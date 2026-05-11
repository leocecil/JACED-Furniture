<footer style="background-color: #1E2125;" class="text-[#9ca3af] pt-20 pb-10 font-sans">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12 lg:px-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-20">
            
            <div class="lg:col-span-4 flex flex-col pr-0 lg:pr-12">
                <a href="/" class="flex items-center gap-3 mb-6">
                    <div class="bg-white p-1.5 rounded flex items-center justify-center">
                        <svg class="w-6 h-6" style="color: #1E2125;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        </svg>
                    </div>
                    <span class="text-white text-xl font-bold tracking-tight">Jaced Furniture</span>
                </a>
                <p class="text-[15px] leading-relaxed mb-8">
                    Redefining architectural spaces through precision-engineered furniture and immersive 3D technology.
                </p>
                <div class="flex items-center gap-3">
                    @foreach(['IG', 'TW', 'FB', 'LI'] as $social)
                        <a href="#" class="w-11 h-11 rounded-full border border-gray-600 flex items-center justify-center text-xs font-semibold text-gray-300 hover:text-white hover:border-white transition-colors duration-300">
                            {{ $social }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-2 lg:ml-8">
                <h3 class="text-white text-[15px] font-semibold mb-6">Collection</h3>
                <ul class="space-y-4">
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">New Arrivals</a></li>
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Best Sellers</a></li>
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Limited Edition</a></li>
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Architect Series</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-white text-[15px] font-semibold mb-6">Company</h3>
                <ul class="space-y-4">
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Our Story</a></li>
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Sustainability</a></li>
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Careers</a></li>
                    <li><a href="#" class="text-[15px] hover:text-white transition-colors duration-300">Press Kit</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h3 class="text-white text-[15px] font-semibold mb-6">Newsletter</h3>
                <p class="text-[15px] leading-relaxed mb-6 pr-0 lg:pr-8">
                    Join our community for architectural insights and early access.
                </p>
                <form class="relative flex items-center max-w-md">
                    <input type="email" placeholder="Email address" 
                        style="background-color: #2A2D32; border-color: #374151;"
                        class="w-full text-white text-[15px] rounded-full border py-3.5 pl-6 pr-14 focus:outline-none focus:border-gray-400 transition-colors placeholder-gray-500">
                    
                    <button type="submit" style="background-color: #8c7454; color: #1E2125;" class="absolute right-1.5 w-11 h-11 rounded-full flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </form>
            </div>
            
        </div>

        <div class="pt-8 border-t border-gray-700/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[11px] font-bold tracking-[0.15em] text-gray-500 uppercase">
                © 2026 JACED FURNITURE. ALL RIGHTS RESERVED.
            </p>
            <div class="flex gap-8 text-[11px] font-bold tracking-[0.15em] text-gray-500 uppercase">
                <a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors duration-300">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>