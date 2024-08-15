
# php-larave-template

Experimental Laravel 10 PHP runtime template for BOHR.

Supported php versions:

- 7.4
- 8.0
- 8.1
- 8.2

Needed BOHR environment variables to be configured:

- BOHR_FUNCTION_RUNTIME = provided.al2
- BOHR_WEB_ADAPTER = 1
- BOHR_WEB_ADAPTER_TYPE = php
- BOHR_WEB_ADAPTER_PHP_VERSION = (php7.4 | php8.0 | php8.1 | php8.2)
- REDIS_HOST=""
- REDIS_PASSWORD=""
- REDIS_PORT=""
- CACHE_DRIVER="redis"
- SESSION_DRIVER="redis"

We recommend upstash for redis cache.

# Warning

do not use these keys on the list below:

- AWS_ACCESS_KEY_ID=
- AWS_SECRET_ACCESS_KEY=
- AWS_DEFAULT_REGION=
- AWS_BUCKET=
- AWS_USE_PATH_STYLE_ENDPOINT=

they are used for internally by AWS and will not work.