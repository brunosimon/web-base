<?php

class Router {

    public function __construct($app = null)
    {
        $this->app = $app;

    }

    public function getData($params = null)
    {
        $this->rootPath = '/';
        $this->params = $params;
        $data = array();

        if (MULTILINGUAL){
            $urlLang = (count($this->params) == 0) ? null : $this->params[0];
            $this->lang = $this->getLang();

            if ($urlLang == null || $this->lang != $urlLang){
                $this->app->redirect($this->rootPath);
            }
        }

        $viewFolder = $this->getViewFolder();

        if ($viewFolder == 'old'){
            if (count($this->params) > 0){
                $this->app->redirect(   $this->lang);
            }
            $route = (object) array('view' => 'index', 'type' => 'old');
        }
        else {
            $route = $this->getRoute();
        }

        $global = $this->getGlobalData();
        $content = $this->getContent($route);

        $this->data = array(
            'content' => $content,
            'global' => $global,
            'route' => $route,
            'viewFolder' => $viewFolder
        );

        if (isset($this->lang)){
            $this->data['lang'] = $this->lang;
        }

        $this->setGlobalVariables();

        return $this->data;
    }

    public function getLang()
    {      
        $lang = (count($this->params) == 0) ? $this->getUserLang() : $this->params[0];

        if (!file_exists('json/' . $lang)){
            echo 'geagae';
            $lang = DEFAULT_LANGUAGE;
        }

        array_shift($this->params);

        $this->rootPath .= $lang . '/';

        return $lang;
    } 

    public function getUserLang()
    {
        $langs = array();

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
            
            if (count($lang_parse[1])) {
                $langs = array_combine($lang_parse[1], $lang_parse[4]);
                $tmp = array();
                foreach ($langs as $lang => $val) {
                    if (MULTILINGUAL_EXTENDED == true){
                        if ( strlen($lang) == 5 ){
                            $lang = substr($lang, 0,2) .'-'. strtoupper( substr($lang, 3,2) );
                            $tmp[$lang] = $val;
                        }
                    }
                    else {
                        $lang = substr($lang, 0,2);
                        $tmp[$lang] = $val;
                    }
                    if ($val === '') $tmp[$lang] = 1;
                }
                $langs = $tmp;
                arsort($langs, SORT_NUMERIC);
            }

        }

        return current(array_keys($langs));

    }

    public function getGlobalData()
    {
        $json = (object) array();
        if (file_exists("json" . $this->rootPath . "/global.json")){
            $json = json_decode(file_get_contents("json" . $this->rootPath . "/global.json"));
        }
        return $json;
    }

    public function getRoute()
    {
        $routes = json_decode(file_get_contents("json" . $this->rootPath . "/routes.json"));

        $route = $routes;
        $service = '';
        $path = '';
        $content = array();
        $incRoute = 0;

        // ---o If it's home, return index template
        if (count($this->params) == 0){
            return (object) array('view' => 'home');
        }

        foreach ($this->params as $key=>$item){
            if (isset($route->{$item})){
                $route = $route->{$item};
            }
            else if (isset($route->childs)){
                $route = $route->childs;
            }
            else {
                $incRoute++;
            }
        }

        return $route;

    }

    public function getContent($route)
    {

        $path = '';
        $content = (object) array();

        if (!isset($route->view)){
            $path = "json" . $this->rootPath . "404.json";
        }
        else {
            if (isset($route->type) && $route->type == 'old'){
                $path =  "json" . $this->rootPath . "old.json";
            } else {
                $routeParams = $this->params;
                $longPath = "json" . $this->rootPath . implode('/', $routeParams) ."/content.json";
                $filename = array_pop($routeParams);
                $shortPath = "json" . $this->rootPath . implode('/', $routeParams) . "/" . $filename . ".json";

                if (isset($route->service) && $route->service != ''){
                    if ($route->service == '::'){
                        $path = SERVICES . '/';
                        if (MULTILINGUAL){
                            $path .= $this->lang . '/';
                        }
                        $path .= implode('/', $this->params);
                        $content->data = json_decode($this->getDataFromURL($path)); 
                    }
                }

                if (file_exists($longPath)) {
                    $path = $longPath;
                }
                else if (file_exists($shortPath)) {
                    $path = $shortPath;
                }
            }
        }

        if ($path != '' && file_exists($path)){
            $content = (object) array_merge( (array) json_decode( file_get_contents($path)), (array) $content );           
        }

        return $content;
    }

    public function getViewFolder()
    {

        $viewFolder = 'desktop';


        if(preg_match('/(?i)msie [1-8]/', $_SERVER['HTTP_USER_AGENT']) /*|| strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false*/){
            $viewFolder = 'old';
        }
        else {
            $detect = new Mobile_Detect;
            $isMobile = $detect->isMobile();
            $isTablet = $detect->isTablet();

            if (MOBILE_SUPPORT && $isMobile && !$isTablet) {
                $viewFolder = 'mobile';
            }
            else if (TABLET_SUPPORT && $isTablet) {
                $viewFolder = 'tablet';
            }
        }

        return $viewFolder;

    }

    public function setGlobalVariables()
    {
        $title = isset($this->data['content']->meta->title) ? $this->data['content']->meta->title : $this->data['global']->meta->title;
        $description = isset($this->data['content']->meta->description) ? $this->data['content']->meta->description : $this->data['global']->meta->description;

        $this->data['title'] = $title;
        $this->data['description'] = $description;
        $this->data['domain'] = DOMAIN;
        $this->data['root_web'] = ROOT_WEB;

    }

    public function getDataFromURL($url)
    {   
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);  

        return $output;
    }

}