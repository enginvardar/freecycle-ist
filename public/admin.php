<?php
$app->map('/', function() use ($app) {
	$methods = new Admin\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->map('/Users(/:method)(/:id)', function($method=false,$id=false) use ($app) {
	$methods = new Admin\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->map('/Pages(/:method)(/:id)', function($method=false,$id=false) use ($app) {
	$methods = new Admin\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->map('/Messages(/:method)(/:id)', function($method=false,$id=false) use ($app) {
	$methods = new Admin\methods();
	$data = $methods->home($app);
	$template = TEMPLATE_PREFIX.".".$data['template'];
	$app->render($template,$data);
})->via('GET','POST');

$app->run();