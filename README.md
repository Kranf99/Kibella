# Kibella

Kibella is a free Self-service BI/Dashboarding Tool that runs everywhere.

Upon writing these lines (2018/4), Kibella is the only open source, self-service BI solution that is running on a simple, easy&cheap to maintain infrastructure (Apache+PHP). 

Kibella is so efficient that it runs easily on any basic WebHosting platform. For example, you can deploy a fully working Kibella dashboard on a Webserver that costs only 18€/year (from <a href="https://www.ovh.com/fr/hebergement-web/">here</a>). This makes Kibella the cheapest and the most versatile dashboarding solution available on the market. This means that there are actually no limits to the deployement of Kibella everywhere! You can deploy Kibella anywhere and for any use case. Cool! :smile:

The design tool (to create new dashboard or to edit/customize existing dashboards) is running 100% in the Browser so that there can be an unlimited number of dashboard designers.

The Front-End is coded with Angular.js & Webpack + Grunt.
The Back-End is running on a simple Apache Server and a SQL (e.g. SQLite, Oracle or SQLServer) database.

Kibella should run in any setup composed of the "Apache Web Server" and PHP.
Typical examples of such setup includes:
  * LAMP: https://en.wikipedia.org/wiki/LAMP_(software_bundle)
  * WAMP: https://en.wikipedia.org/wiki/LAMP_(software_bundle)#WAMP

The datasets injected into Kibella for visualization can be produced by <b>*any*</b> data management tool (ETL tool) supporting SQLite (you have complete freedom!). However, when producing new datasets for Kibella, we advise you to use the <a href="https://timi.eu/products-solutions/timi/anatella/">Anatella</a> data management solution because <a href="https://timi.eu/products-solutions/timi/anatella/">Anatella</a> is well integrated with Kibella: i.e. <a href="https://timi.eu/products-solutions/timi/anatella/">Anatella</a> has specific functionnalities (i.e. a little box) that allows you to very easily and very quickly exports any datasets to Kibella.

Kibella is a popular dashboarding solution when you have to deploy a large quantity of completely separated self-service BI environments. A whole Kibella system is around 11MB. This means that, if you need to create completely separated BI environments (e.g. for each of your different customers), you can just duplicate these 11MB and make several Kibella installations on the same Webserver. Each different installation can have different users, different dashboards, different datasets (nothing is shared). So, to deploy 1000 different, completely separated self-service BI environments, you just need 11GB (=1000x11MB) of hard drive space! Nothing more! Neat! :smile:  As a comparison, the cost of creating 1000 independent self-service BI environments using any other technology than Kibella is usually around several hundred thousands euros. The parameters (users,vizualizations,datasets) of each of these 1000 different installation can be automatically computed by any ETL tool supporting SQLite (e.g. <a href="https://timi.eu/products-solutions/timi/anatella/">Anatella</a>).


---
# WINDOWS Installation for Simple Users

There are no administrative privileges required! <br/>
Anybody can install&run Kibella! Complete freedom!<br/>

The installation of Kibella inside a UwAMP server is now 100% automated: <br/>
Here are the 3 simple steps:
* Unzip the file "Kibella_UwAmp.zip" (available in the <a href="https://github.com/Kranf99/kibella/releases">"releases" section</a>)
* Run "UwAmp.exe"
* Open Kibella inside your browser: i.e. open the URL "http://localhost/kibella".

Under MS-Windows, Kibella runs inside the famous open-source&free project "UwAMP" that you can download <a href="https://www.uwamp.com/en/">here</a> (no need to download UwAmp yourself: it's already included in the Kibella zip file).


---
# LINUX Installation for Simple Users

* download and unzip the zip file "kibella_alone_linux.zip" (available in the "releases" section)
* Copy the Kibella files inside a directory XX served by Apache.
* Add inside the root of the Kibella Install directory a file named ".htaccess" that contains these 2 lines:
```
RewriteEngine on
RewriteRule (db/.*) JSON_SQL_Bridge/requests.php [L]
```
  For your convenience, such a file is directly provided inside the Kibella distribution.
* Enable write access inside the sub-directory "tempdata" and "tempdata/cache" inside the Kibella installation dir:
```
cd kibella
sudo chmod 777 tempdata
sudo chmod 777 tempdata/cache
sudo chmod 666 tempdata/kibella.sqlite
sudo chmod 666 tempdata/kibella_stats.sqlite
```

---
# Installation for Developers

For windows developers, the best way to work on Kibella is to use the 
"Ubuntu on Windows". More information on this subject here:
  https://docs.microsoft.com/en-us/windows/wsl/install-win10

1. install npm libraries 

   Here are the installation instructions extracted from https://linuxize.com/post/how-to-install-node-js-on-ubuntu-18.04/:
```
sudo curl -sL https://deb.nodesource.com/setup_10.x | sudo -E bash -
sudo apt-get install nodejs
sudo apt-get install make
(cd /mnt/f/soft/UwAmp2/www/kibella_src)
```

2. Install npm dependencies at root of project for Grunt & Webpack and in `/interface` for front-end dependencies with `sudo npm install` in `/`
   
   This should download & unpack a whole bunch of required third party librairies. These librairies will be located in the 2 directories "node_modules" and "interface/node_modules". For your convenience, I also packed all these librairies in one big zip file here: https://github.com/Kranf99/kibella/releases/tag/v0.1

3. If it's your first time using grunt, you must install it globally 
   with `sudo npm install -g grunt-cli`

4. (optional) Config

    Change hostname and port in `interface/config`
    * the default is `"elasticsearch": "../db"`
    * custom must include full hostname + absolute path, eg:"http://localhost:8988/kibella/db"

5. Build

 5.1. Build a large index.js for easy debugging
```
(sudo) grunt dev
```

 This will delete all in `/dev` if it exist, copy all the non-interface folders (`/public`,`/src`...) and transpile the bundle within `/dev/interface` and keep the process alive for watching your modifications (webpack --watch)

 5.2. Build a small index.js for distribution

```
(sudo) grunt dist --force
```

 This will delete all in `/dist` if it exist, copy all the non-interface folders (`/public`,`/src`...) and transpile the bundle within `/dist/interface`*


---
# Troubleshooting

1. Troubleshooting Linux Installation

 1.1. Enable .htaccess application-specific configuration in the Apache server

Since Kibella contains a .htaccess configuration file (present in Kibella’s 
installation directory), Apache needs to be configured to enable its use.
To this end, verify that the Apache configuration file 
```
/etc/apache2/sites-available/default 
```
has the AllowOverride directive set to All inside the Directory directive block 
for the webserver directory (e.g. /var/www). This means that that part of the 
file should (typically) look like this:
```
<Directory /var/www>
      Options Indexes FollowSymLinks MultiViews
      AllowOverride All
      Order allow,deny
      Allow from all
</Directory>
```
If not, edit the "/etc/apache2/httpd.conf" configuration file (or the "/etc/apache2/apache2.conf" configuration file for the Linux Debian distribution) and add the following lines:
```
<Directory /var/www/kibella>
      AllowOverride All
</Directory>
```

 1.2. Enable Apache’s rewrite module

Verify that the rewrite module is enabled by running:
```
sudo apache2ctl -M
```
where a module called "rewrite" should appear in the list.
If it does not appear, enable it with:
```
sudo a2enmod rewrite
```

1.3. Install PHP SQlite3 module

To detect tis type of error, you need to type:
```
tail -f /var/log/apache2/error.log 
```
If you see:
```
PHP Fatal error:  Class 'sqlite3' not found in /var/www/html/kibella/JSON_SQL_Bridge/classes.php on line 11
```
...it means that you need to install the PHP SQlite3 module

Step 1 :

 * For PHP5, use
```
        sudo apt-get install php5-sqlite
```

 * For PHP7.0, use
```
        sudo apt-get install php7.0-sqlite
```

 * For PHP7.1, use
```
        sudo apt-get install php7.1-sqlite
```

 * For PHP7.2, use
```
        sudo apt-get install php7.2-sqlite
```

 * For PHP7.3, use
```
        sudo apt-get install php7.3-sqlite
```

Step 2 :

   Restart Apache:
```
        sudo service apache2 restart
```

1.4. Enable write access in temp directory

To detect tis type of error, you need to type:
```
tail -f /var/log/apache2/error.log 
```

If you see:
```
PHP Warning: fopen(/var/www/kibella/tempdata/cache/query_INCOME(income)-278b7e89b0d371c514ea8b7c87a624cd.json_tmp ): failed to open stream: Permission denied in /var/www/kibella/JSON_SQL_Bridge/functionsparse.php on line 11
```
...it means that you need to enable write access inside the sub-directory "tempdata" and "tempdata/cache" inside the Kibella installation dir. This is done this way:
```
cd kibella
sudo chmod 777 tempdata/
sudo chmod 777 tempdata/cache/
```

1.5. Enable write access to "kibella.sqlite"

This happens often when you update the dashboard vizualisations with new ones (i.e. when you replace the kibella.sqlite file on your remote "production" server). To detect tis type of error, you need to type:
```
tail -f /var/log/apache2/error.log 
```

If you see:
```
PHP Notice:  session_start(): ps_files_cleanup_dir: opendir(/var/lib/php/sessions) failed: Permission denied (13) in /var/www/html/kibella/JSON_SQL_Bridge/users/user.php on line 41
```
...it means that you need to enable write access to "tempdata/kibella.sqlite" in the Kibella installation dir. 
This is done this way:
```
cd kibella
sudo chmod 666 tempdata/kibella.sqlite
sudo chmod 666 tempdata/kibella_stats.sqlite
```
If that's not enough, you also might need to run some "chown" command.

---
# How To contribute

1. Regarding the Front-End code.

We are always happy to receive any contribution to the front-end code (i.e. to the code that is running inside the Web Browser)! :smile:

The front-end is written in Angular.js + Webpack + Grunt. Nearly all the vizualisation modules in the Front-end are built "on top" of Plotly.js or D3.js. A Kibella vizualisation module is just a small layout ("on top" of Plotly.js or D3.js) that provides a self-service, zero-code interface that allows to create all the charts using only the mouse. Thanks to a modular code architecture, it's very easy to add new vizualitions modules! So, grab your keyboards and let's create together the ultimate Self-service Dashboarding Tool! ...So that everybody can enjoy beautiful dashboards everywhere! Yes! :smile:

A good place to find what could be interesting as a contribution is inside the <a href="https://github.com/Kranf99/kibella/issues">issue section</a> of the Kibella Github repository.


2. Regarding the Back-End code.

We are not interested in any help regarding the back-end code (i.e. the back-end code is the code that is runnning on the Web Server). The back-end code is pure PHP code that interfaces with a classical database (SQLite, Oracle, MS-SQLServer) through SQL. We don't want to change that: For us, PHP+SQL is already the best solution because it offers:
* ..the highest portability (i.e. you can deploy PHP almost everywhere)
* ..the highest level of security (i.e. By default, Kibella runs inside Apache and Apache is the most secure Web server currently available)
* ..the easiest to maintain & support solution (i.e. what's easier to maintain than a simple Apache+PHP server?)
* ..a relatively fast processing speed (i.e. it really depends on which SQL database you are using. ..But you should know that the official TPC-H benchmarck is telling us that the MS-SQLServer engine is the fastest non-clustered database engine currently available).

By default, we won't follow-up on any question that is related to the back-end code (since we are not interested in contributions to the back-end code).



---
Copyright 2017 Frank Vanden berghen
All Right reserved.
