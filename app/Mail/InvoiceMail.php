<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $logoPath;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->logoPath = public_path('image/jaced_logo1.png');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Receipt Order #' . $this->order->id . ' - Jaced Furniture',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-pdf',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.invoice-pdf', [
            'order' => $this->order,
            'logoPath' => public_path('image/jaced_logo1.png'),
        ]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $pdf->output(),
                'Receipt-Order-' . $this->order->id . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}