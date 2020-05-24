
set -e
set -x


cd /var/app/app
npm ci
npm run build