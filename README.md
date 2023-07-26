# Gitlab Kanban board Project I

## Installation:

## Clone the project
```bash
git clone https://github.com/hamidroohani/messaging-system.git
```

```bash
cd messaging-system/
```

## Option A: Docker

### Run docker to make services
```bash
cd docker
```

### Run docker to make services
```bash
sudo docker compose up -d
```

### Install composer inside docker container
```bash
sudo docker compose exec phpfpm composer install
```

### Migrate tables to the database
```bash
sudo docker compose exec phpfpm php artisan migrate
```

### Seed tables to the database
```bash
sudo docker compose exec phpfpm php artisan db:seed
```

### Change dir owner to webserver user
```bash
sudo chown -R www-data ../.
```


go to http://127.0.0.1:8903

