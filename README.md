# PHPoetry HiQueue
## An English language Haiku Generator-
### PHP 7.4
### MySql or MariaDB
Author: Brian Moniz
Github: https://github.com/youniform/PHPoetry

###   Preface:
If you are an advanced enough user to be mindful about industry standard 
security practices, or for that matter just prefer not to run a stranger's 
php/shell script then "Option B" should suit your needs.  

###   Option A)
###       From project root run provided php/bash script with sudo access:
           $ sudo chmod +x ./PrepareTheProse.php
           $ sudo ./PrepareTheProse.php

###   Option B)
###       Run in your MYSQL CLI, PHPMyAdmin or SqlWorkbench:
           CREATE DATABASE word_list; 
###       From project root run:
           $ sudo apt install pv
           $ pv word_list.sql.gz | gunzip | mysql -u user -p word_list
           $ touch ./env.php
           $ echo "<?php \n $UntrackedDbUser='<DbUser>'; \n $UntrackedDbPass ='<DbPass>';" >> ./env.php
