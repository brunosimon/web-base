<?php

//include cockpit

require '../vendor/autoload.php';
require '../config.php';
require '../manage/bootstrap.php';
require 'utils/Utils.php';

// ------------------------------------------------o Create App

$app = new \Slim\Slim(array(
    'templates.path' => 'views/'
));

$app->get('(/)(/:params+)', function($params = array()) use ($app) {

    if (MULTILINGUAL == true){
        $lang = array_shift($params);
    }

    $collection = ucfirst(array_shift($params));
    $view = 'index';

    $posts = collection('Blog')->find(['public' => true])->toArray();

    if (count($params) >= 1){
        $path = $params[0];
        $view = 'article';
        $currentPost = array();

        foreach ($posts as $post){

            $title = ($post['title_' . $lang] != '') ? $post['title_' . $lang] : $post['title'];

            if ($path == stringToNiceUrl($title)){
                $currentPost = $post;
                break;
            }
        }

        $app->render('article.php', array('post' => $currentPost, 'lang' => $lang, 'collection' => $collection));
    }
    else {
        $app->render('index.php', array('posts' => $posts, 'lang' => $lang, 'collection' => $collection));
    }


});

$app->run();