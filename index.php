<?php

include_once 'ReadAPI.php';


$url = (isset($_GET['url'])) ? $_GET['url'] : 'http://dev.medialab.ufg.br/ibram/wp-json/tainacan/v1';
$class = new ReadAPI($url);
$collections = $class->readCollectionsPublished();
$dir = dirname(__FILE__).'/files/';
$class->recursiveRemoveDirectory($dir);
mkdir($dir);

$class->generate_repository_metadata($dir);
$class->generate_repository_settings($dir);
//iterando sobre todas as colecoes
if(is_array($collections)){
    foreach ($collections as $collection) {
        $dir_collection = $dir.$collection['ID'].'/';
        $class->recursiveRemoveDirectory($dir_collection);
        if(!is_dir($dir_collection)){
            mkdir($dir_collection);
        }
        $class->generate_single_collection_file($dir_collection,$collection['ID']);
        $class->generate_metadata_collection_file($dir_collection,$collection['ID']);
    }
}

