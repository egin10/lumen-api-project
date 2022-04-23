# Lumen API Project

Lumen API connects to Firebase Realtime Database, Mongodb Atlas & Consume [Reqres](https://reqres.in/) API.

### Usage
1. Clone this repo using Git.
2. Navigate to project dir with command `cd lumen-api-project`
   
- Run With Docker Compose
  1. Run project with command `docker-compose up -d --build`
  2. Migrate User with command `docker-compose exec -T php php artisan migrate:fresh`
  3. Open browser to see API docs and navigate to `http://localhost:8000/api/documentation`

- Run in Local
  1. Update .env file to connect mysql and run command `php artisan migrate`
  2. Run project with command `php -S localhost:8000 -t public`
  3. Open browser to see API docs and navigate to `http://localhost:8000/api/documentation`
   
---
Thank you!