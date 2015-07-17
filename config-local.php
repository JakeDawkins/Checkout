<?php

/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/

$config = array(
    "db" => array(
        "gear" => array(
            "dbname" => "checkout",
            "username" => "root",
            "password" => "",
            "host" => "localhost"
        ),
        "personal" => array(
            "dbname" => "jakedawk_db",
            "username" => "jakedawk_primary",
            "password" => "4NCnERfrKCe9KQfmdLECVfhRbFsBNamx",
            "host" => "localhost"
        )
    ),
    "urls" => array(
        "baseUrl" => "http://jakedawkins.com",
        "devUrl" => "http://dev.jakedawkins.com"
    ),
    "paths" => array(
        "resources" => "/home/jakedawkins/resources", //or /resources
        "images" => array(
            "content" => "/home/jakedawkins/dev/img/content", //$_SERVER["DOCUMENT_ROOT"] . "/img/content",
            "layout" => "/home/jakedawkins/dev/img/layout" //$_SERVER["DOCUMENT_ROOT"] . "/img/layout"
        )
    ),
);

//ini_set("error_reporting", "true");
//error_reporting(E_ALL|E_STRCT);

?>
