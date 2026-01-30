# 🚀 High-Performance URL Shortener

A distributed, full-stack URL shortening service built with modern DevOps practices.
Features asynchronous click tracking via RabbitMQ, a RESTful API, and a reactive Next.js UI.

## 🛠 Tech Stack

- **Backend:** Symfony 7 (PHP 8.4)
- **Frontend:** Next.js 14 (TypeScript + Tailwind CSS)
- **Database:** PostgreSQL 16
- **Queue:** RabbitMQ (Async analytics)
- **Infrastructure:** Docker Compose

## ✅ Key Features

- **URL Shortening:** Generate random 6-character short codes.
- **Soft Delete:** Hide links safely without data loss.
- **Async Analytics:** Click tracking is decoupled using RabbitMQ for high performance.
- **Instant Redirects:** Zero-latency redirection engine.
- **Full Stack Logic:** Complete flow from Frontend → API → DB.

---

## ⚡️ Quick Start Guide

### 1. Build & Start the System

This command builds the Docker images (Frontend & Backend) and starts all services in the background.

```bash
docker compose up -d --build

# Install PHP packages
docker compose exec backend composer install

# Run database migrations
docker compose exec backend php bin/console doctrine:migrations:migrate --no-interaction

docker compose exec backend php bin/console messenger:consume async -vv

docker compose exec backend php bin/phpunit

Service,URL,Credentials
Frontend App,http://localhost:3000,N/A
Backend API,http://localhost:8000/api/urls,N/A
RabbitMQ Dashboard,http://localhost:15672,user / password

docker compose restart backend
```
