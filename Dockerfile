ARG PHP_VERSION=8.5

######################
# Test runner target #
######################
FROM milejko/php:${PHP_VERSION}

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage
