# 🛍️ Vitrine de Produtos - Desafio Técnico Luvinco

Uma aplicação fullstack moderna desenvolvida com PHP 8.1, Laravel 12, Docker e MySQL 8, seguindo princípios SOLID e Clean Code.

## 🎯 Funcionalidades

- **Vitrine de Produtos**: Exibe produtos consumidos da API externa da Luvinco
- **Carrinho de Compras**: Permite adicionar/remover produtos com controle de quantidade
- **Sistema de Pedidos**: Cria pedidos localmente e permite envio para API externa
- **Interface Moderna**: Design responsivo com Tailwind CSS
- **Arquitetura SOLID**: Separação clara de responsabilidades
- **Clean Code**: Código limpo e bem estruturado

## 🚀 Tecnologias Utilizadas

- **Backend**: PHP 8.1, Laravel 12
- **Frontend**: HTML, Tailwind CSS, JavaScript
- **Banco de Dados**: MySQL 8
- **Containerização**: Docker + Docker Compose
- **Arquitetura**: SOLID, Clean Code, Repository Pattern

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🛠️ Instalação e Configuração

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd vitrine-produtos
```

### 2. Configure o ambiente
```bash
# Copie o arquivo de ambiente
cp env.example .env
```

### 3. Inicie os containers
```bash
docker-compose up -d --build
```

### 4. Instale as dependências do Laravel
```bash
docker-compose exec app composer install
```

### 5. Gere a chave da aplicação
```bash
docker-compose exec app php artisan key:generate
```

### 6. Execute as migrations
```bash
docker-compose exec app php artisan migrate
```

### 7. Sincronize os produtos da API
```bash
docker-compose exec app php artisan tinker
```
No tinker, execute:
```php
app(App\Contracts\Services\ProductServiceInterface::class)->syncProducts();
```

## 🌐 Acessando a Aplicação

- **Frontend**: http://localhost:8000
- **API**: http://localhost:8000/api
- **Banco de Dados**: localhost:3307

## 📚 Endpoints da API

### Produtos
- `GET /api/products` - Lista todos os produtos
- `GET /api/products/{id}` - Busca produto específico
- `POST /api/products/sync` - Sincroniza produtos da API externa

### Pedidos
- `GET /api/orders` - Lista todos os pedidos
- `GET /api/orders/{id}` - Busca pedido específico
- `POST /api/orders` - Cria novo pedido
- `POST /api/orders/{id}/send-external` - Envia pedido para API externa

## 🏗️ Arquitetura SOLID

### Single Responsibility Principle (SRP)
- **Models**: Responsáveis apenas pela estrutura de dados
- **Services**: Contêm a lógica de negócio
- **Repositories**: Gerenciam acesso aos dados
- **Controllers**: Lidam apenas com requisições HTTP

### Open/Closed Principle (OCP)
- Interfaces definem contratos que podem ser estendidos
- Novas funcionalidades são adicionadas sem modificar código existente

### Liskov Substitution Principle (LSP)
- Implementações podem ser substituídas sem quebrar o código
- Interfaces garantem contratos consistentes

### Interface Segregation Principle (ISP)
- Interfaces específicas para cada responsabilidade
- `ProductServiceInterface` e `OrderServiceInterface` separados

### Dependency Inversion Principle (DIP)
- Dependências são injetadas via interfaces
- Controllers dependem de abstrações, não implementações

## 🎨 Interface do Usuário

### Vitrine de Produtos
- Grid responsivo de produtos
- Informações completas (nome, preço, marca, categoria, estoque)
- Controle de quantidade ao adicionar ao carrinho
- Indicador de disponibilidade em estoque

### Carrinho de Compras
- Lista de produtos selecionados
- Cálculo automático de totais
- Opção de remover itens
- Botão para limpar carrinho

### Finalização de Pedido
- Resumo do pedido
- Detalhes de entrega
- Opção de envio para API externa
- Feedback de sucesso/erro

## 🔧 Comandos Úteis

```bash
# Acessar container da aplicação
docker-compose exec app bash

# Executar migrations
docker-compose exec app php artisan migrate

# Limpar cache
docker-compose exec app php artisan cache:clear

# Ver logs
docker-compose logs app

# Parar containers
docker-compose down

# Reconstruir containers
docker-compose up -d --build
```

## 🧪 Testes

Para executar os testes:
```bash
docker-compose exec app php artisan test
```

## 📊 Estrutura do Banco de Dados

### Tabela `products`
- `id` (Primary Key)
- `product_id` (Unique - ID da API externa)
- `name` (Nome do produto)
- `description` (Descrição)
- `price` (Preço)
- `category` (Categoria)
- `brand` (Marca)
- `stock` (Quantidade em estoque)
- `image_url` (URL da imagem)

### Tabela `orders`
- `id` (Primary Key)
- `order_id` (UUID único)
- `status` (Status do pedido)
- `message` (Mensagem)
- `estimated_delivery` (Data estimada de entrega)
- `total_amount` (Valor total)

### Tabela `order_items`
- `id` (Primary Key)
- `order_id` (Foreign Key)
- `product_id` (Foreign Key)
- `quantity` (Quantidade)
- `unit_price` (Preço unitário)
- `total_price` (Preço total)

## 🔐 Configuração da API Externa

A aplicação se comunica com a API da Luvinco:
- **URL**: https://luvinco.proxy.beeceptor.com
- **Token**: wQ8ehU2x4gj93CH9lMTnelQO3GcFvLzyqn8Fj3WA0ffQy57I60

### ⚠️ Comportamento da API Externa
**Importante**: A API externa atualiza os preços dos produtos **apenas uma vez por dia à meia-noite**. Durante o restante do dia, as requisições retornarão o mesmo preço.

### 🔄 Estratégia de Sincronização
- **Sincronização principal**: Diariamente às 00:05 (5 minutos após meia-noite)
- **Sincronização de backup**: Diariamente às 06:00 (caso a primeira falhe)
- **Middleware inteligente**: Sincroniza apenas uma vez por dia quando necessário
- **Sincronização manual**: Disponível via comando `php artisan products:sync`

### Endpoints Utilizados
- `GET /products` - Lista de produtos
- `POST /orders` - Criação de pedidos

## 🚀 Deploy

### Opções de Deploy
- **Vercel**: Para frontend
- **Railway**: Para backend completo
- **Render**: Para aplicação completa
- **AWS**: Para infraestrutura escalável

### Variáveis de Ambiente para Deploy
```env
APP_NAME="Vitrine de Produtos"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

DB_CONNECTION=mysql
DB_HOST=seu-host
DB_PORT=3306
DB_DATABASE=vitrine_db
DB_USERNAME=seu-usuario
DB_PASSWORD=sua-senha

LUVINCO_API_URL=https://luvinco.proxy.beeceptor.com
LUVINCO_API_TOKEN=wQ8ehU2x4gj93CH9lMTnelQO3GcFvLzyqn8Fj3WA0ffQy57I60
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto foi desenvolvido como parte do desafio técnico da Luvinco.

## 👨‍💻 Autor

Desenvolvido com ❤️ seguindo as melhores práticas de desenvolvimento.

---

**Nota**: Esta aplicação foi desenvolvida especificamente para o desafio técnico da Luvinco, demonstrando habilidades em PHP, Laravel, Docker e princípios SOLID.