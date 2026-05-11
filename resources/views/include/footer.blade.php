<footer class="bg-[#1a1f24] text-gray-400 py-16 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            
            <div class="space-y-6">
                <div class="flex items-center gap-2 text-white">
                    <div class="bg-white p-1 rounded">
                        <svg class="w-5 h-5 text-[#1a1f24]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-widest">FURNISPACE</span>
                </div>
                <p class="text-sm leading-relaxed max-w-xs">
                    Redefining architectural spaces through precision-engineered furniture and immersive 3D technology.
                </p>
                <div class="flex gap-3">
                    @foreach(['IG', 'TW', 'FB', 'LI'] as $social)
                        <a href="#" class="w-10 h-10 rounded-full border border-gray-600 flex items-center justify-center text-xs font-semibold hover:bg-white hover:text-black transition-all">
                            {{ $social }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-6">Collection</h4>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-white transition">New Arrivals</a></li>
                    <li><a href="#" class="hover:text-white transition">Best Sellers</a></li>
                    <li><a href="#" class="hover:text-white transition">Limited Edition</a></li>
                    <li><a href="#" class="hover:text-white transition">Architect Series</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-6">Company</h4>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-white transition">Our Story</a></li>
                    <li><a href="#" class="hover:text-white transition">Sustainability</a></li>
                    <li><a href="#" class="hover:text-white transition">Careers</a></li>
                    <li><a href="#" class="hover:text-white transition">Press Kit</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-6">Newsletter</h4>
                <p class="text-sm mb-6">Join our community for architectural insights and early access.</p>
                <form class="flex gap-2">
                    <div class="relative flex-grow">
                        <input type="email" placeholder="Email address" 
                            class="w-full bg-[#2a2f35] border border-gray-700 rounded-full py-3 px-6 text-sm focus:outline-none focus:border-gray-500 transition">
                    </div>
                    <button type="submit" class="bg-[#8b764d] hover:bg-[#a18a5d] text-white p-3 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] uppercase tracking-widest">
            <p>© 2026 FURNISPACE ARCHITECTURAL DESIGN. ALL RIGHTS RESERVED.</p>
            <div class="flex gap-8">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>