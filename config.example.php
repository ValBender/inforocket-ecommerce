<?php
/**
 * Arquivo de Configuração - InfoRocket
 * 
 * INSTRUÇÕES:
 * 1. Copie este arquivo para 'config.php'
 * 2. Preencha as configurações abaixo
 * 3. Nunca commite o arquivo 'config.php' no Git
 */

// ================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// ================================

// Configurações de conexão MySQL
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
define('DB_NAME', '24198_Loja');

// ================================
// CONFIGURAÇÕES DE EMAIL
// ================================

// Configurações SMTP para envio de emails
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'seu_email@gmail.com');
define('SMTP_PASSWORD', 'sua_senha_app');
define('SMTP_FROM_EMAIL', 'noreply@inforocket.pt');
define('SMTP_FROM_NAME', 'InfoRocket');

// ================================
// CONFIGURAÇÕES DE SEGURANÇA
// ================================

// Chave secreta para sessões (gere uma chave aleatória)
define('SECRET_KEY', 'sua_chave_secreta_aqui_128_caracteres_minimo');

// Salt para hash de senhas (gere um salt único)
define('PASSWORD_SALT', 'seu_salt_unico_aqui');

// ================================
// CONFIGURAÇÕES DA APLICAÇÃO
// ================================

// URL base da aplicação
define('BASE_URL', 'http://localhost:8080');

// Diretório de uploads
define('UPLOAD_DIR', '../uploads/');

// Tamanho máximo de upload (em bytes)
define('MAX_UPLOAD_SIZE', 5242880); // 5MB

// ================================
// CONFIGURAÇÕES DE DESENVOLVIMENTO
// ================================

// Modo debug (true para desenvolvimento, false para produção)
define('DEBUG_MODE', true);

// Exibir erros PHP (true para desenvolvimento, false para produção)
define('DISPLAY_ERRORS', true);

// Log de erros
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', '../logs/error.log');

// ================================
// CONFIGURAÇÕES DE PAGAMENTO
// ================================

// Para integração futura com sistemas de pagamento
// Remova os comentários e configure quando necessário

/*
// PayPal (Sandbox para testes)
define('PAYPAL_CLIENT_ID', 'seu_client_id_paypal_sandbox');
define('PAYPAL_CLIENT_SECRET', 'seu_client_secret_paypal_sandbox');
define('PAYPAL_MODE', 'sandbox'); // 'sandbox' ou 'live'

// Stripe (para testes)
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_sua_chave_publica');
define('STRIPE_SECRET_KEY', 'sk_test_sua_chave_secreta');
*/

// ================================
// CONFIGURAÇÕES DE CACHE
// ================================

// Cache de sessões
define('SESSION_LIFETIME', 3600); // 1 hora em segundos

// Cache de produtos
define('PRODUCT_CACHE_TIME', 300); // 5 minutos em segundos

?>

