<?php

class ReadAPI{

    public $url;

    function __construct($url){
        $this->url = $url;
    }

    /**
     * Metodo que retorno as colecoes publicadas
     *
     * @return array
     */
    public function readCollectionsPublished():Array{
        $qry_str = "/collections";
        $ch = curl_init();
        // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, $this->url. $qry_str);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        return (is_array(json_decode($content, true))) ? json_decode($content, true) : [];
    }

    /**
     * @param $dir
     * @param $id
     */
    public function generate_single_collection_file($dir,$id){
        $qry_str = "/collections/".$id.'?includeMetadata=1';
        $ch = curl_init();
        // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, $this->url. $qry_str);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        $this->generate_json_file($content,$dir.'filters.json');
    }

    /**
     * @param $dir
     * @param $id
     */
    public function generate_metadata_collection_file($dir,$id){
        $qry_str = "/collections/".$id.'/metadata?includeMetadata=1';
        $ch = curl_init();
            // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, $this->url. $qry_str);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        $this->generate_json_file($content,$dir.'metadata.json');
    }

    /**
     *
     */
    public function generate_repository_metadata($dir){
        $qry_str = '/repository/metadata?includeMetadata=1';
        $ch = curl_init();
        // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, $this->url. $qry_str);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        $this->generate_json_file($content,$dir.'repository-metadata.json');
    }

    public function generate_repository_settings($dir){
        $qry_str = '/repository?includeMetadata=1';
        $ch = curl_init();
        // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, $this->url. $qry_str);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $content = trim(curl_exec($ch));
        curl_close($ch);
        $this->generate_json_file($content,$dir.'repository-settings.json');
    }

    /**
     * @param $json
     * @param $name_file
     */
    public function generate_json_file($json,$name_file) {
        ob_start();
        ob_end_clean();
        $df = fopen($name_file, 'w');
        fputs($df, $json);
        fclose($df);
    }

    /**
     *
     */
    public function recursiveRemoveDirectory($directory) {
        if (is_dir($directory)) {
            //foreach (glob("{$directory}/{,.}*", GLOB_BRACE) as $file) {
            //foreach (glob("{$directory}/*") as $file) {
            foreach (glob("{$directory}/{,.}[!.,!..]*",GLOB_MARK|GLOB_BRACE) as $file) {
                if (is_dir($file)) {
                    $this->recursiveRemoveDirectory($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($directory);
        }
    }
}