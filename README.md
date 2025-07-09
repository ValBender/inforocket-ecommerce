# 🚀 InfoRocket - E-commerce de Tecnologia

> **Plataforma de e-commerce moderna desenvolvida em PHP e MySQL com interface responsiva usando Bootstrap 5 e sistema completo de gestão de produtos.**

![InfoRocket](https://img.shields.io/badge/InfoRocket-E--commerce-blue?style=for-the-badge&logo=rocket)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

## 📋 Sobre o Projeto

InfoRocket é uma plataforma de e-commerce especializada em produtos de tecnologia, desenvolvida com foco na experiência do usuário e funcionalidades modernas. O sistema oferece uma interface intuitiva tanto para clientes quanto para administradores.

### ✨ Funcionalidades Principais

#### 🛍️ **Para Clientes**
- **Catálogo de Produtos** - Navegação intuitiva com 8 produtos de tecnologia
- **Sistema de Busca** - Filtros avançados por nome e descrição
- **Carrinho de Compras** - Adicionar, remover e atualizar quantidades
- **Finalização de Pedidos** - Processo simplificado de checkout
- **Autenticação Segura** - Sistema de login e registro

#### 🔐 **Para Administradores**
- **Gestão de Produtos** - CRUD completo (Criar, Ler, Atualizar, Deletar)
- **Interface Dupla** - Versão básica e premium
- **Dashboard Avançado** - Estatísticas em tempo real
- **Gestão de Usuários** - Controle de acesso e permissões

## 🛠️ Tecnologias Utilizadas

### **Frontend**
- **HTML5** - Estrutura semântica moderna
- **CSS3** - Estilização avançada com gradientes e animações
- **Bootstrap 5** - Framework responsivo
- **JavaScript** - Interatividade e validações
- **Font Awesome** - Ícones profissionais

### **Backend**
- **PHP 8** - Linguagem de programação server-side
- **MySQL** - Sistema de gerenciamento de banco de dados
- **PDO/MySQLi** - Conexão segura com prepared statements

### **Segurança**
- **Prepared Statements** - Proteção contra SQL Injection
- **Sanitização de Dados** - Validação de inputs
- **Controle de Sessões** - Autenticação segura
- **Hash de Senhas** - Criptografia bcrypt

## 📁 Estrutura do Projeto

```
InfoRocket/
├── 📁 api/
│   ├── auth.php              # Sistema de autenticação
│   ├── db.php                # Conexão com banco de dados
│   ├── produtos.php          # CRUD de produtos
│   ├── add_to_cart.php       # Adicionar ao carrinho
│   ├── update_cart.php       # Atualizar carrinho
│   └── delete_cart.php       # Remover do carrinho
├── 📁 views/
│   ├── login.php             # Página de login
│   ├── registo.php           # Página de registro
│   ├── cart.php              # Carrinho de compras
│   ├── finish.php            # Finalização de pedido
│   ├── areaadmin.php         # Administração básica
│   └── areaadmin_premium.php # Administração premium
├── 📁 assets/
│   └── 📁 images/            # Imagens dos produtos
├── index.php                 # Página principal
├── 24198_Loja.sql           # Estrutura do banco
├── produtos_table.sql        # Tabela de produtos
├── carrinho_table.sql        # Tabela do carrinho
└── README.md                 # Documentação
```

## 🚀 Como Executar

### **Pré-requisitos**
- PHP 8.0 ou superior
- MySQL 8.0 ou superior
- Servidor web (Apache/Nginx) ou XAMPP
- Extensões PHP: mysqli, pdo_mysql

### **Instalação**

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/inforocket.git
   cd inforocket
   ```

2. **Configure o banco de dados**
   ```bash
   # Crie o banco de dados
   mysql -u root -p -e "CREATE DATABASE 24198_Loja;"
   
   # Importe as estruturas
   mysql -u root -p 24198_Loja < 24198_Loja.sql
   mysql -u root -p 24198_Loja < produtos_table.sql
   mysql -u root -p 24198_Loja < carrinho_table.sql
   ```

3. **Configure a conexão**
   ```php
   // Edite api/db.php com suas credenciais
   $servername = "localhost";
   $username = "seu_usuario";
   $password = "sua_senha";
   $dbname = "24198_Loja";
   ```

4. **Inicie o servidor**
   ```bash
   # Com PHP built-in server
   php -S localhost:8080
   
   # Ou configure no Apache/Nginx
   ```

5. **Acesse a aplicação**
   ```
   http://localhost:8080
   ```

## 👤 Credenciais de Teste

### **Administrador**
- **Usuário:** adm
- **Email:** teste@teste.com
- **Senha:** password

## 📊 Funcionalidades Detalhadas

### **Sistema de Produtos**
- ✅ Catálogo com 8 produtos de tecnologia
- ✅ Imagens reais em alta qualidade
- ✅ Preços competitivos e descrições detalhadas
- ✅ Sistema de busca em tempo real

### **Carrinho de Compras**
- ✅ Adicionar produtos com um clique
- ✅ Atualizar quantidades dinamicamente
- ✅ Remover produtos com confirmação
- ✅ Cálculo automático de totais
- ✅ Busca dentro do carrinho

### **Administração**
- ✅ **Versão Básica:** Interface simples e funcional
- ✅ **Versão Premium:** Dashboard com estatísticas
- ✅ CRUD completo de produtos
- ✅ Upload de imagens
- ✅ Gestão de estoque

### **Design Responsivo**
- ✅ Interface adaptável (Desktop, Tablet, Mobile)
- ✅ Gradientes modernos e animações suaves
- ✅ Tipografia profissional (Inter Font)
- ✅ Ícones Font Awesome

## 🎨 Screenshots

### **Homepage**
Interface moderna com hero section e catálogo de produtos

### **Carrinho de Compras**
Sistema interativo com cálculos em tempo real

### **Administração Premium**
Dashboard avançado com estatísticas e gestão completa

## 🔧 Configurações Avançadas

### **Banco de Dados**
```sql
-- Estrutura principal
CREATE TABLE Utilizador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    RoleID INT DEFAULT 2
);

CREATE TABLE Produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT,
    preco DECIMAL(10,2),
    imagem LONGBLOB
);

CREATE TABLE Carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT,
    produto_id INT,
    quantidade INT DEFAULT 1,
    FOREIGN KEY (utilizador_id) REFERENCES Utilizador(id),
    FOREIGN KEY (produto_id) REFERENCES Produto(id)
);
```

### **Segurança**
- Prepared statements em todas as queries
- Validação de dados no frontend e backend
- Controle de sessões com timeout
- Hash bcrypt para senhas

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Contato

**InfoRocket Team**
- 📧 Email: contato@inforocket.pt
- 🌐 Website: [www.inforocket.pt](https://www.inforocket.pt)
- 📍 Localização: Lisboa, Portugal

---

<div align="center">

**Desenvolvido com ❤️ para a comunidade de tecnologia**

[![GitHub](https://img.shields.io/badge/GitHub-InfoRocket-black?style=for-the-badge&logo=github)](https://github.com/seu-usuario/inforocket)

</div>

