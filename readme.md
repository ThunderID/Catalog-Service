FORMAT: 1A

# CATALOG-SERVICE

# Catalog [/catalogs]
Catalog  resource representation.

## Show all Catalogs [GET /catalogs]


+ Request (application/json)
    + Body

            {
                "search": {
                    "_id": "string",
                    "name": "string",
                    "slug": "string",
                    "type": "simple|grouped|downloadable|affiliate|variable",
                    "price": "array|string"
                },
                "sort": {
                    "newest": "asc|desc",
                    "price": "desc|asc",
                    "name": "desc|asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "_id": "string",
                        "name": "string",
                        "slug": "string",
                        "type": "simple|grouped|downloadable|affiliate|variable",
                        "contents": [
                            "string"
                        ],
                        "images": {
                            "path": "string"
                        },
                        "display": {
                            "net_price": "number",
                            "discount": "number",
                            "stock": "number"
                        }
                    },
                    "count": "integer"
                }
            }

## Store Catalog [POST /catalogs]


+ Request (application/json)
    + Body

            {
                "_id": "string",
                "name": "string",
                "slug": "string",
                "type": "simple|grouped|downloadable|affiliate|variable",
                "contents": [
                    "string"
                ],
                "images": {
                    "path": "string"
                },
                "display": {
                    "net_price": "number",
                    "discount": "number",
                    "stock": "number"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "name": "string",
                    "slug": "string",
                    "type": "simple|grouped|downloadable|affiliate|variable",
                    "contents": [
                        "string"
                    ],
                    "images": {
                        "path": "string"
                    },
                    "display": {
                        "net_price": "number",
                        "discount": "number",
                        "stock": "number"
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete Catalog [DELETE /catalogs]


+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "name": "string",
                    "slug": "string",
                    "type": "simple|grouped|downloadable|affiliate|variable",
                    "contents": [
                        "string"
                    ],
                    "images": {
                        "path": "string"
                    },
                    "display": {
                        "net_price": "number",
                        "discount": "number",
                        "stock": "number"
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }