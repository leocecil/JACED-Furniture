<footer style="background-color: #1a1c20; color: #9ca3af; padding: 60px 0 30px; font-family: 'Segoe UI', sans-serif;">
    <div class="footer-container">
        <div class="footer-grid">
            
            {{-- Brand Column --}}
            <div class="footer-brand-col">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" style="width: 49px; height: 30px;">
                    </div>
                    <span style="color: white; font-size: 22px; font-weight: 700; letter-spacing: -0.01em;">Jaced Furniture</span>
                </div>
                <p style="font-size: 15px; line-height: 1.8; color: #d1d5db; margin-bottom: 24px;">
                    Redefining architectural spaces through precision-engineered furniture and immersive 3D technology.
                </p>
                <div style="display: flex; gap: 12px; margin-bottom: 20px;">
                    @foreach([
                        'IG' => 'https://www.instagram.com', 
                        'TW' => 'https://www.twitter.com', 
                        'FB' => 'https://www.facebook.com', 
                        'LI' => 'https://www.linkedin.com'
                    ] as $social => $url)
                     <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="footer-social-link">
                        {{ $social }}
                     </a>
                    @endforeach
                </div>
            </div>

            {{-- Collection Column --}}
            <div>
                <h3 style="color: white; font-size: 16px; font-weight: 700; margin-bottom: 28px; margin-top: 0;">Collection</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 20px;">
                    <li>
                        <a href="{{ route('shop', ['collection' => 'new']) }}"
                        style="color:#9ca3af; text-decoration:none; font-size:15px;">
                            New Arrivals
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('shop', ['collection' => 'bestseller']) }}"
                        style="color:#9ca3af; text-decoration:none; font-size:15px;">
                            Best Sellers
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('shop', ['collection' => 'limited']) }}"
                        style="color:#9ca3af; text-decoration:none; font-size:15px;">
                            Limited Edition
                        </a>
                    </li>
                    {{-- <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Architect Series</a></li> --}}
                </ul>
            </div>

            {{-- Company Column --}}
            <div>
                <h3 class="footer-heading">Company</h3>
                <ul class="footer-links-list">
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Our Story</a></li>
                    <li><a href="#">Sustainability</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="footer-bottom-bar">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.15em; color: #6b7280; text-transform: uppercase; margin: 0;">
                © 2026 JACED FURNITURE. ALL RIGHTS RESERVED.
            </p>
            <div class="footer-legal-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-container { max-width: 1300px; margin: 0 auto; padding: 0 24px; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 40px; margin-bottom: 60px; }
    .footer-heading { color: white; font-size: 16px; font-weight: 700; margin-bottom: 20px; margin-top: 0; }
    .footer-links-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px; }
    .footer-links-list a { color: #9ca3af; text-decoration: none; font-size: 15px; transition: color 0.2s ease; }
    .footer-links-list a:hover { color: #C99A6B; }
    .footer-social-link { width: 46px; height: 46px; border-radius: 50%; border: 1.5px solid #4b5563; display: inline-flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s; }
    .footer-social-link:hover { border-color: #C99A6B; color: #C99A6B; background: rgba(255,255,255,0.03); }
    .footer-bottom-bar { border-top: 1px solid #2d3139; padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; }
    .footer-legal-links { display: flex; gap: 32px; }
    .footer-legal-links a { font-size: 11px; font-weight: 700; letter-spacing: 0.15em; color: #6b7280; text-transform: uppercase; text-decoration: none; transition: color 0.2s; }
    .footer-legal-links a:hover { color: #C99A6B; }

    @media (max-width: 991.98px) {
        .footer-grid { grid-template-columns: 1fr; gap: 32px; }
        .footer-bottom-bar { flex-direction: column; text-align: center; }
        .footer-legal-links { justify-content: center; width: 100%; gap: 20px; }
    }
</style>