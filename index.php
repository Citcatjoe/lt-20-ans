<?php
// Config
require_once __DIR__ . '/vendor/autoload.php';

$dirs  = array();
$dirs[] = __DIR__;
$dirs[] = __DIR__ . '/templates';
if (is_dir( __DIR__ . '/../../_admin/templates')) {
	$dirs[] =  __DIR__ . '/../../_admin/templates';
}

if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== FALSE) {
	$dirs[] = __DIR__ . '/../web/_admin/templates';
}

$loader = new Twig_Loader_Filesystem($dirs);
$twig = new Twig_Environment($loader);

// Variables
$variables = array();
if (file_exists(__DIR__ . '/variables.json')) {
	$variables = json_decode(file_get_contents(__DIR__ . '/variables.json'), TRUE);
}

// Application variables (as URL and more)
$app = new stdClass();

// @TODO Secure it !!!
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

$app->url = 'https://' . $host . $uri;
$app->base_url = 'https://' . $host;
$app->base_path = rtrim(parse_url($uri, PHP_URL_PATH), '/');
$variables = array_merge($variables, array('app' => $app));


// Si plusieurs pages
$template = '00.html.twig';
if (isset($_GET['page']) && !empty($_GET['page'])) {
	if (file_exists(__DIR__ . '/' . $_GET['page'] . '.html.twig')) {
		$template = $_GET['page'] . '.html.twig';
		$variables = array_merge($variables, array('active_page' => $_GET['page']));
	}
}

// Afficher le template
echo $twig->render($template, $variables);