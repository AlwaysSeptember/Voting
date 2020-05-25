
set -e
set -x

echo "found entrypoint"
pwd
ls -l
cd app
npm ci
npm run build