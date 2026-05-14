<footer style="background-color: #1a1c20; color: #9ca3af; padding: 80px 0 40px; font-family: 'Segoe UI', sans-serif;">
    <div style="max-width: 1300px; margin: 0 auto; padding: 0 60px;">

        {{-- Top Grid --}}
        <div style="display: grid; grid-template-columns: 2.5fr 1fr 1fr 1.5fr; gap: 48px; margin-bottom: 80px;">

            {{-- Brand Column --}}
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1a1c20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <path d="M8 21h8M12 17v4"/>
                            <path d="M2 7h20"/>
                        </svg>
                    </div>
                    <span style="color: white; font-size: 22px; font-weight: 700; letter-spacing: -0.01em;">Jaced Furniture</span>
                </div>

                <p style="font-size: 15px; line-height: 1.8; color: #d1d5db; margin-bottom: 36px;">
                    Redefining architectural spaces through precision-engineered furniture and immersive 3D technology.
                </p>

                <div style="display: flex; gap: 12px;">
                    @foreach(['IG', 'TW', 'FB', 'LI'] as $social)
                        <a href="#" style="width: 52px; height: 52px; border-radius: 50%; border: 1.5px solid #4b5563; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s;">
                            {{ $social }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Collection Column --}}
            <div>
                <h3 style="color: white; font-size: 16px; font-weight: 700; margin-bottom: 28px; margin-top: 0;">Collection</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 20px;">
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">New Arrivals</a></li>
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Best Sellers</a></li>
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Limited Edition</a></li>
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Architect Series</a></li>
                </ul>
            </div>

            {{-- Company Column --}}
            <div>
                <h3 style="color: white; font-size: 16px; font-weight: 700; margin-bottom: 28px; margin-top: 0;">Company</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 20px;">
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Our Story</a></li>
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Sustainability</a></li>
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Careers</a></li>
                    <li><a href="#" style="color: #9ca3af; text-decoration: none; font-size: 15px;">Press Kit</a></li>
                </ul>
            </div>

            {{-- Newsletter Column --}}
            <div>
                <h3 style="color: white; font-size: 16px; font-weight: 700; margin-bottom: 20px; margin-top: 0;">Newsletter</h3>
                <p style="color: #9ca3af; font-size: 15px; line-height: 1.75; margin-bottom: 24px;">
                    Join our community for architectural insights and early access.
                </p>
                <div style="position: relative; display: flex; align-items: center;">
                    <input
                        type="email"
                        placeholder="Email address"
                        style="width: 100%; background-color: #2a2d32; border: 1.5px solid #374151; border-radius: 12px; padding: 14px 56px 14px 18px; color: white; font-size: 14px; outline: none; box-sizing: border-box;"
                    />
                    <button
                        type="submit"
                        style="position: absolute; right: 10px; width: 36px; height: 36px; border-radius: 8px; background-color: #8c7454; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div style="border-top: 1px solid #2d3139; padding-top: 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <p style="font-size: 11px; font-weight: 700; letter-spacing: 0.15em; color: #6b7280; text-transform: uppercase; margin: 0;">
                © 2026 JACED FURNITURE. ALL RIGHTS RESERVED.
            </p>
            <div style="display: flex; gap: 40px;">
                <a href="#" style="font-size: 11px; font-weight: 700; letter-spacing: 0.15em; color: #6b7280; text-transform: uppercase; text-decoration: none;">Privacy Policy</a>
                <a href="#" style="font-size: 11px; font-weight: 700; letter-spacing: 0.15em; color: #6b7280; text-transform: uppercase; text-decoration: none;">Terms of Service</a>
            </div>
        </div>

    </div>
</footer>