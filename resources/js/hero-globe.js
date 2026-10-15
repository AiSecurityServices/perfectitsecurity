/**
 * hero-globe.js — AISecurity Intelligence Core.
 *
 * A signature real-time 3D scene: a luminous central AI core ringed by a
 * three-dimensional neural network of service nodes (AI agents, IT support,
 * cybersecurity, cloud, automation), secure data pathways with travelling
 * pulses, orbital rings, and atmospheric depth in cyan / intelligent blue /
 * restrained violet.
 *
 * Performance & robustness:
 * - Dynamically imported only when #hero-globe-canvas exists (see home view).
 * - Caps DPR (1.5 desktop / 1 mobile), reduced geometry on small screens.
 * - Pauses rendering when off-screen (IntersectionObserver) or tab hidden.
 * - Respects prefers-reduced-motion → static CSS fallback, no WebGL.
 * - Full GPU cleanup (dispose) on pagehide / hot-reload.
 * - Decorative only: canvas is aria-hidden, CTAs never depend on it.
 */

import * as THREE from 'three';

const CYAN = 0x54e5ff;
const BLUE = 0x3478f6;
const VIOLET = 0x9474ff;

function webglAvailable() {
    try {
        const c = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
    } catch {
        return false;
    }
}

export default class HeroGlobe {
    constructor(canvas = 'hero-globe-canvas') {
        this.canvas = typeof canvas === 'string' ? document.getElementById(canvas) : canvas;
        if (!this.canvas || this.canvas.dataset.globeInit === '1') return;
        this.canvas.dataset.globeInit = '1';

        this.stage = this.canvas.closest('.hero-globe-stage');
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.isMobile = window.matchMedia('(max-width: 768px)').matches;
        this.visible = true;
        this.destroyed = false;
        this.pointer = { x: 0, y: 0, tx: 0, ty: 0 };
        this.clock = new THREE.Clock();
        this.disposables = [];

        if (this.reduced || !webglAvailable()) {
            this.fallback();
            return;
        }
        try {
            this.init();
        } catch {
            this.fallback();
        }
    }

    fallback() {
        this.stage?.setAttribute('data-globe', 'fallback');
    }

    track(obj) {
        this.disposables.push(obj);
        return obj;
    }

    init() {
        const canvas = this.canvas;
        this.renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: !this.isMobile, powerPreference: 'high-performance' });
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, this.isMobile ? 1.25 : 1.75));
        this.renderer.setClearColor(0x000000, 0);

        this.scene = new THREE.Scene();
        this.scene.fog = new THREE.FogExp2(0x060b14, 0.05);

        this.camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
        this.camera.position.set(0, 0.5, 8.6);

        this.world = new THREE.Group();
        this.scene.add(this.world);

        // Cinematic lighting: cool key + violet rim + core glow.
        this.scene.add(new THREE.AmbientLight(0x8fb4ff, 0.55));
        const key = new THREE.DirectionalLight(0xbfefff, 1.1);
        key.position.set(4, 3, 5);
        this.scene.add(key);
        const rim = new THREE.DirectionalLight(VIOLET, 0.9);
        rim.position.set(-5, -2, -4);
        this.scene.add(rim);
        const coreLight = new THREE.PointLight(CYAN, 14, 14, 2);
        coreLight.position.set(0, 0, 0);
        this.world.add(coreLight);
        this.coreLight = coreLight;

        this.buildStarfield();
        this.buildCore();
        this.buildNeuralNetwork();
        this.buildRings();

        this.resize();
        this.bind();
        this.loop();
    }

    buildStarfield() {
        const count = this.isMobile ? 300 : 800;
        const pos = new Float32Array(count * 3);
        for (let i = 0; i < count; i++) {
            const r = 18 + Math.random() * 30;
            const t = Math.random() * Math.PI * 2;
            const p = Math.acos(2 * Math.random() - 1);
            pos[i * 3] = r * Math.sin(p) * Math.cos(t);
            pos[i * 3 + 1] = r * Math.sin(p) * Math.sin(t);
            pos[i * 3 + 2] = r * Math.cos(p);
        }
        const g = this.track(new THREE.BufferGeometry());
        g.setAttribute('position', new THREE.BufferAttribute(pos, 3));
        const m = this.track(new THREE.PointsMaterial({ color: 0xa9c6ff, size: 0.055, transparent: true, opacity: 0.7, sizeAttenuation: true, depthWrite: false }));
        this.stars = new THREE.Points(g, m);
        this.scene.add(this.stars);
    }

    buildCore() {
        this.core = new THREE.Group();

        // ── AISecurity Intelligence Globe: luminous Earth-like sphere ──
        const globe = new THREE.Mesh(
            this.track(new THREE.SphereGeometry(1.6, 48, 36)),
            this.track(new THREE.MeshStandardMaterial({
                color: 0x0a2a5e, emissive: 0x168bff, emissiveIntensity: 0.35,
                roughness: 0.55, metalness: 0.35, transparent: true, opacity: 0.96,
            }))
        );
        this.core.add(globe);

        // Fine wireframe mesh for digital cartography feel.
        const mesh = new THREE.Mesh(
            this.track(new THREE.SphereGeometry(1.63, 28, 20)),
            this.track(new THREE.MeshBasicMaterial({ color: CYAN, wireframe: true, transparent: true, opacity: 0.16 }))
        );
        this.core.add(mesh);

        // Graticule: latitude + longitude rings.
        const gratMat = this.track(new THREE.LineBasicMaterial({ color: BLUE, transparent: true, opacity: 0.35 }));
        for (let i = 1; i < 6; i++) {
            const lat = (i / 6) * Math.PI - Math.PI / 2;
            const r = Math.cos(lat) * 1.62;
            const pts = [];
            for (let s = 0; s <= 72; s++) {
                const a = (s / 72) * Math.PI * 2;
                pts.push(new THREE.Vector3(Math.cos(a) * r, Math.sin(lat) * 1.62, Math.sin(a) * r));
            }
            this.core.add(new THREE.Line(this.track(new THREE.BufferGeometry().setFromPoints(pts)), gratMat));
        }
        for (let i = 0; i < 6; i++) {
            const pts = [];
            const lon = (i / 6) * Math.PI;
            for (let s = 0; s <= 72; s++) {
                const a = (s / 72) * Math.PI * 2;
                pts.push(new THREE.Vector3(
                    Math.cos(a) * 1.62 * Math.cos(lon),
                    Math.sin(a) * 1.62,
                    Math.cos(a) * 1.62 * Math.sin(lon)
                ));
            }
            this.core.add(new THREE.Line(this.track(new THREE.BufferGeometry().setFromPoints(pts)), gratMat));
        }

        // City-light points scattered on the surface.
        const cityCount = this.isMobile ? 220 : 520;
        const cp = new Float32Array(cityCount * 3);
        for (let i = 0; i < cityCount; i++) {
            const y = 1 - 2 * Math.random();
            const rad = Math.sqrt(Math.max(0, 1 - y * y));
            const th = Math.random() * Math.PI * 2;
            // Bias toward "landmass" bands so it reads as geography, not noise.
            const band = Math.sin(y * 4.2 + th * 1.3) > -0.25 ? 1.615 : 1.605;
            cp[i * 3] = Math.cos(th) * rad * band;
            cp[i * 3 + 1] = y * band;
            cp[i * 3 + 2] = Math.sin(th) * rad * band;
        }
        const cityGeo = this.track(new THREE.BufferGeometry());
        cityGeo.setAttribute('position', new THREE.BufferAttribute(cp, 3));
        this.core.add(new THREE.Points(cityGeo, this.track(new THREE.PointsMaterial({
            color: 0xbfefff, size: 0.028, transparent: true, opacity: 0.9,
            sizeAttenuation: true, depthWrite: false,
        }))));

        // Holographic outer lattice + solid inner nucleus for depth.
        const lattice = new THREE.Mesh(
            this.track(new THREE.IcosahedronGeometry(1.78, 1)),
            this.track(new THREE.MeshBasicMaterial({ color: CYAN, wireframe: true, transparent: true, opacity: 0.22 }))
        );
        this.core.add(lattice);

        const nucleus = new THREE.Mesh(
            this.track(new THREE.OctahedronGeometry(0.5, 0)),
            this.track(new THREE.MeshStandardMaterial({
                color: BLUE, emissive: BLUE, emissiveIntensity: 0.9,
                roughness: 0.35, metalness: 0.6, transparent: true, opacity: 0.9,
            }))
        );
        nucleus.position.set(0, 0, 0);
        nucleus.scale.setScalar(0.9);
        this.core.add(nucleus);

        // Shield identity emblem floating in front of the globe.
        const shieldTex = this.makeShieldTexture();
        if (shieldTex) {
            const shield = new THREE.Mesh(
                this.track(new THREE.PlaneGeometry(1.05, 1.05)),
                this.track(new THREE.MeshBasicMaterial({ map: shieldTex, transparent: true, depthWrite: false }))
            );
            shield.position.set(0, 0.1, 1.72);
            this.core.add(shield);
            this.shieldSprite = shield;
        }

        // Security emblem: twin thin shield-rings around the nucleus.
        const shieldMat = this.track(new THREE.MeshBasicMaterial({ color: VIOLET, transparent: true, opacity: 0.55 }));
        const shieldA = new THREE.Mesh(this.track(new THREE.TorusGeometry(1.95, 0.012, 8, 96)), shieldMat);
        shieldA.rotation.x = Math.PI / 2.3;
        const shieldB = new THREE.Mesh(this.track(new THREE.TorusGeometry(2.05, 0.008, 8, 96)), shieldMat);
        shieldB.rotation.x = Math.PI / 1.8;
        shieldB.rotation.y = 0.5;
        this.core.add(shieldA, shieldB);

        // Soft additive aura shell for depth.
        const aura = new THREE.Mesh(
            this.track(new THREE.SphereGeometry(1.95, 32, 32)),
            this.track(new THREE.ShaderMaterial({
                transparent: true,
                blending: THREE.AdditiveBlending,
                side: THREE.BackSide,
                depthWrite: false,
                uniforms: { c: { value: new THREE.Color(CYAN) } },
                vertexShader: 'varying vec3 vN; void main(){ vN = normalize(normalMatrix * normal); gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0); }',
                fragmentShader: 'uniform vec3 c; varying vec3 vN; void main(){ float i = pow(0.72 - dot(vN, vec3(0.0, 0.0, 1.0)), 2.4); gl_FragColor = vec4(c, 1.0) * i; }',
            }))
        );
        this.core.add(aura);

        // Holographic platform: stacked glowing rings + light disc.
        const platCyan = this.track(new THREE.MeshBasicMaterial({ color: CYAN, transparent: true, opacity: 0.65 }));
        const platViolet = this.track(new THREE.MeshBasicMaterial({ color: VIOLET, transparent: true, opacity: 0.5 }));
        const ring1 = new THREE.Mesh(this.track(new THREE.TorusGeometry(2.25, 0.02, 8, 128)), platCyan);
        ring1.rotation.x = Math.PI / 2;
        ring1.position.y = -2.05;
        const ring2 = new THREE.Mesh(this.track(new THREE.TorusGeometry(2.55, 0.012, 8, 128)), platViolet);
        ring2.rotation.x = Math.PI / 2;
        ring2.position.y = -2.18;
        const disc = new THREE.Mesh(
            this.track(new THREE.CircleGeometry(2.1, 64)),
            this.track(new THREE.MeshBasicMaterial({ color: BLUE, transparent: true, opacity: 0.14, blending: THREE.AdditiveBlending, depthWrite: false, side: THREE.DoubleSide }))
        );
        disc.rotation.x = -Math.PI / 2;
        disc.position.y = -2.05;
        this.core.add(ring1, ring2, disc);

        this.world.add(this.core);
        this.spin = { lattice, nucleus, shieldA, shieldB, globe, mesh };
    }

    makeShieldTexture() {
        try {
            const c = document.createElement('canvas');
            c.width = 256; c.height = 256;
            const x = c.getContext('2d');
            // Glow backdrop.
            const g = x.createRadialGradient(128, 118, 10, 128, 128, 120);
            g.addColorStop(0, 'rgba(0,207,255,0.55)');
            g.addColorStop(0.55, 'rgba(22,139,255,0.22)');
            g.addColorStop(1, 'rgba(2,9,22,0)');
            x.fillStyle = g;
            x.fillRect(0, 0, 256, 256);
            // Shield outline.
            x.strokeStyle = '#7fe7ff';
            x.lineWidth = 6;
            x.beginPath();
            x.moveTo(128, 42);
            x.lineTo(188, 66);
            x.lineTo(188, 128);
            x.bezierCurveTo(188, 172, 160, 200, 128, 216);
            x.bezierCurveTo(96, 200, 68, 172, 68, 128);
            x.lineTo(68, 66);
            x.closePath();
            x.stroke();
            x.fillStyle = 'rgba(4,19,38,0.85)';
            x.fill();
            x.strokeStyle = '#00CFFF';
            x.lineWidth = 6;
            x.stroke();
            // Check mark.
            x.strokeStyle = '#ffffff';
            x.lineWidth = 9;
            x.lineCap = 'round';
            x.lineJoin = 'round';
            x.beginPath();
            x.moveTo(104, 126);
            x.lineTo(122, 144);
            x.lineTo(154, 108);
            x.stroke();
            // Wordmark.
            x.fillStyle = '#ffffff';
            x.font = '700 22px Inter, Arial, sans-serif';
            x.textAlign = 'center';
            x.fillText('AISecurity', 128, 240);
            const tex = new THREE.CanvasTexture(c);
            tex.colorSpace = THREE.SRGBColorSpace;
            return tex;
        } catch { return null; }
    }

    buildNeuralNetwork() {
        // Service nodes on a shell around the core: deterministic positions
        // from a fibonacci sphere so the composition is art-directed, not random.
        const count = this.isMobile ? 10 : 18;
        const R = 3.15;
        const pts = [];
        const golden = Math.PI * (3 - Math.sqrt(5));
        for (let i = 0; i < count; i++) {
            const y = 1 - (i / (count - 1)) * 2;
            const rad = Math.sqrt(Math.max(0, 1 - y * y));
            const th = golden * i;
            pts.push(new THREE.Vector3(Math.cos(th) * rad * R, y * R * 0.82, Math.sin(th) * rad * R));
        }

        const palette = [CYAN, BLUE, VIOLET];
        const nodeGeo = this.track(new THREE.SphereGeometry(0.055, 12, 12));
        const haloGeo = this.track(new THREE.SphereGeometry(0.11, 12, 12));
        const haloMat = this.track(new THREE.MeshBasicMaterial({ color: CYAN, transparent: true, opacity: 0.22, blending: THREE.AdditiveBlending, depthWrite: false }));
        this.nodeHalos = [];
        pts.forEach((p, i) => {
            const mat = this.track(new THREE.MeshBasicMaterial({ color: palette[i % palette.length] }));
            const n = new THREE.Mesh(nodeGeo, mat);
            n.position.copy(p);
            const h = new THREE.Mesh(haloGeo, haloMat);
            h.position.copy(p);
            this.world.add(n, h);
            this.nodeHalos.push(h);
        });

        // Secure pathways: core → each node (single LineSegments draw call).
        const linePos = new Float32Array(pts.length * 6);
        pts.forEach((p, i) => {
            linePos[i * 6] = 0; linePos[i * 6 + 1] = 0; linePos[i * 6 + 2] = 0;
            linePos[i * 6 + 3] = p.x; linePos[i * 6 + 4] = p.y; linePos[i * 6 + 5] = p.z;
        });
        const lineGeo = this.track(new THREE.BufferGeometry());
        lineGeo.setAttribute('position', new THREE.BufferAttribute(linePos, 3));
        const lines = new THREE.LineSegments(
            lineGeo,
            this.track(new THREE.LineBasicMaterial({ color: BLUE, transparent: true, opacity: 0.28 }))
        );
        this.world.add(lines);

        // Data pulses travelling outward along a subset of pathways.
        this.packets = [];
        const laneCount = this.isMobile ? 4 : 8;
        const packetGeo = this.track(new THREE.SphereGeometry(0.04, 8, 8));
        const packetMat = this.track(new THREE.MeshBasicMaterial({ color: 0xd8fbff }));
        for (let i = 0; i < laneCount; i++) {
            const target = pts[(i * 2 + 1) % pts.length].clone();
            const dot = new THREE.Mesh(packetGeo, packetMat);
            this.world.add(dot);
            this.packets.push({ target, dot, t: Math.random(), speed: 0.25 + Math.random() * 0.25 });
        }
    }

    buildRings() {
        // Two tilted orbital paths with small satellite markers.
        this.orbits = [];
        const mk = (r, tiltX, tiltZ, color, opacity, sats) => {
            const g = new THREE.Group();
            const ring = new THREE.Mesh(
                this.track(new THREE.TorusGeometry(r, 0.008, 8, 128)),
                this.track(new THREE.MeshBasicMaterial({ color, transparent: true, opacity }))
            );
            g.add(ring);
            const satGeo = this.track(new THREE.SphereGeometry(0.05, 10, 10));
            const satMat = this.track(new THREE.MeshBasicMaterial({ color }));
            const markers = [];
            for (let i = 0; i < sats; i++) {
                const s = new THREE.Mesh(satGeo, satMat);
                g.add(s);
                markers.push(s);
            }
            g.rotation.x = tiltX;
            g.rotation.z = tiltZ;
            this.world.add(g);
            this.orbits.push({ group: g, r, markers, speed: 0.15 + Math.random() * 0.15, off: Math.random() * Math.PI * 2 });
        };
        mk(3.4, Math.PI / 2.4, 0.25, CYAN, 0.5, this.isMobile ? 1 : 2);
        mk(3.9, Math.PI / 1.9, -0.35, VIOLET, 0.4, 1);
    }

    bind() {
        this.onPointer = (e) => {
            const x = (e.clientX ?? 0) / window.innerWidth - 0.5;
            const y = (e.clientY ?? 0) / window.innerHeight - 0.5;
            this.pointer.tx = x;
            this.pointer.ty = y;
        };
        this.onResize = () => this.resize();
        this.onVis = () => {
            this.visible = document.visibilityState === 'visible' && this.inView !== false;
        };

        window.addEventListener('pointermove', this.onPointer, { passive: true });
        window.addEventListener('resize', this.onResize);
        document.addEventListener('visibilitychange', this.onVis);

        this.io = new IntersectionObserver((entries) => {
            this.inView = entries[0]?.isIntersecting ?? true;
            this.visible = this.inView && document.visibilityState === 'visible';
        }, { threshold: 0.05 });
        this.io.observe(this.canvas);

        this.onUnload = () => this.dispose();
        window.addEventListener('pagehide', this.onUnload);
    }

    resize() {
        if (!this.renderer || !this.canvas) return;
        const w = this.canvas.clientWidth || this.canvas.parentElement?.clientWidth || 600;
        const h = this.canvas.clientHeight || this.canvas.parentElement?.clientHeight || 480;
        this.renderer.setSize(w, h, false);
        this.camera.aspect = w / h;
        this.camera.updateProjectionMatrix();
    }

    loop() {
        if (this.destroyed) return;
        this.frameId = requestAnimationFrame(() => this.loop());
        if (!this.visible) return;

        const dt = Math.min(this.clock.getDelta(), 0.05);
        const t = this.clock.elapsedTime;

        // Ambient rotation + gentle pointer parallax.
        this.pointer.x += (this.pointer.tx - this.pointer.x) * 0.04;
        this.pointer.y += (this.pointer.ty - this.pointer.y) * 0.04;
        this.world.rotation.y += dt * 0.1;
        this.world.rotation.x = this.pointer.y * 0.22;
        this.camera.position.x = this.pointer.x * 0.7;
        this.camera.position.y = 0.5 - this.pointer.y * 0.5;
        this.camera.lookAt(0, 0, 0);
        if (this.stars) this.stars.rotation.y += dt * 0.004;

        // Core choreography: breathing lattice, counter-rotating nucleus,
        // precessing shield rings, pulsing point light.
        if (this.spin) {
            this.spin.lattice.rotation.y += dt * 0.25;
            this.spin.lattice.rotation.x = Math.sin(t * 0.3) * 0.2;
            const breathe = 1 + Math.sin(t * 1.6) * 0.04;
            this.spin.lattice.scale.setScalar(breathe);
            if (this.spin.globe) this.spin.globe.rotation.y += dt * 0.06;
            if (this.spin.mesh) this.spin.mesh.rotation.y += dt * 0.06;
            this.spin.nucleus.rotation.y -= dt * 0.5;
            this.spin.nucleus.rotation.x += dt * 0.18;
            this.spin.shieldA.rotation.z += dt * 0.4;
            this.spin.shieldB.rotation.z -= dt * 0.3;
        }
        if (this.coreLight) this.coreLight.intensity = 13 + Math.sin(t * 1.6) * 3;

        // Node halo pulse.
        this.nodeHalos?.forEach((h, i) => {
            const s = 1 + Math.sin(t * 2 + i * 1.3) * 0.18;
            h.scale.setScalar(s);
        });

        // Data pulses: core → node pathways.
        this.packets?.forEach((p) => {
            p.t = (p.t + dt * p.speed) % 1;
            p.dot.position.copy(p.target).multiplyScalar(p.t);
            const s = 0.6 + p.t * 0.8;
            p.dot.scale.setScalar(s);
        });

        // Orbiting satellites.
        this.orbits?.forEach((o) => {
            o.markers.forEach((s, i) => {
                const a = t * o.speed + o.off + (i * Math.PI * 2) / o.markers.length;
                s.position.set(Math.cos(a) * o.r, 0, Math.sin(a) * o.r);
            });
        });

        this.renderer.render(this.scene, this.camera);
    }

    dispose() {
        if (this.destroyed) return;
        this.destroyed = true;
        cancelAnimationFrame(this.frameId);
        this.io?.disconnect();
        window.removeEventListener('pointermove', this.onPointer);
        window.removeEventListener('resize', this.onResize);
        document.removeEventListener('visibilitychange', this.onVis);
        window.removeEventListener('pagehide', this.onUnload);
        this.disposables.forEach((d) => d?.dispose?.());
        this.renderer?.dispose();
    }

    /** Initialise every homepage-style globe on the page (one per hero visual). */
    static initAll() {
        const instances = [];
        document.querySelectorAll('canvas.hero-globe-canvas:not([data-globe-init])').forEach((canvas) => {
            try {
                const inst = new HeroGlobe(canvas);
                if (inst.canvas) instances.push(inst);
            } catch { /* decorative only */ }
        });
        return instances;
    }
}
