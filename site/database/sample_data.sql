-- Dados de exemplo para teste do sistema
-- Execute este arquivo após importar database.sql

USE soborracha_db;

-- Inserir mais fornecedores de exemplo
INSERT INTO suppliers (name, company_name, contact_person, email, phone, whatsapp, website, address, city, state, zip_code, description, specialties, status, featured) VALUES 

('Borrachas Premium', 'Premium Vedações Ltda', 'Ana Costa', 'contato@premiumbor.com.br', '(11) 3456-7890', '5511987654321', 'https://premiumbor.com.br', 'Rua das Indústrias, 123', 'São Paulo', 'SP', '01234-567', 'Especializada em borrachas de alta performance para veículos importados e nacionais. Mais de 15 anos no mercado automotivo.', '["Borrachas de Porta", "Borrachas de Parabrisa", "Perfis Especiais"]', 'active', 1),

('AutoSeal Vedações', 'AutoSeal Indústria e Comércio', 'Roberto Silva', 'vendas@autoseal.com.br', '(21) 2345-6789', '5521987654321', 'https://autoseal.com.br', 'Av. Industrial, 456', 'Rio de Janeiro', 'RJ', '20123-456', 'Fornecedor especializado em vedações automotivas com tecnologia alemã. Produtos certificados ISO 9001.', '["Vedações Especiais", "Borrachas Técnicas", "Perfis Customizados"]', 'active', 1),

('FlexBor Componentes', 'FlexBor Componentes Automotivos', 'Mariana Santos', 'comercial@flexbor.com.br', '(31) 3456-7890', '5531987654321', 'https://flexbor.com.br', 'Distrito Industrial, 789', 'Belo Horizonte', 'MG', '30123-456', 'Fabricante de componentes de borracha para a indústria automotiva. Especialista em correias e mangueiras.', '["Correias Automotivas", "Mangueiras", "Borrachas Técnicas"]', 'active', 0),

('VedaMax Soluções', 'VedaMax Soluções em Borracha', 'Carlos Oliveira', 'atendimento@vedamax.com.br', '(41) 3456-7890', '5541987654321', 'https://vedamax.com.br', 'Zona Industrial, 321', 'Curitiba', 'PR', '80123-456', 'Soluções completas em vedação automotiva. Atendemos desde pequenas oficinas até grandes montadoras.', '["Borrachas de Porta", "Borrachas de Vidro", "Kits de Vedação"]', 'active', 1),

('TechSeal Brasil', 'TechSeal Brasil Ltda', 'Fernanda Lima', 'info@techseal.com.br', '(51) 3456-7890', '5551987654321', 'https://techseal.com.br', 'Parque Tecnológico, 654', 'Porto Alegre', 'RS', '90123-456', 'Tecnologia avançada em vedações automotivas. Parceira oficial de montadoras europeias no Brasil.', '["Tecnologia Avançada", "Borrachas Importadas", "Consultoria Técnica"]', 'active', 0),

('BorrachaCar MS', 'BorrachaCar Mato Grosso do Sul', 'José Pereira', 'contato@borrachacar.ms.gov.br', '(67) 3456-7890', '5567987654321', '', 'Rua do Comércio, 987', 'Campo Grande', 'MS', '79123-456', 'Fornecedor local especializado em borrachas para o mercado regional. Atendimento personalizado e entrega rápida.', '["Atendimento Local", "Entrega Rápida", "Borrachas Nacionais"]', 'active', 0),

('ElasticPro Vedações', 'ElasticPro Vedações Industriais', 'Patricia Rocha', 'vendas@elasticpro.com.br', '(62) 3456-7890', '5562987654321', 'https://elasticpro.com.br', 'Setor Industrial, 147', 'Goiânia', 'GO', '74123-456', 'Especialista em vedações para veículos pesados e máquinas agrícolas. Produtos de alta durabilidade.', '["Veículos Pesados", "Máquinas Agrícolas", "Alta Durabilidade"]', 'active', 1),

('RubberTech Solutions', 'RubberTech Solutions Ltda', 'Miguel Torres', 'contato@rubbertech.com.br', '(85) 3456-7890', '5585987654321', 'https://rubbertech.com.br', 'Complexo Industrial, 258', 'Fortaleza', 'CE', '60123-456', 'Soluções inovadoras em borracha técnica. Desenvolvimento de produtos customizados para aplicações específicas.', '["Produtos Customizados", "Borracha Técnica", "Inovação"]', 'active', 0);

-- Inserir algumas categorias de produtos
INSERT INTO product_categories (name, slug, description, sort_order) VALUES 
('Kits de Vedação', 'kits-vedacao', 'Kits completos de vedação para diversos modelos', 6),
('Borrachas Universais', 'borrachas-universais', 'Borrachas que se adaptam a múltiplos modelos', 7),
('Acessórios', 'acessorios', 'Acessórios complementares para instalação', 8);

-- Inserir alguns produtos de exemplo
INSERT INTO products (name, slug, description, short_description, category_id, supplier_id, sku, featured_image, specifications, compatibility, tags, status, featured, sort_order) VALUES 

('Kit Borracha Porta Completo Universal', 'kit-borracha-porta-universal', 'Kit completo com borrachas para 4 portas, incluindo vedação superior e inferior. Material de alta qualidade com garantia de 2 anos.', 'Kit completo para vedação de 4 portas - Universal', 1, 1, 'KIT-PORTA-001', 'kit-porta-universal.jpg', '{"material": "EPDM", "cor": "Preto", "dureza": "65 Shore A", "temperatura": "-40°C a +120°C"}', '["Chevrolet Onix", "Ford Ka", "Volkswagen Gol", "Fiat Uno"]', 'kit, porta, universal, vedação', 'active', 1, 1),

('Borracha Parabrisa Fiat Uno', 'borracha-parabrisa-fiat-uno', 'Borracha específica para parabrisa do Fiat Uno, modelos 2010 em diante. Encaixe perfeito e instalação facilitada.', 'Borracha parabrisa Fiat Uno 2010+', 2, 2, 'PAR-UNO-001', 'parabrisa-uno.jpg', '{"material": "Borracha Natural", "cor": "Preto", "espessura": "3mm"}', '["Fiat Uno 2010+", "Fiat Uno Way", "Fiat Uno Attractive"]', 'fiat, uno, parabrisa, específico', 'active', 1, 2),

('Perfil Borracha Porta Malas Universal', 'perfil-porta-malas-universal', 'Perfil de borracha para vedação de porta malas. Vendido por metro linear, corte sob medida.', 'Perfil porta malas - venda por metro', 4, 3, 'PERF-PM-001', 'perfil-porta-malas.jpg', '{"material": "EPDM", "largura": "15mm", "altura": "8mm"}', '["Universal", "Corte sob medida"]', 'perfil, porta malas, universal, metro', 'active', 0, 3),

('Correia Dentada Chevrolet Onix', 'correia-dentada-onix', 'Correia dentada original para Chevrolet Onix motor 1.0 e 1.4. Produto com garantia de fábrica.', 'Correia dentada Onix 1.0/1.4', 5, 4, 'COR-ONX-001', 'correia-onix.jpg', '{"dentes": "137", "largura": "25mm", "material": "Borracha reforçada"}', '["Chevrolet Onix 1.0", "Chevrolet Onix 1.4", "Chevrolet Prisma"]', 'correia, onix, dentada, motor', 'active', 1, 4);

-- Atualizar configurações do site
UPDATE site_settings SET setting_value = 'Conheça nossos fornecedores parceiros e a qualidade dos produtos que oferecemos.' WHERE setting_key = 'hero_subtitle';

-- Inserir configuração específica para fornecedores
INSERT INTO site_settings (setting_key, setting_value, setting_type, description, group_name) VALUES 
('suppliers_intro_title', 'Parceiros de Qualidade', 'text', 'Título da seção de introdução dos fornecedores', 'suppliers'),
('suppliers_intro_text', 'Trabalhamos com os melhores fornecedores do mercado automotivo para garantir produtos de qualidade superior aos nossos clientes.', 'textarea', 'Texto de introdução dos fornecedores', 'suppliers'),
('suppliers_show_contact', '1', 'boolean', 'Mostrar informações de contato dos fornecedores', 'suppliers'),
('suppliers_per_page', '12', 'text', 'Número de fornecedores por página', 'suppliers');

-- Inserir alguns logs de exemplo (opcional)
INSERT INTO admin_logs (user_id, action, table_name, record_id, new_values, ip_address, user_agent) VALUES 
(1, 'create_supplier', 'suppliers', 4, '{"name": "VedaMax Soluções"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(1, 'create_product', 'products', 1, '{"name": "Kit Borracha Porta Completo Universal"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(1, 'update_settings', 'site_settings', 1, '{"setting_key": "hero_subtitle"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

-- Verificar dados inseridos
SELECT 'Fornecedores cadastrados:' as info, COUNT(*) as total FROM suppliers;
SELECT 'Produtos cadastrados:' as info, COUNT(*) as total FROM products;
SELECT 'Categorias cadastradas:' as info, COUNT(*) as total FROM product_categories;
SELECT 'Configurações:' as info, COUNT(*) as total FROM site_settings;
