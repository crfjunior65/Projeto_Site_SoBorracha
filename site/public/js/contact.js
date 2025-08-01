// ===== CONTACT PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    initContactForm();
    initFAQ();
    initPhoneMask();
    initFormValidation();
});

// ===== CONTACT FORM =====
function initContactForm() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                submitForm(this);
            }
        });
    }
}

function submitForm(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    submitBtn.disabled = true;
    
    // Get form data
    const formData = new FormData(form);
    
    // Simulate form submission (replace with actual endpoint)
    setTimeout(() => {
        // Show success message
        showNotification('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
        
        // Reset form
        form.reset();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Clear any validation errors
        clearAllValidationErrors();
        
    }, 2000);
}

// ===== FORM VALIDATION =====
function initFormValidation() {
    const form = document.getElementById('contact-form');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
}

function validateForm() {
    const form = document.getElementById('contact-form');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Validate email format
    const emailField = form.querySelector('#email');
    if (emailField.value && !isValidEmail(emailField.value)) {
        showFieldError(emailField, 'Digite um e-mail válido');
        isValid = false;
    }
    
    // Validate phone format if provided
    const phoneField = form.querySelector('#phone');
    if (phoneField.value && !isValidPhone(phoneField.value)) {
        showFieldError(phoneField, 'Digite um telefone válido');
        isValid = false;
    }
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    
    // Clear previous error
    clearFieldError(field);
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Este campo é obrigatório');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && value && !isValidEmail(value)) {
        showFieldError(field, 'Digite um e-mail válido');
        return false;
    }
    
    // Phone validation
    if (field.name === 'phone' && value && !isValidPhone(value)) {
        showFieldError(field, 'Digite um telefone válido');
        return false;
    }
    
    return true;
}

function showFieldError(field, message) {
    field.classList.add('error');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
}

function clearFieldError(field) {
    field.classList.remove('error');
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

function clearAllValidationErrors() {
    const form = document.getElementById('contact-form');
    const errorElements = form.querySelectorAll('.field-error');
    const errorFields = form.querySelectorAll('.error');
    
    errorElements.forEach(error => error.remove());
    errorFields.forEach(field => field.classList.remove('error'));
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^\(\d{2}\)\s\d{4,5}-\d{4}$/;
    return phoneRegex.test(phone);
}

// ===== PHONE MASK =====
function initPhoneMask() {
    const phoneInput = document.getElementById('phone');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
            }
            
            e.target.value = value;
        });
    }
}

// ===== FAQ ACCORDION =====
function initFAQ() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', function() {
            const isActive = item.classList.contains('active');
            
            // Close all FAQ items
            faqItems.forEach(faqItem => {
                faqItem.classList.remove('active');
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });
}

// ===== CONTACT METHOD INTERACTIONS =====
function initContactMethods() {
    // WhatsApp integration
    const whatsappButtons = document.querySelectorAll('[href*="wa.me"]');
    whatsappButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Track WhatsApp click (analytics)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'contact', {
                    'method': 'whatsapp'
                });
            }
        });
    });
    
    // Phone call tracking
    const phoneButtons = document.querySelectorAll('[href^="tel:"]');
    phoneButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Track phone call (analytics)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'contact', {
                    'method': 'phone'
                });
            }
        });
    });
    
    // Email tracking
    const emailButtons = document.querySelectorAll('[href^="mailto:"]');
    emailButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Track email click (analytics)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'contact', {
                    'method': 'email'
                });
            }
        });
    });
}

// ===== FORM AUTO-FILL FROM URL PARAMS =====
function initFormAutoFill() {
    const urlParams = new URLSearchParams(window.location.search);
    const form = document.getElementById('contact-form');
    
    if (!form) return;
    
    // Auto-fill subject if provided in URL
    const subject = urlParams.get('subject');
    if (subject) {
        const subjectField = form.querySelector('#subject');
        if (subjectField) {
            subjectField.value = subject;
        }
    }
    
    // Auto-fill vehicle if provided in URL
    const vehicle = urlParams.get('vehicle');
    if (vehicle) {
        const vehicleField = form.querySelector('#vehicle');
        if (vehicleField) {
            vehicleField.value = decodeURIComponent(vehicle);
        }
    }
    
    // Auto-fill message if provided in URL
    const message = urlParams.get('message');
    if (message) {
        const messageField = form.querySelector('#message');
        if (messageField) {
            messageField.value = decodeURIComponent(message);
        }
    }
}

// ===== FORM ANALYTICS =====
function trackFormInteraction(action, field = null) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'form_interaction', {
            'action': action,
            'field': field,
            'form_name': 'contact_form'
        });
    }
}

// ===== COPY TO CLIPBOARD =====
function initCopyToClipboard() {
    const copyableElements = document.querySelectorAll('[data-copy]');
    
    copyableElements.forEach(element => {
        element.addEventListener('click', function() {
            const textToCopy = this.dataset.copy || this.textContent;
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    showNotification('Copiado para a área de transferência!', 'success');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = textToCopy;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Copiado para a área de transferência!', 'success');
            }
        });
    });
}

// ===== BUSINESS HOURS INDICATOR =====
function initBusinessHoursIndicator() {
    const now = new Date();
    const currentHour = now.getHours();
    const currentDay = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
    
    let isOpen = false;
    let statusText = '';
    
    // Business hours: Monday-Friday 7:30-17:30, Saturday 8:00-12:00
    if (currentDay >= 1 && currentDay <= 5) { // Monday to Friday
        if (currentHour >= 7 && currentHour < 17) {
            isOpen = true;
            statusText = 'Aberto agora';
        } else if (currentHour === 7 && now.getMinutes() >= 30) {
            isOpen = true;
            statusText = 'Aberto agora';
        } else if (currentHour === 17 && now.getMinutes() < 30) {
            isOpen = true;
            statusText = 'Aberto agora';
        } else {
            statusText = 'Fechado - Abre às 7:30';
        }
    } else if (currentDay === 6) { // Saturday
        if (currentHour >= 8 && currentHour < 12) {
            isOpen = true;
            statusText = 'Aberto agora';
        } else {
            statusText = 'Fechado - Abre segunda às 7:30';
        }
    } else { // Sunday
        statusText = 'Fechado - Abre segunda às 7:30';
    }
    
    // Update status indicators
    const statusIndicators = document.querySelectorAll('.business-status');
    statusIndicators.forEach(indicator => {
        indicator.textContent = statusText;
        indicator.className = `business-status ${isOpen ? 'open' : 'closed'}`;
    });
}

// ===== INITIALIZE ALL FUNCTIONS =====
document.addEventListener('DOMContentLoaded', function() {
    initContactForm();
    initFAQ();
    initPhoneMask();
    initFormValidation();
    initContactMethods();
    initFormAutoFill();
    initCopyToClipboard();
    initBusinessHoursIndicator();
});

// ===== ADDITIONAL STYLES =====
const contactStyles = document.createElement('style');
contactStyles.textContent = `
    .field-error {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgb(239 68 68 / 0.1) !important;
    }
    
    .business-status {
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        display: inline-block;
    }
    
    .business-status.open {
        background: #dcfce7;
        color: #166534;
    }
    
    .business-status.closed {
        background: #fef2f2;
        color: #991b1b;
    }
    
    [data-copy] {
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    [data-copy]:hover {
        color: var(--primary-color);
    }
    
    .form-group input:invalid,
    .form-group textarea:invalid {
        border-color: #ef4444;
    }
    
    .form-group input:valid,
    .form-group textarea:valid {
        border-color: #10b981;
    }
`;
document.head.appendChild(contactStyles);
