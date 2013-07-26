Multi-Threaded-Server-in-PHP
============================

Simple skeleton for multi-threaded server (and client) in PHP


Requirements
------------
- PHP 5.3.0 or above (Source code tested with PHP 5.5.1)
- With pthreads and ZTS enabled in the PHP build


Build Your Own PHP
------------------

Ubuntu 13.04 was used for the following example.

- Install some required libs
```bash
sudo apt-get install aptitude git \
  libxml2-dev \
  libssl-dev \
  libbz2-dev \
  libcurl4-gnutls-dev \
  libpng-dev
```

- Download PHP from http://php.net/downloads.php
```bash
wget http://ca3.php.net/get/php-5.5.1.tar.bz2/from/ca1.php.net/mirror
```

- Extract to tmp folder and go to the PHP source directory
```bash
tar xjf php-5.5.1.tar.bz2 -C /tmp
cd /tmp/php-5.5.1
```

- Get pthreads source
```bash
cd ext
git clone https://github.com/krakjoe/pthreads.git
cd ..
```

- Remove the default configure file and rebuild it
```bash
rm configure
./buildconf --force
```

- Run configure
```bash
./configure --with-config-file-path=/etc/php5/apache2 \
  --with-pear=/usr/share/php \
  --with-bz2 \
  --with-curl \
  --with-gd \
  --enable-calendar \
  --enable-mbstring \
  --enable-bcmath \
  --enable-sockets \
  --with-libxml-dir=/usr \
  --with-mysqli \
  --with-mysql \
  --with-openssl \
  --with-regex=php \
  --with-zlib \
  --enable-pthreads=static \
  --enable-maintainer-zts
````

- Compile and test
```bash
make
make test
```

Done!


Run Server and Client
---------------------
```bash
php Server.php &
php Client.php
```
