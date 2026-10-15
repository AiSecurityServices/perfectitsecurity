import Alpine from 'alpinejs';
import ScrollAnimations from './scroll-animations.js';

window.Alpine = Alpine;

// PERFECTITSECURITY 3D identity: the terminal CSS layer (.term-bg) stays as
// the base, and the single global WebGL world (global-3d.js — starfield,
// planet, network, shield/glove) renders strictly behind all content as the
// main visual identity. One instance, one loop, loaded as a SEPARATE async chunk
// so the main bundle stays lean; static fallback when WebGL, reduced-motion
// or low-power require it (handled inside Global3DScene).

// Initialise scroll animations on every page
document.addEventListener('DOMContentLoaded', () => {
    window._scrollAnim = new ScrollAnimations();
    try {
        if (document.querySelector('[data-global-3d]') && !window._global3D) {
            import('./global-3d.js')
                .then((m) => { window._global3D = new m.default(); })
                .catch(() => { /* 3D decorative only — never break the page */ });
        }
        // Signature homepage globe: shown on every page hero (one instance
        // per hero visual), separate async chunk so the main bundle stays
        // lean. Module self-handles reduced-motion, WebGL fallback,
        // off-screen pausing, and GPU cleanup.
        if (document.querySelector('canvas.hero-globe-canvas:not([data-globe-init])') && !window._heroGlobes) {
            import('./hero-globe.js')
                .then((m) => { window._heroGlobes = m.default.initAll(); })
                .catch(() => {
                    document.querySelectorAll('.hero-globe-stage').forEach((el) => el.setAttribute('data-globe', 'fallback'));
                });
        }
    } catch (e) { /* 3D decorative only — never break the page */ }

    // Scroll-aware nav: transparent over hero → frosted glass after scroll.
    try {
        const nav = document.getElementById('main-nav');
        if (nav) {
            const onScroll = () => nav.classList.toggle('nav-scrolled', window.scrollY > 24);
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        }
    } catch (e) { /* nav decorative only */ }
});

// Dark mode persistence
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
}

Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: this.dark } }));
    }
});

// Sidebar collapse
Alpine.data('sidebar', () => ({
    collapsed: window.innerWidth < 1024,
    mobileOpen: false,
    init() {
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                this.mobileOpen = false;
            }
        });
    },
    toggle() {
        this.collapsed = !this.collapsed;
    },
    toggleMobile() {
        this.mobileOpen = !this.mobileOpen;
    }
}));



Alpine.start();
