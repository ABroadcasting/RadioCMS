# Installation
##1. First of all, you need to install LAMP on your server (recommended php-fpm)

**_Debian/Ubuntu_**

apt-get install apache2 php-fpm mysql-server
##2. You will need some php extensions to use this script
* Simple XML
* GD2
* LibSSH
* Gettext
* curl

**_Debian/Ubuntu_**

`apt-get install php-simplexml php-ssh2 php-curl php-gd php-gettext`

##3.You may install icecast and ezstream

**_Debian/Ubuntu_**

`apt-get install icecast ezstream`

**_Notice!_** You may use extended version with libtag support, if not, you wiil be noticed at the main page.
You may download it here
https://github.com/DragonZX/ezstream-ext


