-- Banco de dados para o site Só Borracha Ltda
-- Criado para gerenciar fornecedores, produtos e administração do site

CREATE DATABASE IF NOT EXISTS soborracha_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE soborracha_db;

-- Tabela de usuários administradores
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de fornecedores
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    cnpj VARCHAR(18) NULL,
    contact_person VARCHAR(100) NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    whatsapp VARCHAR(20) NULL,
    website VARCHAR(200) NULL,
    address TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(2) NULL,
    zip_code VARCHAR(10) NULL,
    description TEXT NULL,
    logo_image VARCHAR(255) NULL,
    specialties TEXT NULL, -- JSON com especialidades
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de categorias de produtos
CREATE TABLE IF NOT EXISTS product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NULL,
    short_description VARCHAR(500) NULL,
    category_id INT NULL,
    supplier_id INT NULL,
    sku VARCHAR(50) NULL,
    price DECIMAL(10,2) NULL,
    featured_image VARCHAR(255) NULL,
    gallery_images TEXT NULL, -- JSON com array de imagens
    specifications TEXT NULL, -- JSON com especificações
    compatibility TEXT NULL, -- JSON com compatibilidade de veículos
    tags VARCHAR(500) NULL,
    meta_title VARCHAR(200) NULL,
    meta_description VARCHAR(300) NULL,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);

-- Tabela de configurações do site
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_type ENUM('text', 'textarea', 'image', 'json', 'boolean') DEFAULT 'text',
    description VARCHAR(255) NULL,
    group_name VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de imagens do site (para galeria de imagens)
CREATE TABLE IF NOT EXISTS site_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NULL,
    caption VARCHAR(500) NULL,
    file_size INT NULL,
    mime_type VARCHAR(100) NULL,
    dimensions VARCHAR(20) NULL, -- formato: "1920x1080"
    category VARCHAR(50) DEFAULT 'general',
    uploaded_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Tabela de logs de atividades do admin
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50) NULL,
    record_id INT NULL,
    old_values TEXT NULL, -- JSON
    new_values TEXT NULL, -- JSON
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Inserir dados iniciais

-- Usuário administrador padrão (senha: admin123)
INSERT INTO admin_users (username, email, password, full_name, role) VALUES 
('admin', 'admin@soborracha.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin');

-- Categorias de produtos iniciais
INSERT INTO product_categories (name, slug, description) VALUES 
('Borrachas de Porta', 'borrachas-porta', 'Vedações para portas de veículos'),
('Borrachas de Parabrisa', 'borrachas-parabrisa', 'Vedações para para-brisas'),
('Borrachas de Vidro Lateral', 'borrachas-vidro-lateral', 'Vedações para vidros laterais'),
('Perfis Especiais', 'perfis-especiais', 'Perfis de borracha para aplicações específicas'),
('Correias Automotivas', 'correias-automotivas', 'Correias para motores e sistemas automotivos');

-- Configurações iniciais do site
INSERT INTO site_settings (setting_key, setting_value, setting_type, description, group_name) VALUES 
('site_title', 'Só Borracha Ltda - Borrachas Automotivas Multi Marcas', 'text', 'Título do site', 'general'),
('site_description', 'Especialistas em borrachas automotivas para todas as marcas. Varejo e atacado em Campo Grande - MS.', 'textarea', 'Descrição do site', 'general'),
('company_phone', '(67) 99918-0553', 'text', 'Telefone da empresa', 'contact'),
('company_email', 'ronaldo@soborracha.com.br', 'text', 'Email da empresa', 'contact'),
('company_address', 'Campo Grande - MS', 'text', 'Endereço da empresa', 'contact'),
('working_hours', 'Seg-Sex: 7:30 às 17:30 e Sábado: 8:00 às 12:00', 'text', 'Horário de funcionamento', 'contact'),
('whatsapp_number', '5567999180553', 'text', 'Número do WhatsApp', 'contact'),
('hero_title', 'Borrachas Automotivas Multi Marcas', 'text', 'Título principal da página inicial', 'homepage'),
('hero_subtitle', 'Especialistas em vedações automotivas há mais de 25 anos', 'text', 'Subtítulo da página inicial', 'homepage'),
('about_text', 'A Só Borracha Ltda é referência em borrachas automotivas em Campo Grande - MS.', 'textarea', 'Texto sobre a empresa', 'about');

-- Fornecedores de exemplo
INSERT INTO suppliers (name, company_name, contact_person, email, phone, city, state, description, specialties, featured) VALUES 
('Borrachas Continental', 'Continental Borrachas Ltda', 'João Silva', 'contato@continental.com.br', '(11) 3456-7890', 'São Paulo', 'SP', 'Fornecedor especializado em borrachas de alta qualidade para o mercado automotivo.', '["Borrachas de Porta", "Perfis Especiais"]', TRUE),
('AutoVed Vedações', 'AutoVed Indústria e Comércio', 'Maria Santos', 'vendas@autoved.com.br', '(21) 2345-6789', 'Rio de Janeiro', 'RJ', 'Indústria especializada em vedações automotivas e perfis de borracha.', '["Borrachas de Parabrisa", "Vedações Especiais"]', TRUE),
('Flex Borrachas', 'Flex Borrachas e Vedações', 'Carlos Oliveira', 'comercial@flexborrachas.com.br', '(31) 3456-7890', 'Belo Horizonte', 'MG', 'Fabricante de correias e borrachas técnicas para o setor automotivo.', '["Correias Automotivas", "Borrachas Técnicas"]', FALSE);
