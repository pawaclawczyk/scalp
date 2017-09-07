MOUNT_FLAG   = -v $(PWD):/app
WORKDIR_FLAG = -w /app

dockerize:
	docker run -it $(MOUNT_FLAG) $(WORKDIR_FLAG) pawaclawczyk/php bash

