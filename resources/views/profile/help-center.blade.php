@extends('base.base')

@push('styles')
<style>
    .help-page {
        background-color: var(--jaced-cream);
        min-height: 100vh;
        padding: 60px 16px;
    }

    .help-wrapper {
        max-width: 700px;
        margin: 0 auto;
    }

    .help-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .help-header h2 {
        color: var(--jaced-brown-dark);
        font-weight: 700;
        margin-bottom: 12px;
    }

    /* Accordion Style */
    .faq-item {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--jaced-input);
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-question {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }

    .faq-question h6 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--jaced-brown-dark);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background-color: #fafafa;
    }

    .faq-answer-content {
        padding: 0 24px 20px;
        font-size: 0.9rem;
        line-height: 1.6;
        color: var(--jaced-muted);
    }

    /* Rotation icon */
    .faq-icon {
        transition: transform 0.3s ease;
        color: var(--jaced-caramel);
    }

    .faq-item.active .faq-icon {
        transform: rotate(180deg);
    }

    .faq-item.active .faq-answer {
        max-height: 200px; /* Adjust as needed */
    }

    /* Contact Section */
    .contact-card {
        margin-top: 40px;
        background: var(--jaced-brown-dark);
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        color: white;
    }

    .btn-contact {
        background: var(--jaced-caramel);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        margin-top: 15px;
        text-decoration: none;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="help-page">
    <div class="help-wrapper">
        
        <div class="help-header">
            <a href="{{ route('profile') }}" class="text-decoration-none d-inline-flex align-items-center mb-3" style="color: var(--jaced-caramel);">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span class="ms-2 fw-bold">Back</span>
            </a>
            <h2>Help Center</h2>
            <p class="text-muted">Find answers to your questions about Jaced furniture.</p>
        </div>

        {{-- FAQ Items --}}
        <div class="faq-list">
            
            <div class="faq-item">
                <div class="faq-question">
                    <h6>How do I track my order?</h6>
                    <div class="faq-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        You can track your order in the "Transaction" menu. We will provide a tracking number once the artisan finishes your furniture and ships it.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h6>Can I request custom furniture?</h6>
                    <div class="faq-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Yes! Most of our artisans accept custom orders. You can click the "Request Custom" button on the artisan's profile page to start a discussion.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h6>What are Artisan Points?</h6>
                    <div class="faq-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Artisan Points are rewards you get for every purchase. You can redeem these points for exclusive discounts or vouchers in the Reward Center.
                    </div>
                </div>
            </div>

        </div>

        {{-- Contact Support --}}
        <div class="contact-card">
            <h5>Still have questions?</h5>
            <p class="opacity-75 small">Our team is ready to help you find the perfect piece for your home.</p>
            <a href="https://wa.me/6281226449681" class="btn-contact">Chat with Support</a>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const item = question.parentElement;
            
            // Close other items (Optional: comment this out if you want multiple open)
            document.querySelectorAll('.faq-item').forEach(otherItem => {
                if (otherItem !== item) otherItem.classList.remove('active');
            });

            item.classList.toggle('active');
        });
    });
</script>
@endsection