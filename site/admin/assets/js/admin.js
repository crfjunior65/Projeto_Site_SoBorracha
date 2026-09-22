/**
 * JavaScript principal do painel administrativo
 * Só Borracha Ltda
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes
    initializeSidebar();
    initializeDropdowns();
    initializeNotifications();
    initializeSearch();
    initializeTooltips();
    initializeModals();
    initializeForms();
    
    /**
     * Inicializar sidebar
     */
    function initializeSidebar() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                
                // Salvar estado no localStorage
                const isOpen = sidebar.classList.contains('open');
                localStorage.setItem('sidebarOpen', isOpen);
            });
            
            // Restaurar estado do sidebar
            const savedState = localStorage.getItem('sidebarOpen');
            if (savedState === 'true') {
                sidebar.classList.add('open');
            }
            
            // Fechar sidebar ao clicar fora (mobile)
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('open');
                    }
                }
            });
        }
    }
    
    /**
     * Inicializar dropdowns
     */
    function initializeDropdowns() {
        const dropdowns = document.querySelectorAll('.user-menu, .notifications');
        
        dropdowns.forEach(dropdown => {
            const trigger = dropdown;
            const menu = dropdown.querySelector('.user-dropdown, .notifications-dropdown');
            
            if (trigger && menu) {
                // Toggle dropdown
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Fechar outros dropdowns
                    dropdowns.forEach(other => {
                        if (other !== dropdown) {
                            other.classList.remove('open');
                        }
                    });
                    
                    dropdown.classList.toggle('open');
                });
                
                // Fechar ao clicar fora
                document.addEventListener('click', function() {
                    dropdown.classList.remove('open');
                });
                
                // Prevenir fechamento ao clicar dentro do menu
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    }
    
    /**
     * Inicializar notificações
     */
    function initializeNotifications() {
        const notificationItems = document.querySelectorAll('.notification-item');
        
        notificationItems.forEach(item => {
            item.addEventListener('click', function() {
                this.classList.add('read');
                
                // Simular marcação como lida
                setTimeout(() => {
                    this.style.opacity = '0.7';
                }, 200);
            });
        });
        
        // Auto-atualizar notificações
        setInterval(checkNewNotifications, 30000); // 30 segundos
    }
    
    /**
     * Verificar novas notificações
     */
    function checkNewNotifications() {
        // Implementar chamada AJAX para verificar novas notificações
        // Por enquanto, apenas simular
        const badge = document.querySelector('.notification-badge');
        if (badge && Math.random() > 0.8) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
            badge.style.display = 'block';
        }
    }
    
    /**
     * Inicializar busca global
     */
    function initializeSearch() {
        const searchInput = document.getElementById('globalSearch');
        if (!searchInput) return;
        
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performGlobalSearch(query);
                }, 300);
            } else {
                hideSearchResults();
            }
        });
        
        // Fechar resultados ao clicar fora
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target)) {
                hideSearchResults();
            }
        });
    }
    
    /**
     * Realizar busca global
     */
    function performGlobalSearch(query) {
        // Simular busca (implementar AJAX real)
        const results = [
            { type: 'product', title: 'Borracha de Porta Universal', url: 'pages/products/edit.php?id=1' },
            { type: 'supplier', title: 'Continental Borrachas', url: 'pages/suppliers/edit.php?id=1' },
            { type: 'setting', title: 'Configurações do Site', url: 'pages/settings/site.php' }
        ].filter(item => item.title.toLowerCase().includes(query.toLowerCase()));
        
        showSearchResults(results);
    }
    
    /**
     * Mostrar resultados da busca
     */
    function showSearchResults(results) {
        let resultsContainer = document.querySelector('.search-results');
        
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.className = 'search-results';
            document.querySelector('.search-box').appendChild(resultsContainer);
        }
        
        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="search-no-results">Nenhum resultado encontrado</div>';
        } else {
            resultsContainer.innerHTML = results.map(result => `
                <a href="${result.url}" class="search-result-item">
                    <i class="fas fa-${getIconForType(result.type)}"></i>
                    <span>${result.title}</span>
                    <small>${getTypeLabel(result.type)}</small>
                </a>
            `).join('');
        }
        
        resultsContainer.style.display = 'block';
    }
    
    /**
     * Esconder resultados da busca
     */
    function hideSearchResults() {
        const resultsContainer = document.querySelector('.search-results');
        if (resultsContainer) {
            resultsContainer.style.display = 'none';
        }
    }
    
    /**
     * Obter ícone para tipo de resultado
     */
    function getIconForType(type) {
        const icons = {
            product: 'box',
            supplier: 'truck',
            setting: 'cog',
            user: 'user'
        };
        return icons[type] || 'file';
    }
    
    /**
     * Obter label para tipo de resultado
     */
    function getTypeLabel(type) {
        const labels = {
            product: 'Produto',
            supplier: 'Fornecedor',
            setting: 'Configuração',
            user: 'Usuário'
        };
        return labels[type] || 'Item';
    }
    
    /**
     * Inicializar tooltips
     */
    function initializeTooltips() {
        const tooltipElements = document.querySelectorAll('[title]');
        
        tooltipElements.forEach(element => {
            const title = element.getAttribute('title');
            element.removeAttribute('title');
            
            element.addEventListener('mouseenter', function(e) {
                showTooltip(e.target, title);
            });
            
            element.addEventListener('mouseleave', function() {
                hideTooltip();
            });
        });
    }
    
    /**
     * Mostrar tooltip
     */
    function showTooltip(element, text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = text;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
        
        setTimeout(() => tooltip.classList.add('show'), 10);
    }
    
    /**
     * Esconder tooltip
     */
    function hideTooltip() {
        const tooltip = document.querySelector('.custom-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }
    
    /**
     * Inicializar modais
     */
    function initializeModals() {
        const modalTriggers = document.querySelectorAll('[data-modal]');
        const modals = document.querySelectorAll('.modal');
        
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('data-modal');
                const modal = document.getElementById(modalId);
                if (modal) {
                    showModal(modal);
                }
            });
        });
        
        modals.forEach(modal => {
            const closeButtons = modal.querySelectorAll('.modal-close, [data-dismiss="modal"]');
            
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    hideModal(modal);
                });
            });
            
            // Fechar ao clicar no overlay
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal(modal);
                }
            });
        });
        
        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    hideModal(openModal);
                }
            }
        });
    }
    
    /**
     * Mostrar modal
     */
    function showModal(modal) {
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }
    
    /**
     * Esconder modal
     */
    function hideModal(modal) {
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
    
    /**
     * Inicializar formulários
     */
    function initializeForms() {
        const forms = document.querySelectorAll('form[data-ajax]');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitFormAjax(this);
            });
        });
        
        // Validação em tempo real
        const inputs = document.querySelectorAll('input[required], textarea[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    }
    
    /**
     * Submeter formulário via AJAX
     */
    function submitFormAjax(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        // Mostrar loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Sucesso!', data.message, 'success');
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                showAlert('Erro!', data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Erro!', 'Ocorreu um erro inesperado.', 'error');
            console.error('Error:', error);
        })
        .finally(() => {
            // Restaurar botão
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    }
    
    /**
     * Validar campo
     */
    function validateField(field) {
        const value = field.value.trim();
        const isValid = field.checkValidity();
        
        // Remover classes anteriores
        field.classList.remove('is-valid', 'is-invalid');
        
        // Adicionar classe apropriada
        if (value && isValid) {
            field.classList.add('is-valid');
        } else if (value && !isValid) {
            field.classList.add('is-invalid');
        }
    }
    
    /**
     * Mostrar alerta
     */
    function showAlert(title, message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible`;
        alert.innerHTML = `
            <div class="alert-content">
                <div class="alert-icon">
                    <i class="fas fa-${getAlertIcon(type)}"></i>
                </div>
                <div class="alert-text">
                    <strong>${title}</strong>
                    <p>${message}</p>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Adicionar ao container de alertas ou ao body
        const alertContainer = document.querySelector('.alert-container') || document.body;
        alertContainer.appendChild(alert);
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (alert.parentElement) {
                alert.remove();
            }
        }, 5000);
    }
    
    /**
     * Obter ícone para tipo de alerta
     */
    function getAlertIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
    
    /**
     * Utilitários
     */
    window.AdminUtils = {
        showAlert,
        showModal,
        hideModal,
        showTooltip,
        hideTooltip
    };
    
    // Adicionar estilos dinâmicos
    addDynamicStyles();
    
    /**
     * Adicionar estilos CSS dinâmicos
     */
    function addDynamicStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .search-results {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                max-height: 300px;
                overflow-y: auto;
                display: none;
            }
            
            .search-result-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 15px;
                color: #4a5568;
                text-decoration: none;
                border-bottom: 1px solid #f7fafc;
                transition: background 0.2s;
            }
            
            .search-result-item:hover {
                background: #f7fafc;
            }
            
            .search-result-item i {
                width: 16px;
                text-align: center;
                color: #667eea;
            }
            
            .search-result-item span {
                flex: 1;
                font-weight: 500;
            }
            
            .search-result-item small {
                color: #a0aec0;
                font-size: 11px;
                text-transform: uppercase;
                font-weight: 600;
            }
            
            .search-no-results {
                padding: 20px;
                text-align: center;
                color: #a0aec0;
                font-size: 14px;
            }
            
            .custom-tooltip {
                position: absolute;
                background: #2d3748;
                color: white;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 500;
                z-index: 10000;
                opacity: 0;
                transform: translateY(5px);
                transition: all 0.2s ease;
                pointer-events: none;
            }
            
            .custom-tooltip::after {
                content: '';
                position: absolute;
                top: 100%;
                left: 50%;
                transform: translateX(-50%);
                border: 4px solid transparent;
                border-top-color: #2d3748;
            }
            
            .custom-tooltip.show {
                opacity: 1;
                transform: translateY(0);
            }
            
            .alert {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 300px;
                max-width: 500px;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                animation: slideInRight 0.3s ease;
            }
            
            .alert-success {
                background: #f0fff4;
                border: 1px solid #c6f6d5;
                color: #22543d;
            }
            
            .alert-error {
                background: #fed7d7;
                border: 1px solid #feb2b2;
                color: #c53030;
            }
            
            .alert-warning {
                background: #fefcbf;
                border: 1px solid #f6e05e;
                color: #744210;
            }
            
            .alert-info {
                background: #ebf8ff;
                border: 1px solid #90cdf4;
                color: #2c5282;
            }
            
            .alert-content {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 15px;
            }
            
            .alert-icon {
                font-size: 18px;
                margin-top: 2px;
            }
            
            .alert-text {
                flex: 1;
            }
            
            .alert-text strong {
                display: block;
                font-weight: 600;
                margin-bottom: 4px;
            }
            
            .alert-text p {
                margin: 0;
                font-size: 14px;
                opacity: 0.9;
            }
            
            .alert-close {
                background: none;
                border: none;
                color: inherit;
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                opacity: 0.7;
                transition: opacity 0.2s;
            }
            
            .alert-close:hover {
                opacity: 1;
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .is-valid {
                border-color: #48bb78 !important;
                box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.1) !important;
            }
            
            .is-invalid {
                border-color: #e53e3e !important;
                box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1) !important;
            }
            
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .modal.show {
                opacity: 1;
                visibility: visible;
            }
            
            .modal-content {
                background: white;
                border-radius: 12px;
                max-width: 90vw;
                max-height: 90vh;
                overflow-y: auto;
                transform: scale(0.9);
                transition: transform 0.3s ease;
            }
            
            .modal.show .modal-content {
                transform: scale(1);
            }
            
            body.modal-open {
                overflow: hidden;
            }
        `;
        
        document.head.appendChild(style);
    }
});

/**
 * Função para confirmar ações
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * Função para deletar item
 */
function deleteItem(url, itemName) {
    const message = `Tem certeza que deseja deletar "${itemName}"? Esta ação não pode ser desfeita.`;
    
    if (confirm(message)) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                AdminUtils.showAlert('Sucesso!', data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                AdminUtils.showAlert('Erro!', data.message, 'error');
            }
        })
        .catch(error => {
            AdminUtils.showAlert('Erro!', 'Ocorreu um erro inesperado.', 'error');
            console.error('Error:', error);
        });
    }
}
// ===== BUSCA GLOBAL =====
document.addEventListener('DOMContentLoaded', function() {
    const globalSearch = document.getElementById('globalSearch');
    
    if (globalSearch) {
        let searchTimeout;
        
        globalSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                hideSearchResults();
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        // Fechar resultados ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-box')) {
                hideSearchResults();
            }
        });
    }
});

function performSearch(query) {
    fetch(`/admin/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSearchResults(data.results);
            }
        })
        .catch(error => {
            console.error('Erro na busca:', error);
        });
}

function showSearchResults(results) {
    let searchResults = document.getElementById('searchResults');
    
    if (!searchResults) {
        searchResults = document.createElement('div');
        searchResults.id = 'searchResults';
        searchResults.className = 'search-results';
        document.querySelector('.search-box').appendChild(searchResults);
    }
    
    if (results.length === 0) {
        searchResults.innerHTML = '<div class="search-no-results">Nenhum resultado encontrado</div>';
    } else {
        searchResults.innerHTML = results.map(result => `
            <a href="/admin/${result.url}" class="search-result-item">
                <i class="${result.icon}"></i>
                <div class="search-result-content">
                    <div class="search-result-title">${result.title}</div>
                    <div class="search-result-subtitle">${result.subtitle}</div>
                </div>
            </a>
        `).join('');
    }
    
    searchResults.style.display = 'block';
}

function hideSearchResults() {
    const searchResults = document.getElementById('searchResults');
    if (searchResults) {
        searchResults.style.display = 'none';
    }
}
