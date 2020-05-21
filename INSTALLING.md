

## How to get the dev environment working

First run, to let the vendor be installed, and DB created.

```
docker-compose up --build db
```

```
db_1             | 2020-05-21T15:52:46.265794Z 0 [Note] mysqld: ready for connections.
db_1             | Version: '5.7.30'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server (GPL)
```

Then after that run the installer to install the vendor files and create the DB structure.

```
docker-compose up --build installer
```


Then after that bring the site up.

```
docker-compose up --build 
```

and it should be viewable at:
http://local.api.voting.phpimagick.com/

If the DB ever needs to be recreated from scratch.

```
php vendor/bin/phinx migrate -t 0
php vendor/bin/phinx migrate
```

Or just kill the boxes, delete the data/mysql directory, and start from scratch.