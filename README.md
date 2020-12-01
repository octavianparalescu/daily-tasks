## Dev Installation:

Create the .env files:

`cp envexample .env`

`cp taskenvexample .task.env`

Install composer dependencies:

`docker-compose run composer install`

## Tests

Run PHPUnit:

`docker-compose run test tests`

## Running the task domain:

`docker-compose up -d task_http_dev task_db_dev`

## Running the Swagger UI

`docker-compose up -d swagger-ui`

Accessible at: http://localhost:8082/