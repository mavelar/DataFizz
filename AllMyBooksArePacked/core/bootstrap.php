<?
	session_start();

	$app = new \Slim\Slim(array(
		'mode' => 'development',
		'templates.path' => '../templates',
		'view' => new \Slim\Views\Twig()
	));

	$app->setName('rctest');

	$view = $app->view();
	$view->parserOptions = array(
		'debug' => true
	  // 'cache' => dirname(__FILE__) . '/cache'
	);
	$view->parserExtensions = array(
		new \Slim\Views\TwigExtension(),
	);

		// PRODUCTION
	$app->configureMode('production', function () use ($app) {
		$app->config(array(
			'log.enable' => true,
			'debug' => false
			));
	});

		// DEVELOPMENT
	$app->configureMode('development', function () use ($app) {
		$app->config(array(
			'log.enable' => true,
			'debug' => true
			));
	});

	// 404
	$app->notFound(function () use ($app) {
    $app->render('404.html.twig');
	});

	// Error
	$app->error(function (\Exception $e) use ($app) {
    $app->render('error.php');
	});
?>