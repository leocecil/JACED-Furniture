@extends('base.base')

@push('styles')
<style>
    .privacy-page {
        background-color: var(--jaced-cream);
        min-height: 100vh;
        padding: 60px 16px;
    }

    .privacy-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 48px;
        border-radius: 20px;
        border: 1px solid var(--jaced-input);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    .privacy-header {
        text-align: center;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--jaced-cream);
        padding-bottom: 30px;
    }

    .privacy-header h1 {
        color: var(--jaced-brown-dark);
        font-weight: 700;
        font-size: 2rem;
    }

    .privacy-content h4 {
        color: var(--jaced-brown);
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .privacy-content p, .privacy-content li {
        color: var(--jaced-brown-dark);
        line-height: 1.8;
        font-size: 0.95rem;
        opacity: 0.85;
    }

    .privacy-content ul {
        padding-left: 20px;
        margin-bottom: 20px;
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
<div class="privacy-page">
    <div class="privacy-container">
        {{-- BACK --}}
        <a href="{{ route('profile') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back</span>
        </a>

        <div class="privacy-header">
            <h1>Privacy Policy</h1>
            <p class="text-muted small">Last Updated: May 2026</p>
        </div>

        <div class="privacy-content font-serif-jaced">
            <p>At <strong>Jaced</strong>, your privacy is a priority. This Privacy Policy explains how we collect, use, and protect your personal information when you use our website.</p>

            <h4>1. Information We Collect</h4>
            <p>We collect information that you provide directly to us, including:</p>
            <ul>
                <li>Personal details (Name, email address, phone number).</li>
                <li>Shipping information (Home address, city, postal code).</li>
                <li>Account credentials (Encrypted passwords).</li>
            </ul>

            <h4>2. How We Use Your Information</h4>
            <p>We use the collected data for various purposes:</p>
            <ul>
                <li>To process and deliver your furniture orders.</li>
                <li>To manage your Jaced account and Artisan Points.</li>
                <li>To send transactional emails and occasional marketing updates (you can opt-out anytime).</li>
            </ul>

            <h4>3. Data Security</h4>
            <p>We implement industry-standard security measures to protect your data. Your passwords are encrypted, and we do not store sensitive payment information directly on our servers.</p>

            <h4>4. Third-Party Services</h4>
            <p>We may share your data with trusted partners (e.g., shipping couriers and payment gateways) only to the extent necessary to fulfill your orders.</p>

            <h4>5. Your Rights</h4>
            <p>You have the right to access, correct, or delete your personal information through your Profile settings. If you wish to permanently delete your account, you can use the "Delete Account" feature in your dashboard.</p>

            <div class="mt-5 p-4 rounded text-center" style="background-color: var(--jaced-cream);">
                <p class="mb-2"><strong>Need to clear things up?</strong></p>
                <p class="mb-0">Feel free to reach out to our privacy team at <br>
                <a href="mailto:privacy@jaced.com" style="color: var(--jaced-caramel); font-weight: 600;">privacy@jaced.com</a></p>
            </div>
        </div>
    </div>
</div>
@endsection