# Introduction #
This page will detail the setup used for developing gmapper.  I will be using the xampp package for development on local machines during development.

# XAMPP/gmapper Setup #
  1. Download latest XAMPP package here: http://www.apachefriends.org/en/xampp.html
```
  wget http://www.apachefriends.org/download.php?xampp-linux-1.6.5a.tar.gz
```
  1. Install XAMPP to /opt
```
  sudo tar xzpvf xampp-linux-1.6.5a.tar.gz -C /opt
```
  1. Create symlink for development in home directory
```
  cd /opt/lampp/htdocs
  sudo ln -s /home/${USER}/public_html ${USER}
  cd -
```
  1. Checkout gmapper
```
  svn co http://gmapper.googlecode.com/svn/trunk/ gmapper --username <google code username>
```
  1. Create public\_html link
```
  ln -s gmapper public_html
```
  1. Setup and start XAMPP
```
  sudo /opt/lampp/lampp security
  sudo /opt/lampp/lampp start
```
  1. That's it!  Now you're ready to view gmapper in your browser and start development

# MySQL Setup #
If MySQL is used in this project, this is how to easily setup a MySQL database using xampp.
  1. Create database
```
  cd /opt/lampp/bin
  sudo ./mysqladmin create <DB NAME>
```
  1. Connect to database using root
```
  sudo ./mysql wordpress
```
  1. Make a user for the database
```
  grant all privileges on *.* to '<DB USER>'@'%' identified by '<DB PASSWORD>';
```
  1. Flush privileges (so you don't have to restart SQL) and exit the MySQL interpreter:
```
  flush privileges;
  \q
```
  1. Test connecting using your new user
```
  ./mysql <DB NAME> -u<DB USER> -p<PASSWORD>
```

  * Note: if '%' doesn't work in grant statement, try 'localhost' for localhost connections.