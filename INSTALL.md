# Insurance-ItemTracker
## Installation & Setup

> **Author:** Michael Cabot (`cabotmichael@gmail.com`)
> **License:** GPL v3.0 `See LICENSE.md.`

###  1. Compile SCSS (/scss/main.scss to /htdocs/style/main.css)

>  NOTE: The scss stylesheet system for this app includes source from external vendors:
>   - Bootstrap 4.4.1  | MIT License | getbootstrap.com
>   - normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css
>   
>  These are already imported into the main stylesheet. (main.scss)

###  2. Create & Setup MySQL Database

 1. Create a new database.
 2. Import `'/sql/database.sql'` to create tables and database structure.

> If you would like to setup a demo database containing 150+ example items, then import `'/sql/database_example.sql'` instead of `'/sql/database.sql'`.

###  3. Edit `'mysql.config.example.php'`

 1. Rename `'mysql.config.example.php'` to `'mysql.config.php'`
 2. Edit `'mysql.config.php'` and add mysql server, login & database information.
 
> **Note:** mysql.config.php is expected to be one folder level above index.php, outside of htdocs.

###  4. Copy `'/htdocs/'` Contents to Web Server

> This app can be served with any web server that supports php with mysqli extension. (Apache is recommended)
> **Note:** For security reasons only you should have access to the app and its files hosted & served by the web server. For apache web servers, this can be done with an .htaccess file.

> `For more information, see README.md.`