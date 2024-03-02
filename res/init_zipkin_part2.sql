CREATE USER 'zipkin'@'%' IDENTIFIED BY 'zipkin';

GRANT ALL PRIVILEGES ON zipkin.* TO 'zipkin'@'%';
