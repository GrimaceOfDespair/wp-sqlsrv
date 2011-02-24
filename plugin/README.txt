WordPress for SQL Server Patch has become WordPress Database Abstraction Plugin

Database Abstraction?  Isn't this only for SQL Server and Azure?

The WordPress Database Abstraction Plugin provides two things - database access abstraction
and SQL dialect abstraction.  What does that mean?

1. Database Access Abstraction is the way you connect to the database through PHP.
This plugin allows mysql, pdo, sqlsrv or mssql extensions to be used.  This allows
you to choose the way your WordPress installation connects to your database.  You
can use the plugin and still use a Mysql Database, which is perfect if your hosting
provider only has PDO with the mysql driver available.  The flexible structure of the
plugin means that dropping in additional drivers is easy.

2. SQL dialect abstraction means translating from the dialect understood by Mysql
to other dialects.  Currently only translation layers for T-SQL (used by Azure and SQL Server)
are provided.  However this is an open source project and additional translation layers
could be added.

Which package is right for me?

1. I want to upgrade an existing site running the Wordpress for SQL Server Patch -
      Download the distribution/wp-db-abstraction.zip - this is a full install of
      the latest version of Wordpress with the plugin and drop in files in place and
      ready to use.

2. I want to install a new site on SQL Server or Azure -
      Download the distribution/wp-db-abstraction.zip - this is a full install of
      the latest version of Wordpress with the plugin and drop in files in place and
      ready to use.

3. I am a WordPress expert and want to control how the plugin is installed -
      Download the plugin/wp-db-abstraction.zip - this contains only the plugin
      files and will require manual installation into the mu-plugins folder, and db.php
      will need to be copied into the correct location.  This is for WordPress experts.

What does this change - from patch to plugin - mean?

1. Your site will run just as it always has, the files are just in a different location.

2. You no longer need a specially patched version of WordPress to run on Sql Server or Azure,
just use a regular WordPress release, and put the plugin files and drop in file in the correct locations.

3. You can still update your entire site using a "pre-packaged" version of WordPress with all the plugin files
in place and ready to go.

4. For new installs - we package our own wp-config.php creator.  The creation url will be at
$your_wordpress_url/wp-content/mu-plugins/wp-db-abstraction/setup-config.php  The original
setup-config.php WILL be redirected after the second step if db.php is in the right place,
or you will be automatically redirected (using .htaccess with mod_rewrite or
web.config urlrewrite rules if your webserver supports it).

What this does NOT mean

1. You must have the plugin files in place BEFORE installing, and you must upgrade
the WordPress Database Abstraction Plugin to a version that supports the version of
WordPress you want to run BEFORE upgrading WordPress Core.

2. The WordPress Database Abstraction Plugin must be in the mu-plugins folder (that is "must-use" plugins),
and the special "db.php" drop in file must be in place in the wp-content folder.
The plugin cannot be downloaded, installed, run or updated as a "regular" plugin.

3. If you are you using other plugins with "db.php" drop ins, you must rename these files
($pluginname-db.php is a good choice) and add "include '$pluginname-db.php';" to the bottom
of the WordPress Database Abstraction Plugin's db.php in order to continue using the
plugin properly.

How do I upgrade from the Patch to the Plugin?

1. You can "automatically" upgrade using the upgrade mechanism in the patch.  It will
download the "pre-packaged" version of WordPress with the plugin files in place.

2. You can manually upgrade by downloading the lastest version of WordPress, drop the files
over your existing install, create a "mu-plugins" folder inside the wp-content folder.

Download the plugin and unzip it into the mu-plugins folder. You should have a wp-db-abstraction.php file
and a wp-db-database directory inside mu-plugins.

Finally copy db.php from wp-content/mu-plugins/wp-db-abstraction/db.php
to wp-content/db.php.  Do not attempt to use your install before the plugin and db.php
are in place!

You should not have to change anything in your wp-config.php file.