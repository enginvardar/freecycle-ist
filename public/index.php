<?php
session_start();
mb_internal_encoding("UTF-8");
require '../vendor/autoload.php';

$basePath = dirname(dirname(__FILE__));
$loader = new Composer\Autoload\ClassLoader();
$loader->add('Home', $basePath.'/lib');
$loader->add('Admin', $basePath.'/lib');
$loader->add('Config', $basePath.'/lib');
$loader->register();

$parsedUrl = parse_url($_SERVER['HTTP_HOST']);
$host = explode('.', $parsedUrl['path']);
$subdomain = strtolower($host[0]);

switch ($subdomain) {
	case 'admin':
		$directory = "Admin";
		break;
	case 'www':
		$directory = "Home";
		break;
	default:
		$directory = "Home";
		break;
}
$app = new \Slim\Slim(array(
    'templates.path' => "$basePath/templates",
    'cookies.secret_key'  => '+eLZXU5uR/m_pe+b9`UI636+}T`D_5M9y#;|cK?%KK+ApP5i4a8[Oz]pZ[m7>Iwf',
	'cookies.lifetime' => time() + (1 * 24 * 60 * 60),
	'cookies.cipher' => MCRYPT_RIJNDAEL_256,
	'cookies.cipher_mode' => MCRYPT_MODE_CBC,
	'cookies.encrypt' => true,
));

$app->view(new \Slim\Views\Twig());
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath("$basePath/templates/cache"),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
);


if($_SERVER['APP_ENV'] == 'development'){
	$app->config(array(
		"debug" => true,
	));
}
define("TEMPLATE_PREFIX",strtolower($directory));

require_once $basePath."/lib/$directory/methods.php";
require_once strtolower($directory).".php";