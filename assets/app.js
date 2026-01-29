import './bootstrap.js';
import './styles/app.scss';

document.addEventListener('DOMContentLoaded', () => {
    // ── Burger menu ──
    const burgerMenu = document.getElementById('burgerMenu');
    const navMenu = document.getElementById('navMenu');

    if (burgerMenu && navMenu) {
        burgerMenu.addEventListener('click', () => {
            burgerMenu.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                burgerMenu.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });

        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !burgerMenu.contains(e.target)) {
                burgerMenu.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }

    // ── Scroll reveal ──
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        reveals.forEach(el => observer.observe(el));
    }

    // ── Interactive star rating ──
    document.querySelectorAll('.star-rating label').forEach(label => {
        label.addEventListener('click', function () {
            const value = this.querySelector('input').value;
            document.querySelectorAll('.star').forEach((star, index) => {
                star.textContent = index < value ? '\u2B50' : '\u2606';
            });
        });

        label.addEventListener('mouseenter', function () {
            const value = this.querySelector('input').value;
            document.querySelectorAll('.star').forEach((star, index) => {
                star.style.transform = index < value ? 'scale(1.25)' : 'scale(1)';
            });
        });

        label.addEventListener('mouseleave', function () {
            document.querySelectorAll('.star').forEach(star => {
                star.style.transform = 'scale(1)';
            });
        });
    });

    // ── Nav shrink on scroll ──
    const nav = document.querySelector('nav');
    if (nav) {
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            if (scrollY > 60) {
                nav.style.boxShadow = '0 4px 20px rgba(0,0,0,0.4)';
            } else {
                nav.style.boxShadow = '';
            }
            lastScroll = scrollY;
        }, { passive: true });
    }
});
