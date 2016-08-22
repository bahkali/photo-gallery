<?php


$db['db_server']= "localhost";
$db['db_user']= "root";
$db['db_pass']= "";
$db['db_name']= "photo_gallery";

foreach($db as $key => $value)
{
    define(strtoupper($key), $value);
}

