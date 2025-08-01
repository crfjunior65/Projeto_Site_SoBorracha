// ===== PRODUCTS PAGE JAVASCRIPT =====

document.addEventListener('DOMContentLoaded', function() {
    initProductFilters();
    initProductSearch();
    initLoadMore();
    initProductModal();
});

// ===== PRODUCT FILTERS =====
function initProductFilters() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const productItems = document.querySelectorAll('.product-item');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Filter products
            filterProducts(category, productItems);
        });
    });
}

function filterProducts(category, productItems) {
    productItems.forEach(item => {
        const itemCategory = item.dataset.category;
        
        if (category === 'all' || itemCategory === category) {
            item.classList.remove('hidden');
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 50);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.classList.add('hidden');
            }, 300);
        }
    });
    
    // Update results count
    updateResultsCount();
}

// ===== PRODUCT SEARCH =====
function initProductSearch() {
    const searchInput = document.getElementById('product-search');
    const productItems = document.querySelectorAll('.product-item');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase().trim();
            searchProducts(searchTerm, productItems);
        }, 300));
    }
}

function searchProducts(searchTerm, productItems) {
    let visibleCount = 0;
    
    productItems.forEach(item => {
        const productCard = item.querySelector('.product-card');
        const title = productCard.querySelector('h3').textContent.toLowerCase();
        const description = productCard.querySelector('p').textContent.toLowerCase();
        const brands = productCard.querySelector('.product-brands small').textContent.toLowerCase();
        
        const isMatch = title.includes(searchTerm) || 
                       description.includes(searchTerm) || 
                       brands.includes(searchTerm);
        
        if (searchTerm === '' || isMatch) {
            item.classList.remove('hidden');
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
            visibleCount++;
        } else {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.classList.add('hidden');
            }, 300);
        }
    });
    
    // Show no results message
    showNoResultsMessage(visibleCount === 0 && searchTerm !== '');
}

function showNoResultsMessage(show) {
    let noResultsMsg = document.getElementById('no-results-message');
    
    if (show && !noResultsMsg) {
        noResultsMsg = document.createElement('div');
        noResultsMsg.id = 'no-results-message';
        noResultsMsg.className = 'no-results-message';
        noResultsMsg.innerHTML = `
            <div class="no-results-content">
                <i class="fas fa-search"></i>
                <h3>Nenhum produto encontrado</h3>
                <p>Tente buscar por outros termos ou entre em contato conosco.</p>
                <button class="btn btn-primary" onclick="sendWhatsAppMessage('Não encontrei o produto que procuro. Podem me ajudar?')">
                    <i class="fab fa-whatsapp"></i>
                    Falar com Especialista
                </button>
            </div>
        `;
        
        const productsGrid = document.getElementById('products-grid');
        productsGrid.parentNode.insertBefore(noResultsMsg, productsGrid.nextSibling);
    } else if (!show && noResultsMsg) {
        noResultsMsg.remove();
    }
}

// ===== LOAD MORE FUNCTIONALITY =====
function initLoadMore() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreProducts();
        });
    }
}

function loadMoreProducts() {
    const productsGrid = document.getElementById('products-grid');
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    // Show loading state
    loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
    loadMoreBtn.disabled = true;
    
    // Simulate loading more products
    setTimeout(() => {
        const newProducts = generateMoreProducts();
        productsGrid.insertAdjacentHTML('beforeend', newProducts);
        
        // Reset button
        loadMoreBtn.innerHTML = '<i class="fas fa-plus"></i> Carregar Mais Produtos';
        loadMoreBtn.disabled = false;
        
        // Initialize new product cards
        initNewProductCards();
        
        // Show notification
        showNotification('Mais produtos carregados!', 'success');
    }, 1500);
}

function generateMoreProducts() {
    const additionalProducts = [
        {
            category: 'porta',
            image: 'images/borracha-porta-corsa.jpg',
            title: 'Borracha de Porta Corsa',
            description: 'Específica para Chevrolet Corsa Classic e Sedan',
            features: ['Original', 'Garantia', 'Fácil Instalação'],
            brands: 'Chevrolet Corsa 1994-2012'
        },
        {
            category: 'parabrisa',
            image: 'images/borracha-parabrisa-uno.jpg',
            title: 'Borracha de Parabrisa Uno',
            description: 'Para Fiat Uno Mille, Way e Vivace',
            features: ['Multi Modelos', 'Resistente', 'Instalação Inclusa'],
            brands: 'Fiat Uno 1984-2013'
        }
    ];
    
    return additionalProducts.map(product => `
        <div class="product-item" data-category="${product.category}">
            <div class="product-card">
                <div class="product-image">
                    <img src="${product.image}" alt="${product.title}" loading="lazy">
                    <div class="product-overlay">
                        <button class="btn btn-small" onclick="openProductModal('${product.title.toLowerCase().replace(/\s+/g, '-')}')">
                            <i class="fas fa-eye"></i>
                            Ver Detalhes
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>${product.title}</h3>
                    <p>${product.description}</p>
                    <div class="product-features">
                        ${product.features.map(feature => `<span class="feature-tag">${feature}</span>`).join('')}
                    </div>
                    <div class="product-brands">
                        <small>Compatível com: ${product.brands}</small>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre ${product.title}')">
                            <i class="fab fa-whatsapp"></i>
                            Consultar Preço
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function initNewProductCards() {
    // Re-initialize any functionality for new product cards
    const newCards = document.querySelectorAll('.product-card:not(.initialized)');
    
    newCards.forEach(card => {
        card.classList.add('initialized');
        
        // Add hover effects or other interactions
        const overlay = card.querySelector('.product-overlay');
        if (overlay) {
            card.addEventListener('mouseenter', () => {
                overlay.style.opacity = '1';
            });
            
            card.addEventListener('mouseleave', () => {
                overlay.style.opacity = '0';
            });
        }
    });
}

// ===== PRODUCT MODAL =====
function initProductModal() {
    const modal = document.getElementById('product-modal');
    
    if (modal) {
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeProductModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeProductModal();
            }
        });
    }
}

function openProductModal(productId) {
    const modal = document.getElementById('product-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    
    // Get product data (in a real app, this would come from a database)
    const productData = getProductData(productId);
    
    if (productData) {
        modalTitle.textContent = productData.title;
        modalBody.innerHTML = generateModalContent(productData);
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeProductModal() {
    const modal = document.getElementById('product-modal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function getProductData(productId) {
    // Mock product data - in a real app, this would come from an API
    const products = {
        'porta-universal': {
            title: 'Borracha de Porta Universal',
            image: 'images/borracha-porta-universal.jpg',
            description: 'Borracha de porta universal de alta qualidade, compatível com diversos modelos de veículos nacionais e importados.',
            features: ['Material de alta durabilidade', 'Resistente a intempéries', 'Fácil instalação', 'Vedação perfeita'],
            specifications: {
                'Material': 'Borracha EPDM',
                'Cor': 'Preta',
                'Comprimento': 'Variável conforme modelo',
                'Garantia': '12 meses'
            },
            compatibility: ['Volkswagen Gol', 'Fiat Uno', 'Chevrolet Corsa', 'Ford Ka', 'Toyota Corolla'],
            installation: 'Instalação profissional inclusa no serviço. Nossa equipe especializada garante a vedação perfeita.'
        },
        'parabrisa-gol': {
            title: 'Borracha de Parabrisa Gol',
            image: 'images/borracha-parabrisa-gol.jpg',
            description: 'Borracha específica para parabrisa do Volkswagen Gol, garantindo vedação perfeita e segurança.',
            features: ['Específica para VW Gol', 'Material original', 'Instalação profissional', 'Garantia de vedação'],
            specifications: {
                'Material': 'Borracha EPDM Premium',
                'Cor': 'Preta',
                'Modelo': 'VW Gol G2, G3, G4, G5',
                'Garantia': '24 meses'
            },
            compatibility: ['VW Gol 1995-2012'],
            installation: 'Instalação realizada por profissionais especializados com garantia de serviço.'
        }
    };
    
    return products[productId] || null;
}

function generateModalContent(product) {
    return `
        <div class="modal-product">
            <div class="modal-product-image">
                <img src="${product.image}" alt="${product.title}" loading="lazy">
            </div>
            <div class="modal-product-info">
                <p class="product-description">${product.description}</p>
                
                <div class="product-section">
                    <h4>Características</h4>
                    <ul class="features-list">
                        ${product.features.map(feature => `<li><i class="fas fa-check"></i> ${feature}</li>`).join('')}
                    </ul>
                </div>
                
                <div class="product-section">
                    <h4>Especificações Técnicas</h4>
                    <div class="specifications-grid">
                        ${Object.entries(product.specifications).map(([key, value]) => `
                            <div class="spec-item">
                                <strong>${key}:</strong>
                                <span>${value}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="product-section">
                    <h4>Compatibilidade</h4>
                    <div class="compatibility-tags">
                        ${product.compatibility.map(model => `<span class="compatibility-tag">${model}</span>`).join('')}
                    </div>
                </div>
                
                <div class="product-section">
                    <h4>Instalação</h4>
                    <p class="installation-info">${product.installation}</p>
                </div>
                
                <div class="modal-actions">
                    <button class="btn btn-primary" onclick="sendWhatsAppMessage('Gostaria de saber mais sobre ${product.title}')">
                        <i class="fab fa-whatsapp"></i>
                        Consultar Preço
                    </button>
                    <button class="btn btn-outline" onclick="closeProductModal()">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    `;
}

// ===== UTILITY FUNCTIONS =====
function updateResultsCount() {
    const visibleProducts = document.querySelectorAll('.product-item:not(.hidden)').length;
    const totalProducts = document.querySelectorAll('.product-item').length;
    
    let countElement = document.getElementById('results-count');
    if (!countElement) {
        countElement = document.createElement('div');
        countElement.id = 'results-count';
        countElement.className = 'results-count';
        
        const filterSection = document.querySelector('.products-filter .container');
        filterSection.appendChild(countElement);
    }
    
    countElement.textContent = `Mostrando ${visibleProducts} de ${totalProducts} produtos`;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== MODAL STYLES =====
const modalStyles = document.createElement('style');
modalStyles.textContent = `
    .modal-product {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-6);
        align-items: start;
    }
    
    .modal-product-image img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: var(--radius-lg);
    }
    
    .product-description {
        font-size: var(--font-size-base);
        color: var(--gray-600);
        line-height: 1.6;
        margin-bottom: var(--spacing-6);
    }
    
    .product-section {
        margin-bottom: var(--spacing-6);
    }
    
    .product-section h4 {
        font-size: var(--font-size-lg);
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: var(--spacing-3);
    }
    
    .features-list {
        list-style: none;
        padding: 0;
    }
    
    .features-list li {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
        margin-bottom: var(--spacing-2);
        color: var(--gray-700);
    }
    
    .features-list i {
        color: var(--primary-color);
        font-size: var(--font-size-sm);
    }
    
    .specifications-grid {
        display: grid;
        gap: var(--spacing-3);
    }
    
    .spec-item {
        display: flex;
        justify-content: space-between;
        padding: var(--spacing-2) 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .spec-item strong {
        color: var(--gray-900);
    }
    
    .spec-item span {
        color: var(--gray-600);
    }
    
    .compatibility-tags {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-2);
    }
    
    .compatibility-tag {
        background: var(--gray-100);
        color: var(--gray-700);
        padding: var(--spacing-1) var(--spacing-3);
        border-radius: var(--radius);
        font-size: var(--font-size-sm);
    }
    
    .installation-info {
        color: var(--gray-600);
        line-height: 1.6;
    }
    
    .modal-actions {
        display: flex;
        gap: var(--spacing-3);
        margin-top: var(--spacing-6);
    }
    
    .no-results-message {
        text-align: center;
        padding: var(--spacing-16) var(--spacing-4);
    }
    
    .no-results-content i {
        font-size: var(--font-size-4xl);
        color: var(--gray-400);
        margin-bottom: var(--spacing-4);
    }
    
    .no-results-content h3 {
        font-size: var(--font-size-xl);
        color: var(--gray-700);
        margin-bottom: var(--spacing-3);
    }
    
    .no-results-content p {
        color: var(--gray-600);
        margin-bottom: var(--spacing-6);
    }
    
    .results-count {
        text-align: center;
        color: var(--gray-600);
        font-size: var(--font-size-sm);
        margin-top: var(--spacing-4);
    }
    
    @media (max-width: 768px) {
        .modal-product {
            grid-template-columns: 1fr;
        }
        
        .modal-actions {
            flex-direction: column;
        }
        
        .modal-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
`;
document.head.appendChild(modalStyles);
