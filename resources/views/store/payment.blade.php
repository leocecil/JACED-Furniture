@extends('base.base')

@section('content')
<div class="container text-center mt-5" style="padding: 100px 0;">
    <h2>Complete Your Payment</h2>
    <p>Please complete your payment using the Midtrans popup.</p>
    <button id="pay-button" class="btn-jaced">Pay Now</button>
</div>
@endsection

{{-- Pindahkan script ke dalam push agar ke-load setelah jQuery/Library utama di base.base --}}
@push('scripts')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
  document.getElementById('pay-button').onclick = function(){
    window.snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        window.location.href = "{{ route('payment_status', $order->id) }}";
      },
      onPending: function(result){
        window.location.href = "{{ route('payment_status', $order->id) }}";
      },
      onError: function(result){
        alert("Payment failed!");
        window.location.href = "{{ route('payment_status', $order->id) }}";
      },
      onClose: function(){
        alert('You closed the popup without finishing the payment');
        window.location.href = "{{ route('payment_status', $order->id) }}";
      }
    });
  };

  window.onload = function() {
      document.getElementById('pay-button').click();
  };
</script>
@endpush