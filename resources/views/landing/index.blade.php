<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
    <title>CYHSUR - Centro de Operaciones de Seguridad | COS</title>
    <meta name="description" content="CYHSUR - Centro de Operaciones de Seguridad integrado. Gestión de eventos, drones, patrullas, cámaras Hikvision y más en una sola plataforma.">
    <meta name="keywords" content="seguridad, COS, drones, hikvision, patrullas, monitoreo 24/7, cámaras de seguridad, control de acceso">
    <meta name="author" content="CYHSUR SA">
    
    {{-- Open Graph / Social Media --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/landing') }}">
    <meta property="og:title" content="CYHSUR - Centro de Operaciones de Seguridad">
    <meta property="og:description" content="Una sola plataforma que integra todos tus sistemas de seguridad. Drones, cámaras, patrullas, eventos y más.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="CYHSUR - Centro de Operaciones de Seguridad">
    <meta name="twitter:description" content="Una sola plataforma que integra todos tus sistemas de seguridad.">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    {{-- AOS CSS --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/landing.js'])
    
    {{-- Custom Landing Styles --}}
    <style>
        :root {
            --color-primary: #1b3761;
            --color-secondary: #203c58;
            --color-dark-1: #16262e;
            --color-dark-2: #0e1321;
            --color-dark-3: #262c35;
            --color-light: #f8fafc;
            --color-accent: #3b82f6;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--color-dark-2);
            color: var(--color-light);
        }
        
        /* Gradient backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        }
        
        .gradient-dark {
            background: linear-gradient(180deg, var(--color-dark-2) 0%, var(--color-dark-1) 100%);
        }
        
        .gradient-accent {
            background: linear-gradient(135deg, var(--color-accent) 0%, #1d4ed8 100%);
        }
        
        /* Glass effect */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Card hover effects */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        /* Glow effects */
        .glow-accent {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
        
        .glow-accent:hover {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.5);
        }
        
        /* Text gradient */
        .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Scroll indicator animation */
        @keyframes scrollIndicator {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(10px); opacity: 0.5; }
        }
        
        .scroll-indicator {
            animation: scrollIndicator 2s ease-in-out infinite;
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Pulse animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        /* Companies carousel */
        .companies-swiper .swiper-wrapper {
            transition-timing-function: linear !important;
        }
        
        /* Tab active state */
        .tab-active {
            background: var(--color-accent);
            color: white;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--color-dark-2);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--color-dark-3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }
        
        /* Hero video overlay */
        .hero-overlay {
            background: linear-gradient(180deg, 
                rgba(14, 19, 33, 0.9) 0%, 
                rgba(14, 19, 33, 0.7) 50%, 
                rgba(14, 19, 33, 0.95) 100%
            );
        }
        
        /* Feature card icon */
        .feature-icon {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
        }
        
        /* Nav blur on scroll */
        .nav-scrolled {
            background: rgba(14, 19, 33, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    {{-- Navigation --}}
    @include('landing.partials.navigation')
    
    {{-- Hero Section --}}
    @include('landing.partials.hero')
    
    {{-- Services Section --}}
    @include('landing.partials.services')
    
    {{-- COS Features Section --}}
    @include('landing.partials.cos-features')
    
    {{-- Product Tour Section --}}
    {{-- @include('landing.partials.product-tour') --}}
    
    {{-- Trusted Companies Section --}}
    @include('landing.partials.trusted-companies')
    
    {{-- Call to Action Section --}}
    @include('landing.partials.cta')
    
    {{-- Contact Form Section --}}
    @include('landing.partials.contact-form')
    
    {{-- Footer --}}
    @include('landing.partials.footer')
    
    {{-- AOS JS --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    
    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    {{-- GSAP --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    {{-- Landing Page Scripts --}}
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
        
        // Navigation scroll effect
        const nav = document.getElementById('landing-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('nav-scrolled');
            } else {
                nav.classList.remove('nav-scrolled');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Initialize Swiper for companies carousel
        const companiesSwiper = new Swiper('.companies-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 60,
            loop: true,
            speed: 5000,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
            freeMode: true,
            grabCursor: true,
        });
        
        // GSAP Animations
        gsap.registerPlugin(ScrollTrigger);
        
        // Hero animations
        gsap.from('.hero-title', {
            opacity: 0,
            y: 60,
            duration: 1,
            ease: 'power3.out',
            delay: 0.3
        });
        
        gsap.from('.hero-subtitle', {
            opacity: 0,
            y: 40,
            duration: 1,
            ease: 'power3.out',
            delay: 0.5
        });
        
        gsap.from('.hero-cta', {
            opacity: 0,
            y: 30,
            duration: 1,
            ease: 'power3.out',
            delay: 0.7
        });
        
        // Feature tabs functionality
        const featureTabs = document.querySelectorAll('.feature-tab');
        const featureContents = document.querySelectorAll('.feature-content');
        
        featureTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetId = tab.dataset.target;
                
                // Remove active class from all tabs
                featureTabs.forEach(t => t.classList.remove('tab-active', 'bg-blue-600'));
                featureTabs.forEach(t => t.classList.add('bg-[#262c35]'));
                
                // Add active class to clicked tab
                tab.classList.add('tab-active', 'bg-blue-600');
                tab.classList.remove('bg-[#262c35]');
                
                // Hide all contents
                featureContents.forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('block');
                });
                
                // Show target content
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                    targetContent.classList.add('block');
                }
            });
        });
        
        // Contact form handling
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = contactForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Enviando...';
                submitBtn.disabled = true;
                
                const formData = new FormData(contactForm);
                
                try {
                    const response = await fetch('{{ route("landing.contact") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Show success message
                        contactForm.innerHTML = `
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4">¡Mensaje Enviado!</h3>
                                <p class="text-gray-400">${data.message}</p>
                            </div>
                        `;
                    } else {
                        throw new Error(data.message || 'Error al enviar');
                    }
                } catch (error) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Show error
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4';
                    errorDiv.textContent = error.message || 'Hubo un error. Por favor, intenta nuevamente.';
                    contactForm.prepend(errorDiv);
                    
                    setTimeout(() => errorDiv.remove(), 5000);
                }
            });
        }
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
