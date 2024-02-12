
# Near-Earth Objects API


## Run Locally

Clone the project

```bash
  git clone git@github.com:yevhenhrebenikov/neo.git
```

Go to the project directory and start docker

```bash
  cd neo
  docker-compose up -d
```

Go to php container and install dependencies

```bash
  docker exec -it neo.php bash
  composer install
```

Create database and run migrations

```bash
  bin/console d:d:c
  bin/console d:m:m
```


## Import Data
Use the following command to import data from Nasa:
```bash
  bin/console neo:import
```
Run ```bash bin/console neo:import --help ``` to view available options.


## API Reference

#### Get all neo items

```http
  GET /v1/neo
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `page` | `integer` | *Optional*. Page number (default 1)|
| `limit` | `integer` | *Optional* Items count per page (default 10)|

#### Get all potentially hazardous asteroids

```http
  GET /v1/neo/hazardous
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `page` | `integer` | *Optional*. Page number (default 1)|
| `limit` | `integer` | *Optional* Items count per page (default 10)|

#### Get fastest asteroid info

```http
  GET /v1/neo/fastest
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `hazardous` | `boolean` | *Optional*. Is hazardous (default false) |

#### Get month in the current year with most asteroids

```http
  GET /v1/neo/best-month
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `hazardous` | `boolean` | *Optional*. Is hazardous (default false) |


