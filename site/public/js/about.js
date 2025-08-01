// ===== ABOUT PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    initStatsAnimation();
    initTimelineAnimation();
});

// ===== STATS COUNTER ANIMATION =====
function initStatsAnimation() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    if ('IntersectionObserver' in window) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumber = entry.target;
                    const targetCount = parseInt(statNumber.dataset.count);
                    animateCounter(statNumber, targetCount);
                    statsObserver.unobserve(statNumber);
                }
            });
        }, {
            threshold: 0.5
        });
        
        statNumbers.forEach(stat => {
            statsObserver.observe(stat);
        });
    } else {
        // Fallback for browsers without IntersectionObserver
        statNumbers.forEach(stat => {
            const targetCount = parseInt(stat.dataset.count);
            stat.textContent = targetCount + (targetCount > 100 ? '+' : '');
        });
    }
}

function animateCounter(element, target) {
    let current = 0;
    const increment = target / 50; // Animation duration control
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        // Format number with + for larger values
        const displayValue = Math.floor(current);
        const suffix = target > 100 ? '+' : '';
        element.textContent = displayValue + suffix;
    }, 40);
}

// ===== TIMELINE ANIMATION =====
function initTimelineAnimation() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    if ('IntersectionObserver' in window) {
        const timelineObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-timeline-item');
                    timelineObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3,
            rootMargin: '0px 0px -50px 0px'
        });
        
        timelineItems.forEach(item => {
            timelineObserver.observe(item);
        });
    }
}

// ===== TEAM MEMBER INTERACTIONS =====
function initTeamInteractions() {
    const teamMembers = document.querySelectorAll('.team-member');
    
    teamMembers.forEach(member => {
        member.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        member.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// ===== SMOOTH SCROLL FOR INTERNAL LINKS =====
function initSmoothScrolling() {
    const internalLinks = document.querySelectorAll('a[href^="#"]');
    
    internalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('.main-header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ===== CONTACT INTEGRATION =====
function initContactIntegration() {
    const contactButtons = document.querySelectorAll('[data-contact-type]');
    
    contactButtons.forEach(button => {
        button.addEventListener('click', function() {
            const contactType = this.dataset.contactType;
            
            switch(contactType) {
                case 'whatsapp':
                    sendWhatsAppMessage('Olá! Vi o site de vocês e gostaria de saber mais sobre os produtos da Só Borracha Ltda.');
                    break;
                case 'email':
                    window.location.href = 'mailto:ronaldo@soborracha.com.br?subject=Contato via Site&body=Olá, gostaria de saber mais sobre os produtos da Só Borracha Ltda.';
                    break;
                case 'phone':
                    window.location.href = 'tel:+5567999180553';
                    break;
            }
        });
    });
}

// ===== PARALLAX EFFECT FOR HERO SECTION =====
function initParallaxEffect() {
    const heroSection = document.querySelector('.page-header');
    
    if (heroSection) {
        window.addEventListener('scroll', throttle(() => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            
            heroSection.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
        }, 16));
    }
}

// ===== UTILITY FUNCTIONS =====
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// ===== CSS ANIMATIONS =====
const aboutStyles = document.createElement('style');
aboutStyles.textContent = `
    .timeline-item {
        opacity: 0;
        transform: translateX(-30px);
        transition: all 0.6s ease-out;
    }
    
    .timeline-item.animate-timeline-item {
        opacity: 1;
        transform: translateX(0);
    }
    
    .timeline-item:nth-child(even).animate-timeline-item {
        animation-delay: 0.2s;
    }
    
    .timeline-item:nth-child(odd).animate-timeline-item {
        animation-delay: 0.1s;
    }
    
    .team-member {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .mvv-item {
        transition: transform 0.3s ease-out;
    }
    
    .mvv-item:hover {
        transform: translateY(-4px);
    }
    
    .stat-number {
        transition: all 0.3s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }
`;
document.head.appendChild(aboutStyles);

// Initialize all functions when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initStatsAnimation();
    initTimelineAnimation();
    initTeamInteractions();
    initSmoothScrolling();
    initContactIntegration();
    initParallaxEffect();
});
