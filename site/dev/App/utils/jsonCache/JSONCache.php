<?php

class JSONCache {

    public function __construct($app = null)
    {
        $this->app = $app;

    }

    public function generate($lang = '', $forceCache = false)
    {
        $this->noDataScriptPath = 'assets/js/script.no-data.js';
        $this->scriptPath = 'assets/js/script.js';
        $this->withDataScriptPath = 'assets/js/script.w-data.js';

        $cacheCreationDate = filemtime($this->scriptPath);
        $now = time();
        $oneWeek = 7 * 24 * 60 * 60;
        $oneMinute = 60;

        if ($now - $cacheCreationDate > $oneWeek || $forceCache == true){
            $routes = json_decode(file_get_contents('json/' . $lang . '/routes.json'));
            $cache = (object) array();

            $paths = $this->getAllPaths($routes);

            foreach($paths as $path){
                $dom = $this->getDataFromURL(ROOT_WEB . $lang . $path);
                $cache->{$path} = $dom;
            }

            $this->createCacheFile($cache);
        }

    }

    public function clear(){
        //$this->
    }

    public function getAllPaths($routes, $prePath = '')
    {   
        $paths = array();

        foreach ($routes as $key=>$route){

            $path = $prePath . '/' . $key;

            array_push($paths, $path);

             if (isset($route->childs) && !isset($route->childs->view)){
                $childPaths = $this->getAllPaths($route->childs, $path);
                $paths = array_merge($paths, $childPaths);
            }
        }

        return $paths;

    }

    public function getDataFromURL($url)
    {   
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
        curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $output = curl_exec($ch); 
        curl_close($ch);  

        return $output;
    }

    public function createCacheFile($cache)
    {      
        if (!file_exists(realpath($this->noDataScriptPath))){
            $script = file_get_contents($this->scriptPath);
            $noDataScriptFile = fopen( $this->noDataScriptPath, "w");
            fwrite($noDataScriptFile, $script);
            fclose($noDataScriptFile);
        }

        $script = file_get_contents($this->noDataScriptPath);
        $file = fopen( $withDataScriptPath, "w");
        fwrite($file, 'var CACHE = ' . json_encode($cache) . ';' . $script);
        fclose($file);
    }

}