<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JACED Furniture</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
    @stack('styles') 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden; /* Mengunci layar agar tidak bisa digeser ke kanan jika ada elemen bocor */
        }
        
        .main-content {
            flex: 1;
            transition: padding-top 0.3s ease;
        }

        /* Jarak default dari atas untuk halaman selain Homepage di Layar Desktop */
        .main-content.default-content {
            padding-top: 6rem !important;
        }
        
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
        --beige-50: #FAF7F2; --beige-100: #F2EDE3; --beige-200: #E8DFD0; --beige-300: #D9CCBA;
        --brown-400: #A8896A; --brown-500: #8B6E50; --brown-600: #6E5540; --brown-700: #4A3828; --brown-800: #2E2218;
        --text-primary: #2E2218; --text-secondary: #6E5540; --text-muted: #A8896A; --white: #FFFDF9;
        }

        #chat-launcher { position: fixed; bottom: 28px; right: 28px; width: 56px; height: 56px; background: var(--brown-600); border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 32px rgba(46,34,24,0.45), 0 2px 8px rgba(46,34,24,0.25); transition: transform 0.2s, background 0.2s; z-index: 1100; }
        #chat-launcher:hover { background: var(--brown-700); transform: scale(1.05); }
        #chat-launcher svg { width: 24px; height: 24px; fill: var(--white); }
        #chat-launcher .icon-close { display: none; }

        #chat-widget { position: fixed; bottom: 96px; right: 28px; width: 440px; height: 480px; background: var(--white); border-radius: 20px; box-shadow: 0 8px 48px rgba(46,34,24,0.18); display: flex; flex-direction: column; overflow: hidden; z-index: 1099; transform: scale(0.92) translateY(16px); opacity: 0; pointer-events: none; transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s; }
        #chat-widget.open { transform: scale(1) translateY(0); opacity: 1; pointer-events: all; box-shadow: 0 16px 64px rgba(46,34,24,0.35), 0 4px 16px rgba(46,34,24,0.2); }

        .chat-header { background: var(--brown-700); padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .chat-avatar { width: 36px; height: 36px; background: var(--beige-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .chat-header-info { flex: 1; }
        .chat-header-name { font-size: 14px; font-weight: 500; color: var(--white); }
        .chat-header-status { font-size: 11px; color: var(--beige-300); display: flex; align-items: center; gap: 5px; margin-top: 1px; }
        .status-dot { width: 6px; height: 6px; background: #7EC87E; border-radius: 50%; }

        .budget-bar { background: var(--beige-100); border-bottom: 1px solid var(--beige-200); padding: 10px 14px; flex-shrink: 0; }
        .budget-bar-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
        .budget-label { font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text-muted); font-weight: 500; }
        .budget-value { font-size: 12px; font-weight: 500; color: var(--brown-600); background: var(--white); padding: 2px 10px; border-radius: 20px; border: 1px solid var(--beige-300); }
        .budget-slider { width: 100%; accent-color: var(--brown-600); height: 3px; cursor: pointer; margin-bottom: 8px; }
        .budget-presets { display: flex; gap: 5px; flex-wrap: wrap; }
        .budget-preset { font-size: 11px; padding: 3px 10px; border-radius: 20px; border: 1px solid var(--beige-300); background: transparent; color: var(--text-secondary); cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.15s; white-space: nowrap; }
        .budget-preset:hover { border-color: var(--brown-400); color: var(--brown-600); }
        .budget-preset.active { background: var(--brown-600); color: var(--white); border-color: var(--brown-600); }
        .budget-active-pill { display: none; align-items: center; gap: 4px; font-size: 11px; background: #EAF3DE; color: #3B6D11; padding: 2px 8px 2px 10px; border-radius: 20px; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
        .budget-active-pill.visible { display: flex; }
        .budget-active-pill .clear-x { font-size: 13px; line-height: 1; margin-left: 2px; opacity: 0.7; }

        #budget-collapsible { overflow: hidden; transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 100px; opacity: 1; margin-top: 8px; }
        #budget-collapsible.collapsed { max-height: 0; opacity: 0; margin-top: 0; }

        .chat-messages { flex: 1; overflow-y: auto; padding: 1.25rem; display: flex; flex-direction: column; gap: 14px; background: var(--beige-50); scroll-behavior: smooth; }
        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--beige-300); border-radius: 4px; }

        .msg-row { display: flex; gap: 8px; align-items: flex-end; }
        .msg-row.user { flex-direction: row-reverse; }
        .msg-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--beige-50); display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
        .msg-bubble { max-width: 78%; padding: 10px 14px; border-radius: 16px; font-size: 13.5px; line-height: 1.55; color: var(--text-primary); }
        .msg-row.bot .msg-bubble { background: var(--white); border-bottom-left-radius: 4px; box-shadow: 0 1px 4px rgba(46,34,24,0.07); }
        .msg-row.user .msg-bubble { background: var(--brown-600); color: var(--white); border-bottom-right-radius: 4px; }

        .product-cards { display: flex; flex-direction: column; gap: 10px; margin-top: 6px; width: 100%; }
        .product-card { background: var(--white); border: 1px solid var(--beige-200); border-radius: 14px; overflow: hidden; display: flex; transition: box-shadow 0.15s; cursor: pointer; }
        .product-card:hover { box-shadow: 0 4px 16px rgba(46,34,24,0.1); }
        .product-card-img { width: 100px; min-height: 100px; background: var(--beige-100); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; flex-shrink: 0; }
        .product-card-info { padding: 10px 12px; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .product-card-category { font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 3px; }
        .product-card-name { font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 3px; }
        .product-card-dim { font-size: 11px; color: var(--text-muted); margin-bottom: 6px; }
        .product-card-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .product-card-price { font-size: 14px; font-weight: 500; color: var(--brown-600); }
        .product-card-btn { font-size: 11px; background: var(--brown-600); color: var(--white); border: none; border-radius: 6px; padding: 4px 10px; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.15s; }
        .product-card-btn:hover { background: var(--brown-700); }
        .match-tag { font-size: 10px; background: #EAF3DE; color: #3B6D11; padding: 2px 8px; border-radius: 20px; display: inline-block; margin-bottom: 4px; font-weight: 500; }

        .typing-indicator { display: flex; gap: 4px; align-items: center; padding: 12px 14px; background: var(--white); border-radius: 16px; border-bottom-left-radius: 4px; width: fit-content; box-shadow: 0 1px 4px rgba(46,34,24,0.07); }
        .typing-dot { width: 6px; height: 6px; background: var(--beige-300); border-radius: 50%; animation: typingBounce 1.2s infinite; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typingBounce { 0%, 60%, 100% { transform: translateY(0); background: var(--beige-300); } 30% { transform: translateY(-6px); background: var(--brown-400); } }

        .quick-replies { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
        .quick-reply-btn { font-size: 12px; background: transparent; border: 1px solid var(--beige-300); color: var(--brown-600); border-radius: 20px; padding: 5px 12px; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.15s; white-space: nowrap; }
        .quick-reply-btn:hover { background: var(--brown-600); color: var(--white); border-color: var(--brown-600); }

        .chat-input-area { padding: 12px 14px; background: var(--white); border-top: 1px solid var(--beige-200); display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0; }
        .chat-input { flex: 1; background: var(--beige-100); border: 1px solid var(--beige-200); border-radius: 22px; padding: 9px 16px; font-size: 13.5px; font-family: 'DM Sans', sans-serif; color: var(--text-primary); resize: none; outline: none; max-height: 100px; line-height: 1.45; transition: border-color 0.15s; }
        .chat-input:focus { border-color: var(--brown-400); }
        .chat-input::placeholder { color: var(--text-muted); }
        .send-btn { width: 38px; height: 38px; background: var(--brown-600); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.15s, transform 0.1s; }
        .send-btn:hover { background: var(--brown-700); }
        .send-btn:active { transform: scale(0.94); }
        .send-btn svg { width: 16px; height: 16px; fill: var(--white); }
        .notif-badge { position: absolute; top: -3px; right: -3px; width: 16px; height: 16px; background: #D85A30; border-radius: 50%; font-size: 10px; color: white; display: flex; align-items: center; justify-content: center; font-weight: 500; }
    </style>
</head>
<body>

    {{-- Komponen Navbar Atas --}}
    @include('include.header')
    
    {{-- Pembungkus Konten Utama Dinamis --}}
    <div class="container-fluid main-content px-4 px-md-5 py-5 {{ request()->routeIs('home') ? '' : 'default-content' }}" style="margin:0 auto; max-width: 1400px;">
        @yield('content')
    </div>
    
    {{-- GLOBAL CART SIDEBAR --}}
    @include('partials.cart-sidebar')

    {{-- Komponen Footer Bawah --}}
    @include('include.footer')

    
    <!-- Chatbot Launcher button -->
    <button id="chat-launcher" onclick="toggleChat()" aria-label="Open chat">
    <svg class="icon-chat" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
    </svg>
    <svg class="icon-close" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
    </svg>
    <span class="notif-badge" id="notif-badge">1</span>
    </button>

    <!-- Chat widget -->
    <div id="chat-widget">
    <div class="chat-header">
        <div class="chat-avatar"><img src="{{ asset('image/jaced_logo1.png') }}" style="width:24px;height:24px;object-fit:contain;border-radius:50%;"></div>
        <div class="chat-header-info">
        <div class="chat-header-name">JACED Furniture Assistant</div>
        <div class="chat-header-status"><span class="status-dot"></span>Online now</div>
        </div>
    </div>

    <!-- Budget filter bar -->
    {{-- <div class="budget-bar" id="budget-bar">
        <div class="budget-bar-top">
        <span class="budget-label">Budget filter</span>
        <span class="budget-value" id="budget-display">All prices</span>
        </div>
        <input type="range" class="budget-slider" id="budget-slider"
        min="1000000" max="10000000" step="500000" value="10000000"
        oninput="onBudgetSlide(this.value)">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:6px;">
        <div class="budget-presets" id="budget-presets">
            <button class="budget-preset" onclick="setBudgetPreset(3000000, this)">Under 3jt</button>
            <button class="budget-preset" onclick="setBudgetPreset(5000000, this)">Under 5jt</button>
            <button class="budget-preset" onclick="setBudgetPreset(8000000, this)">Under 8jt</button>
            <button class="budget-preset active" onclick="setBudgetPreset(10000000, this)">Any Price</button>
        </div>
        <button class="budget-active-pill" id="budget-pill" onclick="clearBudget()">
            <span id="budget-pill-text"></span>
            <span class="clear-x">×</span>
        </button>
        </div>
    </div> --}}

    <div class="budget-bar" id="budget-bar">
        <div class="budget-bar-top" onclick="toggleBudgetBar()" style="cursor:pointer;">
            <span class="budget-label">Budget filter</span>
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="budget-value" id="budget-display">All prices</span>
                <svg id="budget-chevron" style="width:14px;height:14px;fill:var(--text-muted);transition:transform 0.2s;" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
            </div>
        </div>
        <div id="budget-collapsible">
            <input type="range" class="budget-slider" id="budget-slider"
                min="1000000" max="30000000" step="500000" value="30000000"
                oninput="onBudgetSlide(this.value)">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">
                <div class="budget-presets" id="budget-presets">
                    <button class="budget-preset" onclick="setBudgetPreset(3000000, this)">Under 3mil</button>
                    <button class="budget-preset" onclick="setBudgetPreset(5000000, this)">Under 5mil</button>
                    <button class="budget-preset" onclick="setBudgetPreset(10000000, this)">Under 10mil</button>
                    <button class="budget-preset active" onclick="setBudgetPreset(30000000, this)">Any Price</button>
                </div>
                <button class="budget-active-pill" id="budget-pill" onclick="clearBudget()">
                    <span id="budget-pill-text"></span>
                    <span class="clear-x">×</span>
                </button>
            </div>
        </div>
    </div>

    <div class="chat-messages" id="chat-messages"></div>

    <div class="chat-input-area">
        <textarea class="chat-input" id="chat-input" placeholder="Ask about furniture, room sizes..." rows="1"></textarea>
        <button class="send-btn" onclick="sendMessage()" aria-label="Send">
        <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
    </div>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartSidebar = document.getElementById('cartSidebar');

            if (cartSidebar) {
                // Mengembalikan keadaan boks belanja terbuka otomatis setelah halaman di-reload
                if (localStorage.getItem('cartOpen') === 'true') {
                    const bsOffcanvas = new bootstrap.Offcanvas(cartSidebar);
                    bsOffcanvas.show();
                }

                // Rekam ke memori lokal browser saat boks belanja dibuka
                cartSidebar.addEventListener('shown.bs.offcanvas', () => {
                    localStorage.setItem('cartOpen', 'true');
                });

            cartSidebar.addEventListener('hidden.bs.offcanvas', () => {
                localStorage.setItem('cartOpen', 'false');
            });
            }
        });

        // ===== BUDGET STATE =====
        let activeBudget = 30000000; // default = no filter (show all)
        let budgetBarOpen = true;

        function toggleBudgetBar() {
            budgetBarOpen = !budgetBarOpen;
            document.getElementById('budget-collapsible').classList.toggle('collapsed', !budgetBarOpen);
            document.getElementById('budget-chevron').style.transform = budgetBarOpen ? 'rotate(0deg)' : 'rotate(-90deg)';
        }
        function formatRupiah(val) {
        if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1).replace('.0','') + 'mil';
        return 'Rp ' + val.toLocaleString('id-ID');
        }

        function onBudgetSlide(val) {
            activeBudget = parseInt(val);
            const display = document.getElementById('budget-display');
            const pill = document.getElementById('budget-pill');
            const pillText = document.getElementById('budget-pill-text');

            // Clear active preset buttons
            document.querySelectorAll('.budget-preset').forEach(b => b.classList.remove('active'));

            if (activeBudget >= 30000000) {
                display.textContent = 'All prices';
                pill.classList.remove('visible');
                // Mark "Any Price" as active
                document.querySelectorAll('.budget-preset')[3].classList.add('active');
            } else {
                display.textContent = 'Under ' + formatRupiah(activeBudget);
                pillText.textContent = '≤ ' + formatRupiah(activeBudget);
                pill.classList.add('visible');
            }
        }

        function setBudgetPreset(val, btn) {
        activeBudget = val;
        document.getElementById('budget-slider').value = val;
        document.querySelectorAll('.budget-preset').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const display = document.getElementById('budget-display');
        const pill = document.getElementById('budget-pill');
        const pillText = document.getElementById('budget-pill-text');

        if (val >= 30000000) {
            display.textContent = 'All prices';
            pill.classList.remove('visible');
        } else {
            display.textContent = 'Under ' + formatRupiah(val);
            pillText.textContent = '≤ ' + formatRupiah(val);
            pill.classList.add('visible');
        }
        }

        function clearBudget() {
        setBudgetPreset(30000000, document.querySelectorAll('.budget-preset')[3]);
        document.getElementById('budget-slider').value = 30000000;
        }

        // ===== STATE =====
        let isOpen = false;
        let messages = JSON.parse(sessionStorage.getItem('chatMessages') || '[]');
        let isTyping = false;

        // ===== INIT =====
        window.onload = () => {
            // restore previous messages if any
            if (messages.length > 0) {
                const saved = JSON.parse(sessionStorage.getItem('chatParsed') || '[]');
                saved.forEach(m => {
                    if (m.role === 'user') addUserMessage(m.text);
                    else addBotMessage(m.parsed);
                });
            } else {
                addBotMessage({
                    message: "Hey there! 👋 Welcome to JACED Furniture! I'm here to help you find the perfect furniture for your home. You can tell me about your room size, style preferences, or what you're looking for!",
                    products: [],
                    quick_replies: ["I need a sofa for my living room", "Help me furnish my bedroom", "I have a small dining area"]
                });
            }
        };

        // ===== TOGGLE =====
        function toggleChat() {
        isOpen = !isOpen;
        const widget = document.getElementById('chat-widget');
        const launcher = document.getElementById('chat-launcher');
        const badge = document.getElementById('notif-badge');

        widget.classList.toggle('open', isOpen);
        launcher.querySelector('.icon-chat').style.display = isOpen ? 'none' : 'block';
        launcher.querySelector('.icon-close').style.display = isOpen ? 'block' : 'none';
        if (badge) badge.style.display = 'none';

        if (isOpen) {
            setTimeout(() => document.getElementById('chat-input').focus(), 300);
            scrollToBottom();
        }
        }

        // ===== SEND =====
        async function sendMessage() {
            const input = document.getElementById('chat-input');
            const text = input.value.trim();
            if (!text || isTyping) return;

            input.value = '';
            input.style.height = 'auto';

            addUserMessage(text);
            messages.push({ role: 'user', content: text });

            showTyping();
            isTyping = true;

            try {
                const response = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        messages: messages,
                        budget: activeBudget < 30000000 ? activeBudget : null,
                    })
                });

                const data = await response.json();
                const rawText = data.content.map(b => b.text || '').join('');

                let parsed;
                try {
                const clean = rawText.replace(/```json|```/g, '').trim();
                parsed = JSON.parse(clean);
                } catch {
                parsed = { message: rawText, products: [], quick_replies: [] };
                }

                messages.push({ role: 'user', content: text });
                sessionStorage.setItem('chatMessages', JSON.stringify(messages));

                // and after assistant response:
                messages.push({ role: 'assistant', content: rawText });
                sessionStorage.setItem('chatMessages', JSON.stringify(messages));

                hideTyping();
                isTyping = false;
                addBotMessage(parsed);

            } catch (err) {
                hideTyping();
                isTyping = false;
                addBotMessage({
                message: "Sorry, I had trouble connecting. Please try again in a moment!",
                products: [],
                quick_replies: ["Try again", "Show all sofas", "Show all beds"]
                });
            }
        }

        // ===== RENDER MESSAGES =====
        function addUserMessage(text) {
            const msgs = document.getElementById('chat-messages');
            const row = document.createElement('div');
            row.className = 'msg-row user';
            row.innerHTML = `<div class="msg-bubble">${escapeHtml(text)}</div>`;
            msgs.appendChild(row);
            scrollToBottom();

            // save to session
            const saved = JSON.parse(sessionStorage.getItem('chatParsed') || '[]');
            saved.push({ role: 'user', text });
            sessionStorage.setItem('chatParsed', JSON.stringify(saved));
        }

        function addBotMessage(parsed) {
            const msgs = document.getElementById('chat-messages');

            // Text bubble
            const row = document.createElement('div');
            row.className = 'msg-row bot';

            let inner = `<div class="msg-avatar"><img src="/image/jaced_logo1.png" style="width:20px;height:20px;object-fit:contain;border-radius:50%;"></div><div style="display:flex;flex-direction:column;gap:8px;max-width:82%">`;
            inner += `<div class="msg-bubble">${escapeHtml(parsed.message)}</div>`;

            // Product cards — filter by active budget
            if (parsed.products && parsed.products.length > 0) {
                inner += `<div class="product-cards">`;
                parsed.products.forEach(p => {
                    const imgHtml = p.image_url
                        ? `<img src="${p.image_url}" style="width:100px;min-height:100px;object-fit:cover;flex-shrink:0;" alt="${p.name}">`
                        : `<div class="product-card-img">🪑</div>`;
                    const slug = p.slug || toSlug(p.name);
                    inner += `
                    <div class="product-card" onclick="window.location='/product/${slug}'">
                        ${imgHtml}
                        <div class="product-card-info">
                            <div class="product-card-category">${p.category}</div>
                            <div class="match-tag">✓ Good match</div>
                            <div class="product-card-name">${p.name}</div>
                            <div class="product-card-dim">📐 ${p.dimensions}</div>
                            <div class="product-card-footer">
                                <span class="product-card-price">${p.price}</span>
                                <button class="product-card-btn" onclick="event.stopPropagation(); handleAddToCart('${slug}', '${p.name}')">Add to Collection</button>
                            </div>
                        </div>
                    </div>`;
                });
                inner += `</div>`;
            }

            // Quick replies
            if (parsed.quick_replies && parsed.quick_replies.length > 0) {
                inner += `<div class="quick-replies">`;
                parsed.quick_replies.forEach(qr => {
                inner += `<button class="quick-reply-btn" onclick="sendQuickReply('${qr.replace(/'/g, "\\'")}')">${qr}</button>`;
                });
                inner += `</div>`;
            }

            inner += `</div>`;
            row.innerHTML = inner;
            msgs.appendChild(row);
            scrollToBottom();

            // save to session at the end
            const saved = JSON.parse(sessionStorage.getItem('chatParsed') || '[]');
            saved.push({ role: 'bot', parsed });
            sessionStorage.setItem('chatParsed', JSON.stringify(saved));
        }

        // ===== TYPING =====
        let typingEl = null;
        function showTyping() {
            const msgs = document.getElementById('chat-messages');
            const row = document.createElement('div');
            row.className = 'msg-row bot';
            row.id = 'typing-row';
            row.innerHTML = `<div class="msg-avatar"><img src="/image/jaced_logo1.png" style="width:20px;height:20px;object-fit:contain;border-radius:50%;"></div><div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>`;
            msgs.appendChild(row);
            scrollToBottom();
        }

        function hideTyping() {
            const el = document.getElementById('typing-row');
            if (el) el.remove();
        }

        // ===== UTILS =====
        function scrollToBottom() {
            const msgs = document.getElementById('chat-messages');
            setTimeout(() => msgs.scrollTop = msgs.scrollHeight, 50);
        }

        function escapeHtml(text) {
            return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // Auto-resize textarea
        document.getElementById('chat-input').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        // Send on Enter (Shift+Enter for new line)
        document.getElementById('chat-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });

        function handleAddToCart(slug, name) {
            @auth
            window.location = '/product/' + slug;
            @else
            addBotMessage({
                message: "To add " + name + " to your cart, you'll need to log in first. Would you like to go to the login page?",
                products: [],
                quick_replies: ["Take me to login", "Continue browsing"]
            });
            @endauth
        }

        // also handle the "Take me to login" quick reply
        function sendQuickReply(text) {
            if (text === 'Take me to login') {
                window.location = '/login';
                return;
            }
            document.getElementById('chat-input').value = text;
            sendMessage();
        }

        // add this helper function in your JS
        function toSlug(name) {
        return name.toLowerCase()
            .replace(/\s*-\s*/g, '-')  // normalize " - " to "-" first
            .replace(/[^a-z0-9\s-]/g, '')
            .trim().replace(/\s+/g, '-');
        }   
    </script>
    
    @stack('scripts')

    {{-- Toast Notification --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <div id="toast-notif" style="position:fixed; top:24px; right:24px; z-index:99999; animation:slideInToast .3s ease; min-width:300px; max-width:400px;">
        <div style="background:white; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.12); display:flex; align-items:center; gap:14px; padding:16px 20px;">
            {{-- Icon Bulat --}}
            <div style="
                width:38px; height:38px; border-radius:50%; flex-shrink:0;
                display:flex; align-items:center; justify-content:center;
                background:{{ session('success') ? '#e8f5e9' : (session('error') ? '#fdecea' : (session('warning') ? '#fff8e1' : '#e3f2fd')) }};
            ">
                @if(session('success'))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @elseif(session('error'))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                @elseif(session('warning'))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f57f17" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1565c0" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                @endif
            </div>

            {{-- Text --}}
            <div style="flex:1;">
                <p style="margin:0; font-size:13px; font-weight:600; color:#1a1714;">
                    {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
                </p>
            </div>

            {{-- Close Button --}}
            <button onclick="document.getElementById('toast-notif').remove()" 
                style="background:none; border:none; color:#aaa; font-size:20px; cursor:pointer; padding:0; line-height:1; flex-shrink:0;">×</button>
        </div>
    </div>

    <style>
    @keyframes slideInToast {
        from { opacity:0; transform:translateY(-16px); }
        to   { opacity:1; transform:translateY(0); }
    }
    </style>

    <script>
        // Auto hide after 4 seconds
        setTimeout(() => {
            const toast = document.getElementById('toast-notif');
            if (toast) {
                toast.style.transition = 'opacity .3s, transform .3s';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(8px)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>
    @endif
</body>
</html>