<?php

require '../vendor/autoload.php';
require '../config.php';
require 'App/Router.php';

// ------------------------------------------------o Create App

$app = new \Slim\Slim();

$app->config(array(
	'view' => new \Slim\Views\Twig(),
	'log.level' => \Slim\Log::DEBUG,
	'debug' => true,
	'templates.path' => 'App/views',
    'mode' => 'production'
));

$router = new Router($app);

//$app->add(new \CheckMiddleware());

// ------------------------------------------------o Register Twig

$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());


// ------------------------------------------------o Conf

$app->get('(/)(/:params+)', function($params = array()) use ($app, $router){

	$data = $router->getData($params);
	$data['ajax'] = $app->request->isAjax();

	if ( !isset($data['route']->view) ){
		$statusCode = 404;
		if ($data['ajax'] == true){
			$statusCode = 200;
		}
		$app->render($data['viewFolder'] . '/404.html.twig', array(), $statusCode);
	}
	else {

		$app->render( $data['viewFolder'] . '/' . $data['route']->view . '.html.twig', $data);
	}

});


$app->run();