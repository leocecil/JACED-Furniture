@extends('base.base')

@section('content')
<div class="container text-center mt-5" style="padding: 100px 0;">
    <h2>Complete Your Payment</h2>
    <p>Please complete your payment using the Midtrans popup.</p>
    <div class="d-flex justify-content-center">
      <button id="pay-button" class="btn-jaced" style="width: auto; padding: 12px 32px;">Pay Now</button>
  </div>
</div>

<div id="payment-toast" style="display:none; position:fixed; top:24px; right:24px; z-index:99999; min-width:300px; max-width:400px;">
    <div style="background:white; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.12); display:flex; align-items:center; gap:14px; padding:16px 20px;">
        <div id="payment-toast-icon" style="width:38px; height:38px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center;"></div>
        <div style="flex:1;">
            <p id="payment-toast-text" style="margin:0; font-size:13px; font-weight:600; color:#1a1714;"></p>
        </div>
    </div>
</div>
@endsection

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
          showPaymentToast('Payment failed. Please try again.', 'error');
          setTimeout(() => {
              window.location.href = "{{ route('payment_status', $order->id) }}";
          }, 3800);
      },
      onClose: function(){
          showPaymentToast('Payment not completed. You can retry anytime.', 'warning');
          setTimeout(() => {
              window.location.href = "{{ route('payment_status', $order->id) }}";
          }, 3800);
      },
    });
  };

  window.onload = function() {
      document.getElementById('pay-button').click();
  };

  function showPaymentToast(message, type) {
    const toast = document.getElementById('payment-toast');
    const icon = document.getElementById('payment-toast-icon');
    const text = document.getElementById('payment-toast-text');

    const config = {
        warning: { bg: '#fff8e1', stroke: '#f57f17', path: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>' },
        error:   { bg: '#fdecea', stroke: '#c62828', path: '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>' },
    };
    const c = config[type] || config.warning;

    icon.style.background = c.bg;
    icon.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${c.stroke}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">${c.path}</svg>`;
    text.textContent = message;

    toast.style.display = 'block';
    toast.style.animation = 'slideInToast .3s ease';
    setTimeout(() => { toast.style.display = 'none'; }, 4000);
  }
</script>
@endpush