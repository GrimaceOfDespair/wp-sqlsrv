wp-sqlsrv
=========

WP Database Abstraction is a plugin to make it possible to run WP on top of MS SQL Server or Azure and provides two features, database access abstraction and SQL dialect abstraction. This plugin cannot be installed or used as a regular plugin, it must be in the "mu-plugins" directory (must use plugins) and in addition to the plugin it contains a "drop-in" to hook into WordPress Database functionality.

Database Access Abstraction is the way you connect to the database through PHP. This plugin allows mysql, mysqli, pdo, sqlsrv or mssql extensions to be used. PDO has support for mssql, dblib, sqlsrv and mysql drivers. This allows you to choose the way your WordPress installation connects to your database. You can use the plugin and still use a Mysql Database, which is perfect if your hosting provider does not make the mysql extension available. The flexible structure of the plugin means that dropping in additional drivers is easy.

SQL dialect abstraction means translating from the dialect understood by Mysql to other dialects. Currently only translation layers for T-SQL (used by Azure and SQL Server) are provided. However this is an open source project and additional translation layers could be added.

This library was imported from http://sourceforge.net/projects/wp-sqlsrv/ and originally created by http://wordpress.visitmix.com/
