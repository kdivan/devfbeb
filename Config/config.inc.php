<?php
/**
 * Created by PhpStorm.
 * User: Divan
 * Date: 21/04/2015
 * Time: 12:17
 */

/**
 * Check is local
 */
define('IS_LOCAL',($_SERVER['REMOTE_ADDR']=='127.0.0.1' ||$_SERVER['REMOTE_ADDR']=='::1' ));

if(IS_LOCAL) {
    /**
     * Database Local Configuration
     */
    define('DB_TYPE', 'pgsql');
    define('DB_HOST', 'ec2-54-228-227-217.eu-west-1.compute.amazonaws.com');
    define('DB_NAME', 'dfcu88d9rcvqjg');
    define('DB_USER', 'uvewrtiishknof');
    define('DB_PASSWORD', 'HnT5nP1aUnrZXmNuC8PTplZog0');
    define('SERVER_NAME','http://localhost/devfbeb/');
    define('FB_APPID', '384038925102491');
    define('FB_APPSECRET', '68283b74324f5ebf5a5ee4ccd87b43e4');
}else {
    /**
     * Database Serveur Configuration
     */
    define('DB_TYPE', 'pgsql');
    define('DB_HOST', 'ec2-54-228-227-217.eu-west-1.compute.amazonaws.com');
    define('DB_NAME', 'dfcu88d9rcvqjg');
    define('DB_USER', 'uvewrtiishknof');
    define('DB_PASSWORD', 'HnT5nP1aUnrZXmNuC8PTplZog0');
    define('SERVER_NAME','http://devfbeb1.herokuapp.com/');
    define('FB_APPID', '811641295596575');
    define('FB_APPSECRET', '94b582458ecdac47af61076e46ea7e3d');
}

/**
 * DB Common configuration
 */
define('DB_STRING','pgsql:host=ec2-54-228-227-217.eu-west-1.compute.amazonaws.com;dbname=dfcu88d9rcvqjg');
define('DB_PREFIX','fb_');

/**
 * Facebook configuration
 */
define('FB_RIGHTS','public_profile,email,user_photos,publish_actions');


/**
 * General App configuration
 */
define('MAX_ELEM_PER_PAGE',12);
define('MAX_IMAGE_PER_LINE',4);
define('DIALOG_WIDTH','');
define('DIALOG_HEIGHT','');

define('PHOTO_WIDTH','150px');
define('PHOTO_HEIGHT','100px');
define('ALBUM_WIDTH','100px');
define('ALBUM_HEIGHT','100px');