#!/bin/bash

echo "🚀 Starting Laravel Docker Setup..."

# Copy env jika belum ada
if [ ! -f .env ]; then
    echo "📄 Creating .env file..."
    cp .env.example .env
fi

# Build & run container
echo "🐳 Building Docker containers..."
docker compose up -d --build

# Tunggu database ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Install dependency
echo "📦 Installing Composer dependencies..."
docker exec -it task_management_web composer install

# Generate app key
echo "🔑 Generating app key..."
docker exec -it task_management_web php artisan key:generate

# Run migration
echo "🗄 Running database migration..."
docker exec -it task_management_web php artisan migrate --force

echo "✅ Setup completed!"
