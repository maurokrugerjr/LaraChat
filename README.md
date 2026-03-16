# LaraChat

Aplicação de chat em tempo real com arquitetura desacoplada: **API Laravel** + **SPA Vue 3**, comunicando via REST + WebSockets (Reverb), filas assíncronas (Horizon), armazenamento de arquivos (MinIO) e autenticação por token (Sanctum) — totalmente containerizada com Docker.

## Repositórios

| Projeto | Descrição |
|---|---|
| `LaraChat/` (este) | API REST — Laravel 12 + Sanctum |
| [`LaraChat-web/`](../LaraChat-web) | SPA Frontend — Vue 3 + Vite |

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.4 + Laravel 12 |
| Autenticação | JWT (php-open-source-saver/jwt-auth) |
| Banco de dados | PostgreSQL 16 |
| Cache / Sessão / Filas | Redis 7 |
| WebSocket | Laravel Reverb |
| Worker de filas | Laravel Horizon |
| Storage | MinIO (S3-compatível) |
| Servidor web | Nginx 1.25 |
| E-mail (dev) | Mailpit |
| Frontend | Vue 3 + Vite (projeto separado) |

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) e [Docker Compose](https://docs.docker.com/compose/install/)

## Instalação

**1. Clone os dois repositórios lado a lado**

```bash
git clone <url-larachat>      LaraChat
git clone <url-larachat-web>  LaraChat-web
```

> Os dois diretórios precisam estar no mesmo nível — o Docker Compose referencia `../LaraChat-web`.

**2. Configure os ambientes**

```bash
# API
cd LaraChat
cp .env.example .env

# Frontend
cd ../LaraChat-web
cp .env.example .env
```

**3. Suba os containers**

```bash
cd LaraChat
docker compose up -d --build
```

**4. Gere a chave e rode as migrations**

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

## Serviços

| Serviço | URL |
|---|---|
| SPA Vue 3 | http://localhost:5173 |
| API Laravel | http://localhost:8000 |
| WebSocket (Reverb) | ws://localhost:8080 |
| MinIO Console | http://localhost:9001 |
| Mailpit (e-mails) | http://localhost:8025 |
| PostgreSQL | localhost:5432 |
| Redis | localhost:6379 |

## Workers (opcionais)

Horizon e Reverb rodam como perfis separados:

```bash
# Subir com todos os workers
docker compose --profile workers up -d

# Ou individualmente
docker compose up -d horizon
docker compose up -d reverb
```

## Endpoints da API

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| POST | `/api/auth/register` | — | Cria conta e retorna JWT |
| POST | `/api/auth/login` | — | Autentica e retorna JWT |
| POST | `/api/auth/logout` | Bearer | Invalida o token atual |
| POST | `/api/auth/refresh` | Bearer | Renova o JWT |
| GET | `/api/auth/me` | Bearer | Retorna o usuário autenticado |

## Comandos úteis

```bash
# Acessar o container da aplicação
docker compose exec app bash

# Rodar migrations
docker compose exec app php artisan migrate

# Tinker
docker compose exec app php artisan tinker

# Logs em tempo real
docker compose exec app php artisan pail

# Rodar testes
docker compose exec app php artisan test

# Parar containers
docker compose down

# Parar e remover volumes (apaga dados)
docker compose down -v
```

## Variáveis de ambiente

| Grupo | Variáveis |
|---|---|
| Banco de dados | `DB_*` — conexão com PostgreSQL |
| Redis | `REDIS_*` — cache, sessão e filas |
| Reverb | `REVERB_*` — servidor WebSocket |
| MinIO / S3 | `AWS_*` / `MINIO_*` — armazenamento |
| Mailpit | `MAIL_*` — captura de e-mails |
| Frontend | `FRONTEND_URL` — origem permitida no CORS |

## Licença

MIT
