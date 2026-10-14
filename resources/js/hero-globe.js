/**
 * hero-globe.js — Signature premium 3D infrastructure globe.
 *
 * A lightweight, art-directed "digital infrastructure universe":
 * wireframe globe + graticule, infrastructure nodes, animated data
 * packets travelling along connection arcs, orbital rings, starfield,
 * cinematic fog + pointer parallax.
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

const CYAN = 0x55e6ff;
const VIOLET = 0x9275ff;

function webglAvailable() {
    try {
        const c = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
    } catch {
        return false;
    }
}

export default class HeroGlobe {
    constructor(canvasId = 'hero-globe-canvas') {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        if (this.canvas.dataset.globeInit === '1') return;
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
        this.scene.fog = new THREE.FogExp2(0x070b14, 0.055);

        this.camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
        this.camera.position.set(0, 0.4, 7.4);

        this.world = new THREE.Group();
        this.scene.add(this.world);

        // Cinematic lighting: cool key + violet rim + soft ambient.
        this.scene.add(new THREE.AmbientLight(0x8fb4ff, 0.55));
        const key = new THREE.DirectionalLight(0xbfefff, 1.1);
        key.position.set(4, 3, 5);
        this.scene.add(key);
        const rim = new THREE.DirectionalLight(VIOLET, 0.9);
        rim.position.set(-5, -2, -4);
        this.scene.add(rim);
        const core = new THREE.PointLight(CYAN, 12, 12, 2);
        core.position.set(0, 0, 0);
        this.world.add(core);

        this.buildStarfield();
        this.buildGlobe();
        this.buildNodesAndArcs();
        this.buildRings();

        this.resize();
        this.bind();
        this.loop();
    }

    buildStarfield() {
        const count = this.isMobile ? 350 : 900;
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
        const m = this.track(new THREE.PointsMaterial({ color: 0xa9c6ff, size: 0.055, transparent: true, opacity: 0.75, sizeAttenuation: true, depthWrite: false }));
        this.stars = new THREE.Points(g, m);
        this.scene.add(this.stars);
    }

    buildGlobe() {
        const R = 2.1;
        this.R = R;
        const seg = this.isMobile ? 36 : 56;

        // Translucent inner sphere for depth.
        const inner = new THREE.Mesh(
            this.track(new THREE.SphereGeometry(R * 0.985, seg, seg)),
            this.track(new THREE.MeshBasicMaterial({ color: 0x0d1a30, transparent: true, opacity: 0.85 }))
        );
        this.world.add(inner);

        // Cyan wireframe lattice.
        const wire = new THREE.Mesh(
            this.track(new THREE.SphereGeometry(R, seg, Math.round(seg * 0.7))),
            this.track(new THREE.MeshBasicMaterial({ color: CYAN, wireframe: true, transparent: true, opacity: 0.16 }))
        );
        this.world.add(wire);
        this.globeSpin = [inner, wire];

        // Graticule: a few latitude rings for craft.
        const grat = new THREE.Group();
        [-1.2, -0.6, 0, 0.6, 1.2].forEach((y) => {
            const rr = Math.sqrt(Math.max(R * R - y * y, 0.01));
            const ring = new THREE.Mesh(
                this.track(new THREE.TorusGeometry(rr, 0.0045, 6, 96)),
                this.track(new THREE.MeshBasicMaterial({ color: CYAN, transparent: true, opacity: 0.35 }))
            );
            ring.rotation.x = Math.PI / 2;
            ring.position.y = y;
            grat.add(ring);
        });
        this.world.add(grat);
        this.globeSpin.push(grat);

        // Atmosphere halo (additive backside shell).
        const halo = new THREE.Mesh(
            this.track(new THREE.SphereGeometry(R * 1.12, 48, 48)),
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
        this.scene.add(halo);
    }

    nodePoint(lat, lon) {
        const { R } = this;
        const phi = (90 - lat) * (Math.PI / 180);
        const theta = (lon + 180) * (Math.PI / 180);
        return new THREE.Vector3(
            -R * Math.sin(phi) * Math.cos(theta),
            R * Math.cos(phi),
            R * Math.sin(phi) * Math.sin(theta)
        );
    }

    buildNodesAndArcs() {
        // Fixed, plausible infrastructure node spread (decorative positions).
        const coords = this.isMobile
            ? [[38, -95], [51, 0], [22, 78], [-23, -46], [35, 139], [-33, 151]]
            : [[38, -95], [51, 0], [48, 12], [22, 78], [35, 139], [-33, 151], [-23, -46], [55, 37], [1, 103], [40, -74], [25, 55], [-1, 36]];
        const pts = coords.map(([la, lo]) => this.nodePoint(la, lo));

        const nodeGeo = this.track(new THREE.SphereGeometry(0.045, 12, 12));
        const nodeMat = this.track(new THREE.MeshBasicMaterial({ color: CYAN }));
        const haloGeo = this.track(new THREE.SphereGeometry(0.09, 12, 12));
        const haloMat = this.track(new THREE.MeshBasicMaterial({ color: CYAN, transparent: true, opacity: 0.25, blending: THREE.AdditiveBlending, depthWrite: false }));
        this.nodeMeshes = [];
        pts.forEach((p, i) => {
            const mat = i % 3 === 2 ? this.track(new THREE.MeshBasicMaterial({ color: VIOLET })) : nodeMat;
            const n = new THREE.Mesh(nodeGeo, mat);
            n.position.copy(p);
            const h = new THREE.Mesh(haloGeo, haloMat);
            h.position.copy(p);
            this.world.add(n, h);
            this.nodeMeshes.push(h);
        });

        // Connection arcs (quadratic bezier lifted off surface) + travelling packets.
        this.packets = [];
        const arcMat = this.track(new THREE.LineBasicMaterial({ color: CYAN, transparent: true, opacity: 0.4 }));
        const arcMatV = this.track(new THREE.LineBasicMaterial({ color: VIOLET, transparent: true, opacity: 0.45 }));
        const pairs = this.isMobile ? [[0, 1], [1, 2], [2, 4], [3, 0]] : [[0, 1], [1, 2], [2, 4], [4, 5], [0, 9], [1, 7], [6, 3], [8, 4], [10, 7], [11, 2]];
        const packetGeo = this.track(new THREE.SphereGeometry(0.035, 8, 8));
        const packetMat = this.track(new THREE.MeshBasicMaterial({ color: 0xd8fbff }));
        pairs.forEach(([a, b], i) => {
            const p1 = pts[a].clone();
            const p2 = pts[b].clone();
            const mid = p1.clone().add(p2).multiplyScalar(0.5).normalize().multiplyScalar(this.R * (1.18 + (i % 3) * 0.08));
            const curve = new THREE.QuadraticBezierCurve3(p1, mid, p2);
            const line = new THREE.Line(this.track(new THREE.BufferGeometry()).setFromPoints(curve.getPoints(48)), i % 3 === 2 ? arcMatV : arcMat);
            this.world.add(line);
            const dot = new THREE.Mesh(packetGeo, packetMat);
            this.world.add(dot);
            this.packets.push({ curve, dot, t: Math.random(), speed: 0.12 + Math.random() * 0.12 });
        });
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
        mk(this.R * 1.45, Math.PI / 2.4, 0.25, CYAN, 0.5, this.isMobile ? 1 : 2);
        mk(this.R * 1.68, Math.PI / 1.9, -0.35, VIOLET, 0.4, 1);
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
        this.world.rotation.y += dt * 0.12;
        this.world.rotation.x = this.pointer.y * 0.22;
        this.camera.position.x = this.pointer.x * 0.7;
        this.camera.position.y = 0.4 - this.pointer.y * 0.5;
        this.camera.lookAt(0, 0, 0);
        if (this.stars) this.stars.rotation.y += dt * 0.004;

        // Node halo pulse.
        this.nodeMeshes?.forEach((h, i) => {
            const s = 1 + Math.sin(t * 2 + i * 1.3) * 0.18;
            h.scale.setScalar(s);
        });

        // Data packets along arcs.
        this.packets?.forEach((p) => {
            p.t = (p.t + dt * p.speed) % 1;
            p.dot.position.copy(p.curve.getPoint(p.t));
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
}
