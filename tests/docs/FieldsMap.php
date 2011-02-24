<?php

require_once 'PHPUnit/Framework.php';
require_once(dirname(dirname(__FILE__)).'/abstract-plugin/wp-includes/wp-db/sqlsrv/translations/fields_map.php');

class FieldSMap extends PHPUnit_Framework_TestCase
{
  static $acum = array();
  
  /**
   * @dataProvider provider
   */
  public function testAdd($qry, $columnTypes)
  { 
    $Fields_map = new Fields_map();
    $this->assertEquals($columnTypes, $Fields_map->extract_column_types($qry));
  }

  /**
   * @dataProvider provider
   */
  public function testWriteFile($qry, $columnTypes)
  {
    $Fields_map = new Fields_map();

    $first_iteration = empty(self::$acum);
    if ($first_iteration) {
      @unlink($Fields_map->filepath);
    }

    self::$acum = array_merge(self::$acum, $columnTypes);
    $Fields_map->update_for($qry, $columnTypes);
    $this->assertEquals(self::$acum, $Fields_map->read());
  }



  public static function provider()
  {
    /** Create WordPress database tables SQL */
    $prefix = substr(md5(uniqid(rand(),true)),0,rand(2,10));
    //$prefix = 'bla';
    $wpdb = new StdClass();
    $wpdb->terms = $prefix.'_terms';
    $wpdb->term_taxonomy = $prefix.'_term_taxonomy';
    $wpdb->term_relationships = $prefix.'_term_relationships';
    $wpdb->comments = $prefix.'_comments';
    $wpdb->links = $prefix.'_links';
    $wpdb->options = $prefix.'_options';
    $wpdb->postmeta = $prefix.'_postmeta';
    $wpdb->posts = $prefix.'_posts';
    $wpdb->users = $prefix.'_users';
    $wpdb->usermeta = $prefix.'_usermeta';

    $arr = array( 
		 array(
		       'qry'=>"
 CREATE TABLE $wpdb->terms (
 term_id bigint(20) unsigned NOT NULL auto_increment,
 name varchar(200) NOT NULL default '',
 slug varchar(200) NOT NULL default '',
 term_group bigint(10) NOT NULL default 0,
 PRIMARY KEY  (term_id),
 UNIQUE KEY slug (slug),
 KEY name (name)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->terms => array(
								       'term_id' => array('type' => 'primary_id'),
								       ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->term_taxonomy (
 term_taxonomy_id bigint(20) unsigned NOT NULL auto_increment,
 term_id bigint(20) unsigned NOT NULL default 0,
 taxonomy varchar(32) NOT NULL default '',
 description longtext NOT NULL,
 parent bigint(20) unsigned NOT NULL default 0,
 count bigint(20) NOT NULL default 0,
 PRIMARY KEY  (term_taxonomy_id),
 UNIQUE KEY term_id_taxonomy (term_id,taxonomy),
 KEY taxonomy (taxonomy)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->term_taxonomy => array(
									       'description' => array('type' => 'text'),
									       'term_taxonomy_id' => array('type' => 'primary_id'),
									       ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->term_relationships (
 object_id bigint(20) unsigned NOT NULL default 0,
 term_taxonomy_id bigint(20) unsigned NOT NULL default 0,
 term_order int(11) NOT NULL default 0,
 PRIMARY KEY  (object_id,term_taxonomy_id),
 KEY term_taxonomy_id (term_taxonomy_id)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->term_relationships => array(
										    'object_id' => array('type' => 'primary_id'),
										    ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->comments (
  comment_ID bigint(20) unsigned NOT NULL auto_increment,
  comment_post_ID bigint(20) unsigned NOT NULL default '0',
  comment_author tinytext NOT NULL,
  comment_author_email varchar(100) NOT NULL default '',
  comment_author_url varchar(200) NOT NULL default '',
  comment_author_IP varchar(100) NOT NULL default '',
  comment_date datetime NOT NULL default '0000-00-00 00:00:00',
  comment_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  comment_content text NOT NULL,
  comment_karma int(11) NOT NULL default '0',
  comment_approved varchar(20) NOT NULL default '1',
  comment_agent varchar(255) NOT NULL default '',
  comment_type varchar(20) NOT NULL default '',
  comment_parent bigint(20) unsigned NOT NULL default '0',
  user_id bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (comment_ID),
  KEY comment_approved (comment_approved),
  KEY comment_post_ID (comment_post_ID),
  KEY comment_approved_date_gmt (comment_approved,comment_date_gmt),
  KEY comment_date_gmt (comment_date_gmt)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->comments => array(
									  'comment_author' => array('type' => 'text'),
									  'comment_content' => array('type' => 'text'),
									  'comment_ID' => array('type' => 'primary_id'),
									  'comment_date' => array('type' => 'date'),
									  'comment_date_gmt' => array('type' => 'date'),
									  ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->links (
  link_id bigint(20) unsigned NOT NULL auto_increment,
  link_url varchar(255) NOT NULL default '',
  link_name varchar(255) NOT NULL default '',
  link_image varchar(255) NOT NULL default '',
  link_target varchar(25) NOT NULL default '',
  link_description varchar(255) NOT NULL default '',
  link_visible varchar(20) NOT NULL default 'Y',
  link_owner bigint(20) unsigned NOT NULL default '1',
  link_rating int(11) NOT NULL default '0',
  link_updated datetime NOT NULL default '0000-00-00 00:00:00',
  link_rel varchar(255) NOT NULL default '',
  link_notes mediumtext NOT NULL,
  link_rss varchar(255) NOT NULL default '',
  PRIMARY KEY  (link_id),
  KEY link_visible (link_visible)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->links => array(
								       'link_notes' => array('type' => 'text'),
								       'link_id' => array('type' => 'primary_id'),
								       'link_updated' => array('type' => 'date'),
								       ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->options (
  option_id bigint(20) unsigned NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  option_name varchar(64) NOT NULL default '',
  option_value longtext NOT NULL,
  autoload varchar(20) NOT NULL default 'yes',
  PRIMARY KEY  (option_id,blog_id,option_name),
  KEY option_name (option_name)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->options => array(
									 'option_value' => array('type' => 'text'),
									 'option_id' => array('type' => 'primary_id'),
									 ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->postmeta (
  meta_id bigint(20) unsigned NOT NULL auto_increment,
  post_id bigint(20) unsigned NOT NULL default '0',
  meta_key varchar(255) default NULL,
  meta_value longtext,
  PRIMARY KEY  (meta_id),
  KEY post_id (post_id),
  KEY meta_key (meta_key)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->postmeta => array(
									  'meta_value' => array('type' => 'text'),
									  'meta_id' => array('type' => 'primary_id'),
									  ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->posts (
  ID bigint(20) unsigned NOT NULL auto_increment,
  post_author bigint(20) unsigned NOT NULL default '0',
  post_date datetime NOT NULL default '0000-00-00 00:00:00',
  post_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  post_content longtext NOT NULL,
  post_title text NOT NULL,
  post_excerpt text NOT NULL,
  post_status varchar(20) NOT NULL default 'publish',
  comment_status varchar(20) NOT NULL default 'open',
  ping_status varchar(20) NOT NULL default 'open',
  post_password varchar(20) NOT NULL default '',
  post_name varchar(200) NOT NULL default '',
  to_ping text NOT NULL,
  pinged text NOT NULL,
  post_modified datetime NOT NULL default '0000-00-00 00:00:00',
  post_modified_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  post_content_filtered text NOT NULL,
  post_parent bigint(20) unsigned NOT NULL default '0',
  guid varchar(255) NOT NULL default '',
  menu_order int(11) NOT NULL default '0',
  post_type varchar(20) NOT NULL default 'post',
  post_mime_type varchar(100) NOT NULL default '',
  comment_count bigint(20) NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY post_name (post_name),
  KEY type_status_date (post_type,post_status,post_date,ID),
  KEY post_parent (post_parent)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->posts => array(
								       'ID' => array('type' => 'primary_id'),
								       'post_date' => array('type' => 'date'),
								       'post_date_gmt' => array('type' => 'date'),
								       'post_modified' => array('type' => 'date'),
								       'post_modified_gmt' => array('type' => 'date'),
								       'post_content' => array('type' => 'text'),
								       'post_title' => array('type' => 'text'),
								       'post_excerpt' => array('type' => 'text'),
								       'to_ping' => array('type' => 'text'),
								       'pinged' => array('type' => 'text'),
								       'post_content_filtered' => array('type' => 'text'),
								       ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->users (
  ID bigint(20) unsigned NOT NULL auto_increment,
  user_login varchar(60) NOT NULL default '',
  user_pass varchar(64) NOT NULL default '',
  user_nicename varchar(50) NOT NULL default '',
  user_email varchar(100) NOT NULL default '',
  user_url varchar(100) NOT NULL default '',
  user_registered datetime NOT NULL default '0000-00-00 00:00:00',
  user_activation_key varchar(60) NOT NULL default '',
  user_status int(11) NOT NULL default '0',
  display_name varchar(250) NOT NULL default '',
  PRIMARY KEY  (ID),
  KEY user_login_key (user_login),
  KEY user_nicename (user_nicename)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->users => array(
								       'ID' => array('type' => 'primary_id'),
								       'user_registered' => array('type' => 'date'),
								       ),
						 ),
		       ),
		 array(
		       'qry'=>"
CREATE TABLE $wpdb->usermeta (
  umeta_id bigint(20) unsigned NOT NULL auto_increment,
  user_id bigint(20) unsigned NOT NULL default '0',
  meta_key varchar(255) default NULL,
  meta_value longtext,
  PRIMARY KEY  (umeta_id),
  KEY user_id (user_id),
  KEY meta_key (meta_key)
) $charset_collate;",
		       'getColumnTypes' => array(
						 $wpdb->usermeta => array(
									  'umeta_id' => array('type' => 'primary_id'),
									  'meta_value' => array('type' => 'text'),
									  ),
						 ),
		       ),
		  );

    return $arr;

  }

}
?>
