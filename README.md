# Lynx Business technical test

## Commands
- `make build` Build the containers
- `make up` Start the containers
- `make ex` Enter the php container
- - `pnpm install` Install the dependencies
- - `pnpm build` Build vuejs
- - `exit` Exit the php container
- `make down` Stop the containers

Or manually but you will need to replace environment variables in the docker-compose.yml file:
1. `docker compose up -d --build`
2. `docker compose exec -it lynx_test_php /bin/sh`
3. `composer install`
4. `pnpm install`
5. `pnpm build`
6. `exit`

## URLs
- Homepage: [http://localhost:8000](http://localhost:8000)
- Register: [http://localhost:8000/register](http://localhost:8000/register)
- Login: [http://localhost:8000/login](http://localhost:8000/login)
- Events management: [http://localhost:8000/events](http://localhost:8000/events)
