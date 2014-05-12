<?php
$app->map('/', function() use ($app) {
	$methods = new Home\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->map('/About', function() use ($app) {
	$methods = new Home\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->map('/Press', function() use ($app) {
	$methods = new Home\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->map('/Contact', function() use ($app) {
	$methods = new Home\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->run();