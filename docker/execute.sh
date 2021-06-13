printf "=====> Copy conf project to Nginx Internal...\n"
docker cp $PWD/docker/api-email.conf service-email:/etc/nginx/conf.d/

printf "=====> Copy conf project to Nginx Default...\n"
docker cp $PWD/docker/nginx/api-email.conf nginx:/etc/nginx/conf.d/

printf "=====> Nginx Restart Default...\n"
docker restart nginx

printf "=====> end sh Execute...\n"
