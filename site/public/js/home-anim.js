/* home-anim.js — animações leves da home modernizada (Só Borracha)
   Sem dependências. Respeita prefers-reduced-motion.
   - Header ganha classe .scrolled ao rolar (efeito glass compacto)
   - Reveal on scroll das seções .reveal
   - Contagem animada dos números .num[data-count] na faixa de credibilidade
*/
(function () {
    'use strict';

    // Marca que o JS de animação está ativo. Só então os elementos .reveal
    // ficam escondidos (via CSS .js-anim). Sem JS, tudo aparece normalmente
    // e o layout não fica com espaços vazios.
    document.documentElement.classList.add('js-anim');

    // 1) Header scrolled — funciona em todas as páginas (header é global)
    var header = document.querySelector('.main-header');
    if (header) {
        var onScroll = function () {
            header.classList.toggle('scrolled', window.scrollY > 20);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // 2) Reveal on scroll
    var reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        if (reduce || !('IntersectionObserver' in window)) {
            reveals.forEach(function (el) { el.classList.add('in'); });
        } else {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.15 });
            reveals.forEach(function (el, i) {
                el.style.transitionDelay = ((i % 3) * 80) + 'ms';
                io.observe(el);
            });
        }
    }

    // 3) Contagem dos números
    var nums = document.querySelectorAll('.num[data-count]');
    if (nums.length) {
        if (reduce || !('IntersectionObserver' in window)) {
            nums.forEach(function (el) { el.textContent = el.dataset.count + '+'; });
        } else {
            var io2 = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    var el = e.target;
                    var target = parseInt(el.dataset.count, 10) || 0;
                    var n = 0;
                    var step = Math.max(1, Math.floor(target / 40));
                    var timer = setInterval(function () {
                        n += step;
                        if (n >= target) { n = target; clearInterval(timer); }
                        el.textContent = n + '+';
                    }, 28);
                    io2.unobserve(el);
                });
            }, { threshold: 0.5 });
            nums.forEach(function (el) { io2.observe(el); });
        }
    }
})();
