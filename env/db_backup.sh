sudo docker exec $1 mysqldump -u root --password=pwd db > backup.sql
