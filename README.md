## Installation instructions

based on Laravel 11, requires PHP 8.4
optional: use DDEV environment (ddev start)

1. run composer
```bash
composer install
```

2. copy .env.example to .env with correct DB settings

3. generate app key for encryption
```bash
    php artisan key:generate
```

4. run migrations
```bash
php artisan migrate
```

5. generate client id + secret for oauth 2:
```bash
php artisan passport:client --client
```

6. and set client_id and client_secret in .env
```bash
VAULTYVAULT_CLIENT_ID  
VAULTYVAULT_CLIENT_SECRET  
```

## Usage instructions

### Generate access tokens
1. Generate access tokens for different scopes (detokenize, tokenize, detokenize + tokenize combined )
via
```bash
php artisan app:generate-auth-token
```
2. Use your favourite request tool and POST to the following JSON body to the following endpoints with the generated auth token header:

set accept header:
```bash
"application/json"
```
set authorization header:
```bash
Bearer example123213213213123131231232132132131231312312321321321312313123example
```

### POST to /tokenize

```json
{
    "id": "req-123",
    "data": {
        "field1": "value1",
        "field2": "value2",
        "fieldn": "valuen"
    }
}
```

### POST  /detokenize

```json
{
    "id": "req-33445",
    "data": {
        "field1": "t8yk4f5",
        "field2": "gj45nkd",
        "field3": "invalid token"
    }
}
```

## Available Commands


Generate access tokens for different scopes
```bash
php artisan app:generate-auth-token
```

Generate client secret + id for OAuth authentication
```bash
php artisan passport:client --client
```

# Limits / infos / stretch goals

1. Uses OAuth2 machine-to-machine authentication using Laravel Passport
2. Encryption based on OpenSSL and the AES-256-CBC cipher should suffice based on https://www.bsi.bund.de/SharedDocs/Downloads/DE/BSI/Publikationen/TechnischeRichtlinien/TR02102/BSI-TR-02102.pdf
3. AI usage: No CHOP was used but TAB auto complete was enabled via Claude 3.5
