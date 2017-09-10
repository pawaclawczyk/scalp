PHP_VERSION     ?= 7.1
CS_FIXER_VERSION = 2.5.0

PHP_IMAGE        = pawaclawczyk/php:$(PHP_VERSION)
CS_FIXER_IMAGE   = takamichi/php-cs-fixer:$(CS_FIXER_VERSION)

WORKDIR      = /app
MOUNT_FLAG   = -v $(PWD):$(WORKDIR)
WORKDIR_FLAG = -w $(WORKDIR)

DOCKER       = docker run --rm -it $(MOUNT_FLAG) $(WORKDIR_FLAG)

dockerize:
	$(DOCKER) $(PHP_IMAGE) bash

cs-fixer:
	$(DOCKER) $(CS_FIXER_IMAGE) fix
