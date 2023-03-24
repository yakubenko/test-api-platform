# Test task

The development environment of this test API app runs in Docker and includes Nginx, php-fpm, PostgreSQL, PGAdmin. To run it you'll need the following installed on your host.

* Docker
* Docker compose - __can be substituted by the usage of the new Docker compose plugin__
* Make - __It's optional but a Makefile included in the project provides quite a few convenient targets that can be used to perform different tasks. Such as `start`, `install-deps` and many others__

## Run the project

### Running via Make targets

```bash
# build and run
> sudo make start

# install dependencies and init the DB structure
> sudo make init
```

### Running via docker-compose directly

```bash
# build and run
> sudo docker-compose up -d

# composer deps
> sudo docker-compose exec phpfpm composer install

# Create DB
> sudo docker-compose exec phpfpm bin/console doctrine:database:create

# Run migrations
> sudo docker-compose exec phpfpm bin/console doctrine:migrations:migrate

# Run initial seeder command
> sudo docker-compose exec phpfpm bin/console app:seed-initial-data
```

## Accessing the API

After running the containers and app initialization you'll be able to access:

- API Docs in the OpenAPI format - [http://localhost:8080/api](http://localhost:8080/api)
- PGAdmin - [http://localhost:1080](http://localhost:1080) - Credentials: `user@example.com:password`
- PostgeSQL - credentials: `postgres:password@postgres:5432`

## DB structure

- `Category` - `has_multiple_prices` flag indicates that products in this category can or can not have different prices for variants. When set to `false` all variants of a product, which belongs to such category, will have their price updated on any price add or change for a variant of the product. Currency relation is respected and prices in other currencies won't be touched.
- `Product` belongs to a `Category`
- Every `Product` can have many `Variants`
- `Variant` can have many prices but every price must be in a unique `Currency`. When one tries to add more than 1 price with already existing currency they'll receive a validation error.
- `Currency` - predefined `eur` and `usd`. These are used to define prices for products' variants.

## Important notes

This API is built using ApiPlatform for Symfony and one of the things the user of this API must know is that it uses IRI for identificators.

For example, instead if passing

```json
POST /api/prices

{
  "price": 140,
  "variant_id": 6,
  "currency_id": 5
}

```

One must pass

```json
POST /api/prices

{
  "price": 140,
  "variant": "/api/variants/6",
  "currency": "/api/currencies/5"
}
```
For the sake of speed, I didn't define sub resources for API responses (read relations). It can be easily done by providing settings in the Entity classes. So, the responses look like that:


for `Accept: application/vnd.api+json`

```json
{
	"data": {
		"id": "/api/products/7",
		"type": "Product",
		"attributes": {
			"_id": 7,
			"title": "Cool jacket 2"
		},
		"relationships": {
			"category": {
				"data": {
					"type": "Category",
					"id": "/api/categories/8"
				}
			}
		}
	}
}

```

and for `Accept: application/json`

```json
[
	{
		"id": 8,
		"title": "Product in category 83",
		"category": "/api/categories/83",
		"variants": []
	},
	{
		"id": 9,
		"title": "Product in category 84",
		"category": "/api/categories/84",
		"variants": []
	}
]
```
