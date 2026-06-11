/* ============================================================
   MAIN.JS — Core JavaScript
   RA Attakal Yaqiin — Premium Islamic Early Childhood Website
   ============================================================ */

(function () {
  'use strict';

  /* -----------------------------------------------------------
     0. UTILITY HELPERS
     ----------------------------------------------------------- */
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

  function debounce(fn, ms = 100) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
  }

  function throttle(fn, ms = 100) {
    let last = 0;
    return (...args) => {
      const now = Date.now();
      if (now - last >= ms) { last = now; fn(...args); }
    };
  }

  /* -----------------------------------------------------------
     1. LOADING SCREEN
     ----------------------------------------------------------- */
  function initLoadingScreen() {
    const loader = $('.loading-screen');
    if (!loader) return;

    document.body.classList.add('loading');

    const hide = () => {
      loader.classList.add('loaded');
      document.body.classList.remove('loading');
      setTimeout(() => {
        loader.style.display = 'none';
      }, 600);
    };

    // Hide after load or after max 3s
    window.addEventListener('load', () => setTimeout(hide, 400));
    setTimeout(hide, 3000);
  }

  /* -----------------------------------------------------------
     2. NAVBAR BEHAVIOR
     ----------------------------------------------------------- */
  function initNavbar() {
    const navbar = $('.navbar');
    if (!navbar) return;

    const SCROLL_THRESHOLD = 50;

    // Scroll class
    const onScroll = throttle(() => {
      if (window.scrollY > SCROLL_THRESHOLD) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    }, 50);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // initial

    // Mobile menu toggle
    const toggler = $('.navbar__toggle');
    const mobileMenu = $('.navbar__mobile');
    const overlay = $('.navbar__overlay');
    const body = document.body;

    function openMenu() {
      mobileMenu?.classList.add('active');
      overlay?.classList.add('active');
      toggler?.classList.add('active');
      body.style.overflow = 'hidden';
    }
    function closeMenu() {
      mobileMenu?.classList.remove('active');
      overlay?.classList.remove('active');
      toggler?.classList.remove('active');
      body.style.overflow = '';
    }

    toggler?.addEventListener('click', () => {
      mobileMenu?.classList.contains('active') ? closeMenu() : openMenu();
    });
    overlay?.addEventListener('click', closeMenu);

    // Close on link click
    $$('.navbar__mobile a').forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // Close on escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });

    // Active link highlighting
    const currentPath = window.location.pathname;
    $$('.navbar__link, .navbar__mobile-link').forEach(link => {
      const href = link.getAttribute('href');
      if (href && (href === currentPath || (href !== '/' && currentPath.startsWith(href)))) {
        link.classList.add('active');
      }
    });

    // Dropdown menus (desktop)
    $$('.navbar__dropdown').forEach(dropdown => {
      const trigger = dropdown.querySelector('.navbar__link');
      const menu = dropdown.querySelector('.navbar__dropdown-menu');

      // Toggle on click for touch devices
      trigger?.addEventListener('click', (e) => {
        if (window.innerWidth >= 1024) {
          // On desktop, let hover handle it but toggle on click too
          e.preventDefault();
          dropdown.classList.toggle('open');
          // Close other dropdowns
          $$('.navbar__dropdown').forEach(d => {
            if (d !== dropdown) d.classList.remove('open');
          });
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.navbar__dropdown')) {
        $$('.navbar__dropdown').forEach(d => d.classList.remove('open'));
      }
    });

    // Mobile dropdown accordion
    $$('.navbar__mobile-dropdown-toggle').forEach(toggle => {
      toggle.addEventListener('click', () => {
        const parent = toggle.closest('.navbar__mobile-dropdown');
        parent?.classList.toggle('open');
      });
    });
  }

  /* -----------------------------------------------------------
     3. DARK MODE TOGGLE
     ----------------------------------------------------------- */
  function initDarkMode() {
    const html = document.documentElement;
    const STORAGE_KEY = 'raay-theme';

    // Get saved preference or default to 'auto'
    const saved = localStorage.getItem(STORAGE_KEY) || 'auto';

    function getSystemPreference() {
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function applyTheme(mode) {
      let effective = mode;
      if (mode === 'auto') {
        effective = getSystemPreference();
      }

      html.classList.remove('dark', 'light');
      html.classList.add(effective);
      html.setAttribute('data-theme', mode); // store user intent

      // Update toggle button icons
      $$('.theme-toggle').forEach(btn => {
        const sunIcon = btn.querySelector('.icon-sun');
        const moonIcon = btn.querySelector('.icon-moon');
        const autoIcon = btn.querySelector('.icon-auto');

        if (sunIcon) sunIcon.style.display = mode === 'light' ? 'block' : 'none';
        if (moonIcon) moonIcon.style.display = mode === 'dark' ? 'block' : 'none';
        if (autoIcon) autoIcon.style.display = mode === 'auto' ? 'block' : 'none';

        btn.setAttribute('aria-label',
          mode === 'dark' ? 'Mode terang' :
          mode === 'light' ? 'Mode otomatis' : 'Mode gelap');
        btn.setAttribute('title',
          mode === 'dark' ? 'Beralih ke mode terang' :
          mode === 'light' ? 'Beralih ke mode otomatis' : 'Beralih ke mode gelap');
      });
    }

    // Cycle: light → dark → auto → light …
    function cycleTheme() {
      const current = html.getAttribute('data-theme') || saved;
      let next;
      if (current === 'light') next = 'dark';
      else if (current === 'dark') next = 'auto';
      else next = 'light';

      localStorage.setItem(STORAGE_KEY, next);
      applyTheme(next);
    }

    // Apply initial
    applyTheme(saved);

    // Bind toggle buttons
    $$('.theme-toggle').forEach(btn => {
      btn.addEventListener('click', cycleTheme);
    });

    // Watch system preference changes when auto
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if ((localStorage.getItem(STORAGE_KEY) || 'auto') === 'auto') {
        applyTheme('auto');
      }
    });
  }

  /* -----------------------------------------------------------
     4. SCROLL ANIMATIONS (Intersection Observer)
     ----------------------------------------------------------- */
  function initScrollAnimations() {
    const animatedEls = $$('[data-animate]');
    if (!animatedEls.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const animation = el.dataset.animate || 'fade-in';
          const delay = el.dataset.animateDelay || '0';

          el.style.transitionDelay = delay + 'ms';
          el.classList.add('animated', animation);
          observer.unobserve(el);
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -40px 0px'
    });

    animatedEls.forEach(el => {
      el.classList.add('will-animate');
      observer.observe(el);
    });

    // Stagger grid items
    $$('[data-stagger]').forEach(container => {
      const items = container.children;
      const baseDelay = parseInt(container.dataset.stagger || '80', 10);
      Array.from(items).forEach((item, i) => {
        if (item.hasAttribute('data-animate') && !item.dataset.animateDelay) {
          item.dataset.animateDelay = String(i * baseDelay);
        }
      });
    });
  }

  /* -----------------------------------------------------------
     5. COUNTER ANIMATION
     ----------------------------------------------------------- */
  function initCounters() {
    const counters = $$('[data-count]');
    if (!counters.length) return;

    function easeOutQuart(t) {
      return 1 - Math.pow(1 - t, 4);
    }

    function animateCounter(el) {
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.countSuffix || '';
      const duration = parseInt(el.dataset.countDuration || '2000', 10);
      const start = performance.now();

      function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const value = Math.round(easeOutQuart(progress) * target);
        el.textContent = value.toLocaleString('id-ID') + suffix;

        if (progress < 1) {
          requestAnimationFrame(update);
        }
      }
      requestAnimationFrame(update);
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(el => observer.observe(el));
  }

  /* -----------------------------------------------------------
     6. SMOOTH SCROLL — anchor links
     ----------------------------------------------------------- */
  function initSmoothScroll() {
    document.addEventListener('click', (e) => {
      const anchor = e.target.closest('a[href^="#"]');
      if (!anchor) return;

      const targetId = anchor.getAttribute('href');
      if (targetId === '#' || targetId.length <= 1) return;

      const target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();

      const navHeight = parseInt(getComputedStyle(document.documentElement)
        .getPropertyValue('--navbar-height'), 10) || 80;

      const top = target.getBoundingClientRect().top + window.scrollY - navHeight - 16;

      window.scrollTo({ top, behavior: 'smooth' });

      // Update URL without scroll
      history.pushState(null, '', targetId);
    });
  }

  /* -----------------------------------------------------------
     7. IMAGE LIGHTBOX
     ----------------------------------------------------------- */
  function initLightbox() {
    let lightbox = null;
    let lightboxImg = null;
    let lightboxCaption = null;
    let lightboxClose = null;
    let lightboxPrev = null;
    let lightboxNext = null;
    let currentIndex = 0;
    let galleryImages = [];

    function createLightbox() {
      lightbox = document.createElement('div');
      lightbox.className = 'lightbox';
      lightbox.innerHTML = `
        <div class="lightbox__overlay"></div>
        <button class="lightbox__close" aria-label="Tutup">
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <button class="lightbox__nav lightbox__prev" aria-label="Sebelumnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6l6 6"/></svg>
        </button>
        <button class="lightbox__nav lightbox__next" aria-label="Berikutnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6l-6 6"/></svg>
        </button>
        <div class="lightbox__content">
          <img class="lightbox__img" src="" alt="">
          <p class="lightbox__caption"></p>
        </div>
      `;
      document.body.appendChild(lightbox);

      lightboxImg = lightbox.querySelector('.lightbox__img');
      lightboxCaption = lightbox.querySelector('.lightbox__caption');
      lightboxClose = lightbox.querySelector('.lightbox__close');
      lightboxPrev = lightbox.querySelector('.lightbox__prev');
      lightboxNext = lightbox.querySelector('.lightbox__next');

      lightbox.querySelector('.lightbox__overlay').addEventListener('click', closeLightbox);
      lightboxClose.addEventListener('click', closeLightbox);
      lightboxPrev.addEventListener('click', () => navigate(-1));
      lightboxNext.addEventListener('click', () => navigate(1));
    }

    function openLightbox(index) {
      if (!lightbox) createLightbox();
      currentIndex = index;
      updateLightbox();
      lightbox.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      if (!lightbox) return;
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
    }

    function navigate(dir) {
      currentIndex = (currentIndex + dir + galleryImages.length) % galleryImages.length;
      updateLightbox();
    }

    function updateLightbox() {
      const item = galleryImages[currentIndex];
      if (!item) return;
      lightboxImg.src = item.src;
      lightboxImg.alt = item.alt || '';
      lightboxCaption.textContent = item.alt || '';
      lightboxPrev.style.display = galleryImages.length > 1 ? '' : 'none';
      lightboxNext.style.display = galleryImages.length > 1 ? '' : 'none';
    }

    // Bind gallery images
    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-lightbox]');
      if (!trigger) return;
      e.preventDefault();

      const group = trigger.dataset.lightbox || 'gallery';
      galleryImages = $$(`[data-lightbox="${group}"]`).map(el => ({
        src: el.dataset.lightboxSrc || el.href || el.querySelector('img')?.src || '',
        alt: el.dataset.lightboxAlt || el.querySelector('img')?.alt || ''
      }));

      const idx = $$(`[data-lightbox="${group}"]`).indexOf(trigger);
      openLightbox(idx >= 0 ? idx : 0);
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (!lightbox?.classList.contains('active')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') navigate(-1);
      if (e.key === 'ArrowRight') navigate(1);
    });
  }

  /* -----------------------------------------------------------
     8. PARALLAX EFFECT
     ----------------------------------------------------------- */
  function initParallax() {
    // Disable on mobile for performance
    if (window.innerWidth < 1024) return;

    const parallaxEls = $$('[data-parallax]');
    if (!parallaxEls.length) return;

    const onScroll = throttle(() => {
      const scrollY = window.scrollY;
      parallaxEls.forEach(el => {
        const speed = parseFloat(el.dataset.parallax || '0.3');
        const rect = el.getBoundingClientRect();
        if (rect.bottom >= 0 && rect.top <= window.innerHeight) {
          const yPos = -(scrollY * speed);
          el.style.transform = `translate3d(0, ${yPos}px, 0)`;
        }
      });
    }, 16);

    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* -----------------------------------------------------------
     9. BACK TO TOP BUTTON
     ----------------------------------------------------------- */
  function initBackToTop() {
    const btn = $('.back-to-top');
    if (!btn) return;

    const THRESHOLD = 500;

    const onScroll = throttle(() => {
      if (window.scrollY > THRESHOLD) {
        btn.classList.add('visible');
      } else {
        btn.classList.remove('visible');
      }
    }, 100);

    window.addEventListener('scroll', onScroll, { passive: true });

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* -----------------------------------------------------------
     10. FORM VALIDATION
     ----------------------------------------------------------- */
  function initFormValidation() {
    const forms = $$('form[data-validate]');
    if (!forms.length) return;

    forms.forEach(form => {
      form.setAttribute('novalidate', '');

      form.addEventListener('submit', (e) => {
        let isValid = true;

        // Clear previous errors
        $$('.form-error', form).forEach(el => el.remove());
        $$('.form-control.error', form).forEach(el => el.classList.remove('error'));

        // Validate required fields
        $$('[required]', form).forEach(field => {
          const value = field.value.trim();
          const group = field.closest('.form-group');

          if (!value) {
            isValid = false;
            showError(field, group, 'Kolom ini wajib diisi');
          } else if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            isValid = false;
            showError(field, group, 'Format email tidak valid');
          } else if (field.type === 'tel' && !/^[0-9+\-\s()]{8,15}$/.test(value)) {
            isValid = false;
            showError(field, group, 'Format nomor telepon tidak valid');
          } else if (field.minLength && value.length < field.minLength) {
            isValid = false;
            showError(field, group, `Minimal ${field.minLength} karakter`);
          }
        });

        if (!isValid) {
          e.preventDefault();
          // Scroll to first error
          const firstErr = form.querySelector('.form-control.error');
          firstErr?.scrollIntoView({ behavior: 'smooth', block: 'center' });
          return;
        }

        // Prevent double submission
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.classList.add('btn-loading');
        }
      });

      // Live validation on blur
      $$('[required]', form).forEach(field => {
        field.addEventListener('blur', () => {
          const group = field.closest('.form-group');
          const existingError = group?.querySelector('.form-error');
          if (existingError) existingError.remove();
          field.classList.remove('error');

          if (!field.value.trim()) {
            showError(field, group, 'Kolom ini wajib diisi');
          } else if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value.trim())) {
            showError(field, group, 'Format email tidak valid');
          }
        });

        // Remove error on input
        field.addEventListener('input', () => {
          const group = field.closest('.form-group');
          const existingError = group?.querySelector('.form-error');
          if (existingError) existingError.remove();
          field.classList.remove('error');
        });
      });
    });

    function showError(field, group, message) {
      field.classList.add('error');
      const errorEl = document.createElement('span');
      errorEl.className = 'form-error';
      errorEl.textContent = message;
      if (group) {
        group.appendChild(errorEl);
      } else {
        field.parentNode.insertBefore(errorEl, field.nextSibling);
      }
    }
  }

  /* -----------------------------------------------------------
     11. PAGE TRANSITION
     ----------------------------------------------------------- */
  function initPageTransition() {
    const main = $('.main-content');
    if (main) {
      main.classList.add('page-enter');
    }
  }

  /* -----------------------------------------------------------
     12. WHATSAPP FLOATING BUTTON VISIBILITY
     ----------------------------------------------------------- */
  function initWhatsApp() {
    const waBtn = $('.whatsapp-float');
    if (!waBtn) return;

    // Show after scrolling past hero
    const onScroll = throttle(() => {
      if (window.scrollY > 300) {
        waBtn.classList.add('visible');
      } else {
        waBtn.classList.remove('visible');
      }
    }, 100);

    window.addEventListener('scroll', onScroll, { passive: true });
    // Show immediately if page loaded mid-scroll
    onScroll();
  }

  /* -----------------------------------------------------------
     INIT ALL
     ----------------------------------------------------------- */
  function init() {
    initLoadingScreen();
    initDarkMode();
    initNavbar();
    initSmoothScroll();
    initScrollAnimations();
    initCounters();
    initLightbox();
    initParallax();
    initBackToTop();
    initFormValidation();
    initPageTransition();
    initWhatsApp();
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
