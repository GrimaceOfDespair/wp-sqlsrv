<?php return array (
  '' => 
  array (
  ),
  'wp_users' => 
  array (
    'ID' => 
    array (
      'type' => 'primary_id',
    ),
    'display_name' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_activation_key' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_email' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_login' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_nicename' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_pass' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_registered' => 
    array (
      'type' => 'date',
    ),
    'user_status' => 
    array (
      'type' => 'int',
    ),
    'user_url' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_usermeta' => 
  array (
    'meta_key' => 
    array (
      'type' => 'nvarchar',
    ),
    'meta_value' => 
    array (
      'type' => 'nvarchar',
    ),
    'umeta_id' => 
    array (
      'type' => 'primary_id',
    ),
    'user_id' => 
    array (
      'type' => 'int',
    ),
  ),
  'wp_terms' => 
  array (
    'name' => 
    array (
      'type' => 'nvarchar',
    ),
    'slug' => 
    array (
      'type' => 'nvarchar',
    ),
    'term_group' => 
    array (
      'type' => 'int',
    ),
    'term_id' => 
    array (
      'type' => 'primary_id',
    ),
  ),
  'wp_term_taxonomy' => 
  array (
    'count' => 
    array (
      'type' => 'int',
    ),
    'description' => 
    array (
      'type' => 'nvarchar',
    ),
    'parent' => 
    array (
      'type' => 'int',
    ),
    'taxonomy' => 
    array (
      'type' => 'nvarchar',
    ),
    'term_id' => 
    array (
      'type' => 'int',
    ),
    'term_taxonomy_id' => 
    array (
      'type' => 'primary_id',
    ),
  ),
  'wp_term_relationships' => 
  array (
    'object_id' => 
    array (
      'type' => 'primary_id',
    ),
    'term_order' => 
    array (
      'type' => 'int',
    ),
    'term_taxonomy_id' => 
    array (
      'type' => 'int',
    ),
  ),
  'wp_commentmeta' => 
  array (
    'comment_id' => 
    array (
      'type' => 'int',
    ),
    'meta_id' => 
    array (
      'type' => 'primary_id',
    ),
    'meta_key' => 
    array (
      'type' => 'nvarchar',
    ),
    'meta_value' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_comments' => 
  array (
    'comment_ID' => 
    array (
      'type' => 'primary_id',
    ),
    'comment_agent' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_approved' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_author' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_author_IP' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_author_email' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_author_url' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_content' => 
    array (
      'type' => 'nvarchar',
    ),
    'comment_date' => 
    array (
      'type' => 'date',
    ),
    'comment_date_gmt' => 
    array (
      'type' => 'date',
    ),
    'comment_karma' => 
    array (
      'type' => 'int',
    ),
    'comment_parent' => 
    array (
      'type' => 'int',
    ),
    'comment_post_ID' => 
    array (
      'type' => 'int',
    ),
    'comment_type' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_id' => 
    array (
      'type' => 'int',
    ),
  ),
  'wp_links' => 
  array (
    'link_description' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_id' => 
    array (
      'type' => 'primary_id',
    ),
    'link_image' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_name' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_notes' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_owner' => 
    array (
      'type' => 'int',
    ),
    'link_rating' => 
    array (
      'type' => 'int',
    ),
    'link_rel' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_rss' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_target' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_updated' => 
    array (
      'type' => 'date',
    ),
    'link_url' => 
    array (
      'type' => 'nvarchar',
    ),
    'link_visible' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_options' => 
  array (
    'autoload' => 
    array (
      'type' => 'nvarchar',
    ),
    'option_id' => 
    array (
      'type' => 'primary_id',
    ),
    'option_name' => 
    array (
      'type' => 'nvarchar',
    ),
    'option_value' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_postmeta' => 
  array (
    'meta_id' => 
    array (
      'type' => 'primary_id',
    ),
    'meta_key' => 
    array (
      'type' => 'nvarchar',
    ),
    'meta_value' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_id' => 
    array (
      'type' => 'int',
    ),
  ),
  'wp_posts' => 
  array (
    'ID' => 
    array (
      'type' => 'primary_id',
    ),
    'comment_count' => 
    array (
      'type' => 'int',
    ),
    'comment_status' => 
    array (
      'type' => 'nvarchar',
    ),
    'guid' => 
    array (
      'type' => 'nvarchar',
    ),
    'menu_order' => 
    array (
      'type' => 'int',
    ),
    'ping_status' => 
    array (
      'type' => 'nvarchar',
    ),
    'pinged' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_author' => 
    array (
      'type' => 'int',
    ),
    'post_content' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_content_filtered' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_date' => 
    array (
      'type' => 'date',
    ),
    'post_date_gmt' => 
    array (
      'type' => 'date',
    ),
    'post_excerpt' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_mime_type' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_modified' => 
    array (
      'type' => 'date',
    ),
    'post_modified_gmt' => 
    array (
      'type' => 'date',
    ),
    'post_name' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_parent' => 
    array (
      'type' => 'int',
    ),
    'post_password' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_status' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_title' => 
    array (
      'type' => 'nvarchar',
    ),
    'post_type' => 
    array (
      'type' => 'nvarchar',
    ),
    'to_ping' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_blogs' => 
  array (
    'archived' => 
    array (
      'type' => 'int',
    ),
    'blog_id' => 
    array (
      'type' => 'primary_id',
    ),
    'deleted' => 
    array (
      'type' => 'int',
    ),
    'domain' => 
    array (
      'type' => 'nvarchar',
    ),
    'lang_id' => 
    array (
      'type' => 'int',
    ),
    'last_updated' => 
    array (
      'type' => 'date',
    ),
    'mature' => 
    array (
      'type' => 'int',
    ),
    'path' => 
    array (
      'type' => 'nvarchar',
    ),
    'public' => 
    array (
      'type' => 'int',
    ),
    'registered' => 
    array (
      'type' => 'date',
    ),
    'site_id' => 
    array (
      'type' => 'int',
    ),
    'spam' => 
    array (
      'type' => 'int',
    ),
  ),
  'wp_blog_versions' => 
  array (
    'blog_id' => 
    array (
      'type' => 'primary_id',
    ),
    'db_version' => 
    array (
      'type' => 'nvarchar',
    ),
    'last_updated' => 
    array (
      'type' => 'date',
    ),
  ),
  'wp_registration_log' => 
  array (
    'ID' => 
    array (
      'type' => 'primary_id',
    ),
    'IP' => 
    array (
      'type' => 'nvarchar',
    ),
    'blog_id' => 
    array (
      'type' => 'int',
    ),
    'date_registered' => 
    array (
      'type' => 'date',
    ),
    'email' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_site' => 
  array (
    'domain' => 
    array (
      'type' => 'nvarchar',
    ),
    'id' => 
    array (
      'type' => 'primary_id',
    ),
    'path' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_sitemeta' => 
  array (
    'meta_id' => 
    array (
      'type' => 'primary_id',
    ),
    'meta_key' => 
    array (
      'type' => 'nvarchar',
    ),
    'meta_value' => 
    array (
      'type' => 'nvarchar',
    ),
    'site_id' => 
    array (
      'type' => 'int',
    ),
  ),
  'wp_signups' => 
  array (
    'activated' => 
    array (
      'type' => 'date',
    ),
    'activation_key' => 
    array (
      'type' => 'nvarchar',
    ),
    'active' => 
    array (
      'type' => 'int',
    ),
    'domain' => 
    array (
      'type' => 'nvarchar',
    ),
    'meta' => 
    array (
      'type' => 'nvarchar',
    ),
    'path' => 
    array (
      'type' => 'nvarchar',
    ),
    'registered' => 
    array (
      'type' => 'date',
    ),
    'signup_id' => 
    array (
      'type' => 'primary_id',
    ),
    'title' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_email' => 
    array (
      'type' => 'nvarchar',
    ),
    'user_login' => 
    array (
      'type' => 'nvarchar',
    ),
  ),
  'wp_users ADD KEY user_login_key' => 
  array (
  ),
  'wp_users ADD KEY user_nicename' => 
  array (
  ),
  'wp_usermeta ADD KEY user_id' => 
  array (
  ),
  'wp_usermeta ADD KEY meta_key' => 
  array (
  ),
  'wp_blogs ADD KEY domain' => 
  array (
  ),
  'wp_blogs ADD KEY lang_id' => 
  array (
  ),
  'wp_blog_versions ADD KEY db_version' => 
  array (
  ),
  'wp_registration_log ADD KEY IP' => 
  array (
  ),
  'wp_site ADD KEY domain' => 
  array (
  ),
  'wp_sitemeta ADD KEY meta_key' => 
  array (
  ),
  'wp_sitemeta ADD KEY site_id' => 
  array (
  ),
  'wp_signups ADD KEY activation_key' => 
  array (
  ),
  'wp_signups ADD KEY user_email' => 
  array (
  ),
  'wp_signups ADD KEY user_login_email' => 
  array (
  ),
  'wp_signups ADD KEY domain_path' => 
  array (
  ),
)
 ?>