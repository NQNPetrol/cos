/**
 * Landing Page JavaScript
 * 
 * Este archivo contiene las funcionalidades específicas del landing page.
 * Las animaciones principales (AOS, GSAP, Swiper) se cargan desde CDN en el blade.
 */

// Importamos Alpine.js si está disponible
import Alpine from 'alpinejs';

// Aseguramos que Alpine esté disponible globalmente
window.Alpine = Alpine;

// Inicializar Alpine si no está ya inicializado
if (!window.Alpine._started) {
    Alpine.start();
}

/**
 * Typing Effect para textos
 */
function typeWriter(element, text, speed = 50) {
    let i = 0;
    element.textContent = '';
    
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    
    type();
}

/**
 * Contador animado
 */
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    function updateCounter() {
        start += increment;
        if (start < target) {
            element.textContent = Math.floor(start);
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
        }
    }
    
    updateCounter();
}

/**
 * Lazy loading de imágenes
 */
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
}

/**
 * Parallax Effect
 */
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    window.addEventListener('scroll', () => {
        const scrollY = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.parallax || 0.5;
            const yPos = -(scrollY * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });
}

/**
 * Smooth reveal on scroll
 */
function initScrollReveal() {
    const revealElements = document.querySelectorAll('[data-reveal]');
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, {
        threshold: 0.1
    });
    
    revealElements.forEach(el => revealObserver.observe(el));
}

/**
 * Video autoplay management
 */
function initVideoAutoplay() {
    const videos = document.querySelectorAll('video[data-autoplay]');
    
    const videoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const video = entry.target;
            if (entry.isIntersecting) {
                video.play();
            } else {
                video.pause();
            }
        });
    }, {
        threshold: 0.5
    });
    
    videos.forEach(video => videoObserver.observe(video));
}

/**
 * Form validation helpers
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return re.test(phone);
}

/**
 * Inicialización cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar funcionalidades
    initLazyLoading();
    initParallax();
    initScrollReveal();
    initVideoAutoplay();
    
    // Exponer utilidades globalmente si es necesario
    window.landingUtils = {
        typeWriter,
        animateCounter,
        validateEmail,
        validatePhone
    };
});

export { typeWriter, animateCounter, validateEmail, validatePhone };
