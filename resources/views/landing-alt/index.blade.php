<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="COS - Centro de Operaciones de Seguridad. Plataforma integral para gestión de operaciones empresariales.">
    <meta name="keywords" content="COS, centro operaciones, gestión empresarial, software, CYHSUR">
    <title>COS - Centro de Operaciones | CYHSUR</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('cos.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'primary': {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#b9e0fe',
                            300: '#7cc8fd',
                            400: '#36aaf8',
                            500: '#0c8ee9',
                            600: '#0070c7',
                            700: '#0159a1',
                            800: '#064b85',
                            900: '#0a3f6e',
                        },
                        'neutral': {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- AOS - Animate On Scroll (sutil) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <style>
        /* Base styles */
        body {
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Clean transitions */
        .transition-smooth {
            transition: all 0.3s ease;
        }
        
        /* Subtle hover effects */
        .hover-lift:hover {
            transform: translateY(-2px);
        }
        
        /* Video background handling */
        .video-container {
            position: relative;
            overflow: hidden;
        }
        
        .video-container video {
            object-fit: contain;
        }
        
        /* Clean card style */
        .card-clean {
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .card-clean:hover {
            border-color: #d4d4d4;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        /* Feature tabs */
        .feature-tab {
            position: relative;
            padding: 1rem 1.5rem;
            border-left: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .feature-tab.active {
            border-left-color: #0070c7;
            background: #f0f7ff;
        }
        
        .feature-tab:hover:not(.active) {
            background: #fafafa;
        }
        
        /* Screenshot frame */
        .screenshot-frame {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .screenshot-frame img {
            border-radius: 4px;
        }
        
        /* Navigation scroll state */
        .nav-scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-white text-neutral-800">
    
    @include('landing-alt.partials.navigation')
    
    @include('landing-alt.partials.hero')
    
    @include('landing-alt.partials.overview')
    
    @include('landing-alt.partials.features')
    
    @include('landing-alt.partials.modules')
    
    @include('landing-alt.partials.integrations')
    
    @include('landing-alt.partials.contact')
    
    @include('landing-alt.partials.footer')

    <!-- Initialize AOS with subtle settings -->
    <script>
        AOS.init({
            duration: 600,
            once: true,
            offset: 50,
            easing: 'ease-out'
        });
        
        // Navigation scroll effect
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
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
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Acelerar reproducción de videos del logo
        document.querySelectorAll('video.logo-video').forEach(video => {
            video.playbackRate = 2.0; // 2x velocidad
        });
        
        // Feature tabs functionality
        function showFeature(featureId) {
            // Hide all feature contents
            document.querySelectorAll('.feature-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Remove active from all tabs
            document.querySelectorAll('.feature-tab').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected feature
            const content = document.getElementById('feature-' + featureId);
            const tab = document.getElementById('tab-' + featureId);
            
            if (content) content.classList.remove('hidden');
            if (tab) tab.classList.add('active');
        }
        
        // Contact form handling
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("landing.contact") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    const messageDiv = document.getElementById('form-message');
                    
                    if (data.success) {
                        messageDiv.className = 'mt-4 p-4 rounded-lg bg-green-50 text-green-700 text-sm';
                        messageDiv.textContent = data.message;
                        messageDiv.classList.remove('hidden');
                        this.reset();
                    } else {
                        messageDiv.className = 'mt-4 p-4 rounded-lg bg-red-50 text-red-700 text-sm';
                        messageDiv.textContent = data.message || 'Error al enviar el mensaje.';
                        messageDiv.classList.remove('hidden');
                    }
                } catch (error) {
                    const messageDiv = document.getElementById('form-message');
                    messageDiv.className = 'mt-4 p-4 rounded-lg bg-red-50 text-red-700 text-sm';
                    messageDiv.textContent = 'Error de conexión. Intenta nuevamente.';
                    messageDiv.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    </script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>
