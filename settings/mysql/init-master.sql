CREATE USER slave1@'%' IDENTIFIED WITH 'mysql_native_password' BY 'slavepassword1';
GRANT REPLICATION SLAVE ON *.* TO slave1@'%';
FLUSH PRIVILEGES;