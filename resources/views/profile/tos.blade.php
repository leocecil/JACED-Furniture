@extends('base.base')

@push('styles')
<style>
    .tos-page {
        background-color: var(--jaced-cream);
        min-height: 100vh;
        padding: 60px 16px;
    }

    .tos-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 48px;
        border-radius: 20px;
        border: 1px solid var(--jaced-input);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    .tos-header {
        text-align: center;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--jaced-cream);
        padding-bottom: 30px;
    }

    .tos-header h1 {
        color: var(--jaced-brown-dark);
        font-weight: 700;
        font-size: 2rem;
    }

    .tos-content h4 {
        color: var(--jaced-brown);
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .tos-content p, .tos-content li {
        color: var(--jaced-brown-dark);
        line-height: 1.8;
        font-size: 0.95rem;
        opacity: 0.85;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--jaced-muted);
        text-decoration: none;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="tos-page">
    <div class="tos-container">
        <a href="{{ url()->previous() }}" class="back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>

        <div class="tos-header">
            <h1>Terms of Service</h1>
            <p class="text-muted small">Last Updated: May 2026</p>
        </div>

        <div class="tos-content font-serif-jaced">
            <p>Welcome to <strong>Jaced</strong>. By accessing our website and using our services, you agree to comply with and be bound by the following terms and conditions.</p>

            <h4>1. Use of Service</h4>
            <p>Our platform is designed to provide high-quality artisan products. You agree to use the service only for lawful purposes and in a way that does not infringe the rights of others.</p>

            <h4>2. Account Responsibility</h4>
            <p>When you create an account, you are responsible for maintaining the security of your account and password. Jaced cannot and will not be liable for any loss or damage from your failure to comply with this security obligation.</p>

            <h4>3. Intellectual Property</h4>
            <p>The content, logo, designs, and artisan works displayed on Jaced are the property of Jaced or its content creators. You may not reproduce or use any of our property without prior written consent.</p>

            <h4>4. Limitation of Liability</h4>
            <p>Jaced shall not be liable for any indirect, incidental, or consequential damages resulting from the use or inability to use our services or products.</p>

            <h4>5. Changes to Terms</h4>
            <p>We reserve the right to modify these terms at any time. We will notify users of any significant changes by posting the new terms on this page.</p>

            <div class="mt-5 p-4 bg-light rounded shadow-sm" style="background-color: var(--jaced-cream) !important;">
                <p class="mb-0 text-center">Questions about the Terms of Service? <br> 
                <a href="mailto:support@jaced.com" style="color: var(--jaced-caramel);">Contact our support team</a></p>
            </div>
        </div>
    </div>
</div>
@endsection