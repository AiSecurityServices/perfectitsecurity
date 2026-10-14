/**
 * scroll-animations.js — Premium scroll-driven reveal animations
 * Uses IntersectionObserver for performance; no external dependencies.
 */

class ScrollAnimations {
    constructor() {
        this.observer = null;
        this.init();
    }

    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            // Immediately reveal all elements without animation
            document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
                el.classList.add('revealed');
            });
            return;
        }

        this.setupRevealObserver();
        this.setup3DCardTilt();
    }

    setupRevealObserver() {
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    this.observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px',
        });

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
            this.observer.observe(el);
        });
    }

    setup3DCardTilt() {
        const cards = document.querySelectorAll('.card-3d');

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -6;
                const rotateY = ((x - centerX) / centerX) * 8;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(8px)`;

                // Update shine position
                const shine = card.querySelector('.card-3d-shine');
                if (shine) {
                    shine.style.setProperty('--mouse-x', `${(x / rect.width) * 100}%`);
                    shine.style.setProperty('--mouse-y', `${(y / rect.height) * 100}%`);
                }
            }, { passive: true });

            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            }, { passive: true });
        });
    }
}

export default ScrollAnimations;
