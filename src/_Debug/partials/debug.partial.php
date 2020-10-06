<?php

require dirname(__FILE__) . "/../../Models/BasicModel.php";


$text = '<b>Hallo</b><script >alert("test");</script>';
$text = BasicModel::strip_all_tags_from_text($text);

$array = array(
    '<b>Hallo</b><script >alert("test");</script>',
    '<b>Hallo</b><script >alert("test");</script>',
    '<b>Hallo</b><script >alert("test");</script>'
);
$array = BasicModel::strip_all_tags_from_array($array);

$object = (object)array(
    "str1" => '<b>Hallo</b><script >alert("test");</script>',
    "str2" => '<b>Hallo</b><script >alert("test");</script>',
);
$object = BasicModel::strip_all_tags($object);


echo $text;

print_r($array);

print_r($object);
