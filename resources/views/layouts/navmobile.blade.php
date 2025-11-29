{{-- File: resources/views/layouts/navmobile.blade.php --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.3/gsap.min.js"></script>
<style>
    body {
        padding-bottom: 120px;
    }

    #navbarContainer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 80px;
        z-index: 50;
    }

    #navbar {
        width: 100%;
        height: 80px;
        background: linear-gradient(90deg, #99E1FF,#31A6D7,#1683B1);
        position: absolute;
        border-radius: 25px 25px 0 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }

    #bubbleWrapper {
        position: absolute;
        display: flex;
        justify-content: space-around;
        width: 100%;
        bottom: 35px;
        z-index: 2;
    }

    .bubble {
        background-color: #D9D9D9;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        transform: translateY(120%);
        box-shadow: none;
    }

    .bubble svg {
        width: 24px;
        height: 24px;
        opacity: 0;
        color: #636CCB;
    }

    .bubble.active {
        transform: translateY(0%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .bubble.active svg {
        opacity: 0.8;
    }

    #bgWrapper {
        filter: url(#goo);
        width: 100%;
        height: 100px;
        position: absolute;
        bottom: 80px;
        pointer-events: none;
    }

    #bg {
        width: 120%;
        height: 100%;
        margin-left: -10%;
    }

    #bgBubble {
        position: absolute;
        background-color: #636CCB;
        width: 4em;
        height: 4em;
        border-radius: 50%;
        bottom: -50px;
        left: 10%;
        transform: translateX(-50%);
    }

    #menuWrapper {
        position: absolute;
        width: 100%;
        display: flex;
        justify-content: space-around;
        bottom: 20px;
    }

    .menuElement {
        opacity: 0.4;
        cursor: pointer;
        transition: opacity 0.2s;
        padding: 10px;
        position: relative;
    }

    .menuElement:hover {
        opacity: 0.6;
    }

    .menuElement svg {
        width: 2remx;
        height: 2rem;
        color: #64748b;
    }

    .label {
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 11px;
        color: #64748b;
        white-space: nowrap;
        font-weight: 500;
    }

    /* Hide on desktop */
    @media (min-width: 768px) {
        #navbarContainer {
            display: none;
        }
    }
</style>

<!-- Bottom Navigation -->
<div id="navbarContainer">
    <div id="navbar">
        <div id="bubbleWrapper">
            <div id="bubble1" class="bubble active">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <div id="bubble2" class="bubble">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div id="bubble3" class="bubble">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div id="bubble4" class="bubble">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div id="bubble5" class="bubble">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
        <div id="menuWrapper">
            <div class="menuElement" data-route="user.homepage" data-url="{{ route('user.homepage') }}" data-id="1" data-position="10%">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="label">Beranda</span>
            </div>
            <div class="menuElement" data-route="barang.index" data-url="{{ route('barang.index') }}" data-id="2" data-position="30%">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span class="label">Barang</span>
            </div>
            <div class="menuElement" data-route="peminjaman.create" data-url="{{ route('peminjaman.create') }}" data-id="3" data-position="50%">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="label">Pinjam</span>
            </div>
            <div class="menuElement" data-route="peminjaman.index" data-url="{{ route('peminjaman.index') }}" data-id="4" data-position="70%">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="label">Riwayat</span>
            </div>
            <div class="menuElement" data-route="profile.index" data-url="{{ route('profile.index') }}" data-id="5" data-position="90%">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="label">Profil</span>
            </div>
        </div>
    </div>
    <div id="bgWrapper">
        <div id="bg"></div>
        <div id="bgBubble"></div>
    </div>
</div>

<svg width="0" height="0">
    <defs>
        <filter id="goo">
            <feGaussianBlur in="SourceGraphic" stdDeviation="20" result="blur" />
            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 30 -15" result="goo" />
            <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
        </filter>
    </defs>
</svg>

<script>
    // Current route dari Laravel
    let currentRoute = '{{ Route::currentRouteName() }}';

    function move(id, position) {
        var tl = gsap.timeline();
        tl.to("#bgBubble", {duration: 0.15, bottom: "-30px", ease: "power2.out"}, 0)
          .to(".bubble", {duration: 0.1, y: "120%", boxShadow: 'none', ease: "power2.out"}, 0)
          .to(".bubble svg", {duration: 0.05, opacity: 0, ease: "power2.out"}, 0)
          .to("#bgBubble", {duration: 0.2, left: position, ease: "power2.inOut"}, 0.1)
          .to("#bgBubble", {duration: 0.15, bottom: "-50px", ease: "power2.out"}, '-=0.2')
          .to(`#bubble${id}`, {duration: 0.15, y: "0%", opacity: 1, boxShadow: '0 4px 12px rgba(0,0,0,0.15)', ease: "power2.out"}, '-=0.1')
          .to(`#bubble${id} svg`, {duration: 0.15, opacity: 0.8, ease: "power2.out"}, '-=0.1');
        
        // Remove active class from all bubbles
        document.querySelectorAll('.bubble').forEach(b => b.classList.remove('active'));
        // Add active class to current bubble
        document.getElementById(`bubble${id}`).classList.add('active');
    }

    // Set active state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const menuElements = document.querySelectorAll('.menuElement');
        
        menuElements.forEach(element => {
            const route = element.getAttribute('data-route');
            const id = element.getAttribute('data-id');
            const position = element.getAttribute('data-position');
            const targetUrl = element.getAttribute('data-url');
            
            // Set initial active state
            if (route === currentRoute) {
                move(id, position);
            }
            
            // Add click event
            element.addEventListener('click', function(e) {
                e.preventDefault();
                move(id, position);
                
                // Navigate after animation
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 300);
            });
        });
    });
</script>