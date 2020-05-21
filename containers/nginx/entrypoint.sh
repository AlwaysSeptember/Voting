
set -e
set -x


ENV_TO_USE=${ENV_DESCRIPTION:=default}

echo "ENV_TO_USE is ${ENV_TO_USE}";

# Generate nginx config file for the centos,dev environment
php vendor/bin/configurate \
    -p server_config.php \
    containers/nginx/config/nginx.conf.php \
    containers/nginx/config/nginx.conf \
    $ENV_TO_USE


/usr/sbin/nginx -c /var/app/containers/nginx/config/nginx.conf
