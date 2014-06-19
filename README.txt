Facebook-like wall for user to user chats (School Assignment)

Requirements:

PHP version >= 5.3
Composer
PHP Rewrite Module
MySQL Database

Installation:

0. open a terminal || prompt

1. cd /path/to/sites
2. git clone (get url from github)
3. cd thewall/
4. composer install

5. open build.properties, and change db settings to match your own.
6. open runtime-conf.xml, and change db settings to match your own.

(now back to terminal)
7. type in: "vendor/propel/propel1/generator/bin/propel-gen" (without quotes)
6. then type: "mysql -u root -p my_db_name < build/sql/schema.sql" (without quotes)