# LaraChat

Aplicação de chat em tempo real construída com Laravel 12, WebSockets via Laravel Reverb, filas assíncronas com Laravel Horizon e armazenamento de arquivos com MinIO — totalmente containerizada com Docker.

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.4 + Laravel 12 |
| Banco de dados | PostgreSQL 16 |
| Cache / Sessão / Filas | Redis 7 |
| WebSocket | Laravel Reverb |
| Worker de filas | Laravel Horizon |
| Storage | MinIO (S3-compatível) |
| Servidor web | Nginx 1.25 |
| E-mail (dev) | Mailpit |
| Frontend build | Vite |

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) e [Docker Compose](https://docs.docker.com/compose/install/)

## Instalação

**1. Clone o repositório**

```bash
git clone <url-do-repositorio> larachat
cd larachat
```

**2. Configure o ambiente**

```bash
cp .env.example .env
```

**3. Suba os containers**

```bash
docker-compose up -d --build
```

**4. Gere a chave da aplicação**

```bash
docker-compose exec app php artisan key:generate
```

**5. Execute as migrations**

```bash
docker-compose exec app php artisan migrate
```

A aplicação estará disponível em `http://localhost:8000`.

## Serviços

| Serviço | URL / Porta |
|---|---|
| Aplicação | http://localhost:8000 |
| WebSocket (Reverb) | ws://localhost:8080 |
| MinIO Console | http://localhost:9001 |
| Mailpit (e-mails) | http://localhost:8025 |
| PostgreSQL | localhost:5432 |
| Redis | localhost:6379 |

## Workers (opcionais)

Horizon e Reverb rodam como perfis separados para não consumir recursos desnecessários em desenvolvimento:

```bash
# Subir todos os workers junto com os demais serviços
docker-compose --profile workers up -d

# Ou subir individualmente
docker-compose up -d horizon
docker-compose up -d reverb
```

## Comandos úteis

```bash
# Acessar o container da aplicação
docker-compose exec app bash

# Rodar migrations
docker-compose exec app php artisan migrate

# Acessar o tinker
docker-compose exec app php artisan tinker

# Ver logs da aplicação em tempo real
docker-compose exec app php artisan pail

# Rodar os testes
docker-compose exec app php artisan test

# Parar todos os containers
docker-compose down

# Parar e remover volumes (apaga dados do banco)
docker-compose down -v
```

## Variáveis de ambiente

Todas as variáveis estão documentadas no `.env.example`. Os principais grupos são:

- **Banco de dados** — conexão com PostgreSQL
- **Redis** — cache, sessão e filas
- **Reverb** — servidor WebSocket
- **MinIO / S3** — armazenamento de arquivos
- **Mailpit** — captura de e-mails em desenvolvimento
- **Horizon** — prefixo das filas no Redis

## Licença

MIT
