# Installation
##1. First of all, you need to install LAMP on your server (recommended php-fpm)
   ###Debian/Ubuntu
   apt-get install apache2 php-fpm mysql-server
##2. You will need some php extensions to use this script
* Simple XML
* GD2
* LibSSH
* Gettext
* curl
   ###Debian/Ubuntu
apt-get install php-simplexml php-ssh2 php-curl php-gd php-gettext

##3.You may install icecast and ezstream
   ###Debian/Ubuntu
apt-get install icecast ezstream

Notice! You may use extended version with libtag support, if not, you wiil be noticed at the main page.
You may download it here
https://github.com/DragonZX/ezstream-ext


