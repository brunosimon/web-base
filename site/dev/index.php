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

	if ( !isset($data['route']->view) ){
		$app->response->setStatus(404);
		$app->render($data['device'] . '/404.html.twig', array(), 404);
	}
	else {

		$data['ajax'] = $app->request->isAjax();

		$app->render( $data['device'] . '/' . $data['route']->view . '.html.twig', $data);
	}

	//

	//$app->render($data);
});

/*$app->error('', function(){
	// Pour setter le statut de la 404 à 200 si Ajax
	$app->response->setStatus(400);
});*/


$app->run();