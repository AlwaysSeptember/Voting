
FROM node:8.16.2-stretch

USER root

WORKDIR /var/app

# COPY package.json .
# RUN npm install --quiet
# COPY . .

# CMD sh /var/app/containers/npm_build/entrypoint.sh

ADD entrypoint.sh /entrypoint.sh
CMD chmod +x entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

#CMD git add entrypoint.sh
#CMD git commit

# CMD pwd && ls -l && ls -l ./app && sh ./app/containers/npm_build/entrypoint.sh

# CMD pwd && ls -l && ls -l /var/app && ls -l /var/app/containers && ls -l /var/app/containers/npm_build
