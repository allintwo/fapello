<?php
// last update - 29-4-2022

global $jal_db_version;
$jal_db_version = '1.0';

function fapello_create_extra_table() {
    global $wpdb;
    global $jal_db_version;

    $table_prefix = $wpdb->prefix;

    $charset_collate = $wpdb->get_charset_collate();


    $sqls = [
        "CREATE TABLE IF NOT EXISTS `fape_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comments` varchar(511) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `ckey` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`)
) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS `fape_follower` (
  `model_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `model_id` (`model_id`),
  KEY `user_id` (`user_id`)
) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS `fape_likes` (
  `media_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `time` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  KEY `media_id` (`media_id`),
  KEY `user_id` (`user_id`)
) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS `fape_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`)
) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS `fape_model` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `ttl_likes` int(11) NOT NULL,
  `ttl_follows` int(11) NOT NULL,
  `ttl_views` int(11) NOT NULL,
  `ttl_comments` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS `fape_url_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) $charset_collate;"
    ];

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    foreach ($sqls as  $sql)
    {
        dbDelta( $sql );
    }

    add_option( 'jal_db_version', $jal_db_version );
}


