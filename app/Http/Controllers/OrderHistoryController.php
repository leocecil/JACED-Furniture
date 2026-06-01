<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        DB::table('orders')
            ->where('user_id', $user->id)
            ->where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', now()->subDays(7))
            ->update(['status' => 'arrived', 'arrived_at' => now()]);

        $filters = ['All', 'Unpaid', 'On Process', 'Packed', 'Delivered', 'Shipped','Arrived', 'Cancelled', 'Disputed'];
        $activeFilter = $request->get('filter', 'All');

        $query = Order::with(['orderDetails.product'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($activeFilter !== 'All') {
            $query->where('status', strtolower(str_replace(' ', '_', $activeFilter)));
        }

        $orders = $query->get();

        return view('store.order-history', compact('orders', 'filters', 'activeFilter'));
    }

    public function show($id)
    {
        $order = Order::with([
            'orderDetails.product.images',
            'shippingAddress',
            'paymentMethod',
            'voucher',
            'vaBank',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        $dispute = DB::table('order_disputes')
            ->where('order_id', $order->id)
            ->latest()
            ->first();

        return view('store.order-history-detail', compact('order', 'dispute'));
    }
    public function invoice($id)
    {
        $order = \App\Models\Order::with([
            'orderDetails.product.images',
            'shippingAddress',
            'paymentMethod',
            'user',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('store.invoice', compact('order'));
    }

    public function markReceived($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'Order cannot be confirmed.');
        }

        $order->status = 'arrived';
        $order->arrived_at = now();
        $order->save();

        $poinBaru = floor($order->total_price / 10000);
        if ($poinBaru > 0) {
            $user = Auth::user();
            $user->increment('current_points', $poinBaru);
            $user->increment('accumulated_points', $poinBaru);

            DB::table('point_histories')->insert([
                'user_id'    => $user->id,
                'points'     => $poinBaru,
                'type'       => 'earned',
                'source'     => 'purchase',
                'order_id'   => $order->id,
                'expired_at' => now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Order confirmed! You earned ' . $poinBaru . ' points.');
    }

    public function submitComplaint(Request $request, $id)
    {
        $request->validate([
            'type'        => 'required|in:missing,damaged',
            'description' => 'required|string|max:1000',
            'photo'       => $request->input('type') === Rule::requiredIf($request->input('type') === 'damaged'), 'nullable', 'image', 'max:2048',
        ]);

        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'Complaint can only be submitted before the order is confirmed.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        DB::table('order_disputes')->insert([
            'order_id'    => $order->id,
            'reason'      => $request->type,
            'description' => $request->description,
            'photo_path'  => $photoPath,
            'status'      => 'open',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $order->status = 'disputed';
        $order->disputed_at = now();
        $order->save();

        return redirect()->back()->with('success', 'Complaint successfully submitted. Admin will review it soon.');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (!in_array($order->status, ['unpaid', 'on_process'])) {
            return redirect()->back()->with('error', 'Order cannot be cancelled.');
        }

        $needsRefund = $order->status === 'on_process';

        $reason = $request->input('cancellation_reason', 'change_of_mind');
        if ($reason === 'others') {
            $reason = $request->input('other_reason', 'Others');
        }

        $reasonLabel = match($reason) {
            'wrong_address'      => 'Wrong delivery address',
            'change_of_mind'     => 'Change of mind',
            'found_cheaper'      => 'Found a cheaper price',
            'ordered_by_mistake' => 'Ordered by mistake',
            default              => $reason,
        };

        if ($needsRefund) {
            $reasonLabel = '[Refund Requested] ' . $reasonLabel;
        }

        $order->status = 'cancelled';
        $order->cancelled_at = now();
        $order->cancellation_reason = $reasonLabel;
        $order->save();

        $message = $needsRefund
            ? 'Order cancelled. Refund will be processed within 3-5 business days.'
            : 'Order successfully cancelled.';

        return redirect()->back()->with('success', $message);
    }

    public function sendInvoice($id)
    {
        $order = Order::with([
            'orderDetails.product.images',
            'shippingAddress',
            'paymentMethod',
            'user',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        Mail::to($order->user->email)->send(new InvoiceMail($order));

        return redirect()->back()->with('success', 'Invoice has been sent to ' . $order->user->email);
    }

    public function repay($id)
    {
        $order = Order::with(['orderDetails.product', 'paymentMethod', 'vaBank'])->findOrFail($id);

        if ($order->user_id !== Auth::id() || $order->status !== 'unpaid') {
            return redirect()->route('store.orderhistory')->with('error', 'Invalid order.');
        }

        if ($order->is_payment_expired) {
            $order->status = 'cancelled';
            $order->cancelled_at = now();
            $order->cancellation_reason = 'Payment time expired';
            $order->save();
 
            return redirect()->route('store.orderhistory')
                ->with('error', 'Payment time has expired. Your order has been automatically cancelled.');
        }

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $newMidtransOrderId = 'JACED-ORD-' . $order->id . '-' . time();
        $order->update(['midtrans_order_id' => $newMidtransOrderId]);

        $paymentMethod = $order->paymentMethod?->name;
        $chosenBank    = $order->vaBank?->name;

        $enabledPayments = [];
        if ($paymentMethod === 'virtual_account' && !empty($chosenBank)) {
            if ($chosenBank === 'mandiri') {
                $enabledPayments[] = 'echannel';
            } else {
                $enabledPayments[] = $chosenBank . '_va';
            }
        } elseif (!empty($paymentMethod)) {
            $enabledPayments[] = match($paymentMethod) {
                'qris'        => 'other_qris',
                default       => $paymentMethod,
            };
        }

        $remainingMinutes = max(1, (int) now()->diffInMinutes($order->payment_expired_at, false));

        $params = [
            'transaction_details' => [
                'order_id'     => $newMidtransOrderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit'       => 'minutes',
                'duration'   => $remainingMinutes,
            ],
            'callbacks' => [
                'finish' => route('payment_return', $order->id),
            ],
        ];

        if (!empty($enabledPayments)) {
            $params['enabled_payments'] = $enabledPayments;
        }

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('store.payment', compact('snapToken', 'order'));
    }
}
