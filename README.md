# Controle de inventório

Essa é uma aplicação web para controle de estoque, com compra e venda de produtos.

![Print do dashboard inicial com informações sobre o dia de hoje](./inventory-control.png "Dashboard")
## Stack
- Backend: PHP
- Frontend: Vue.js
- Infra: Docker + Nginx

## Pré-requisitos
- Docker

## Como rodar

1. Clone o repositório
```bash
git clone https://github.com/gabrielcavdias/inventory-control
cd [pasta-do-projeto]
```

2. Suba os containers 
```bash
docker compose up -d
```
3. Instalando dependências
```bash
docker compose exec front npm install
docker compose exec api composer install
docker compose exec api php artisan migrate
docker compose exec api php artisan db:seed
```

4. Acesse a aplicação

- Frontend: http://localhost:5173

- API: http://localhost:8010

5. Entre com Login e Senha

- email: ```admin@inventory.com```

- senha: ```admin12345```
