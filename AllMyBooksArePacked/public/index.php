<?
	// AUTOLOADER
	require '../vendor/autoload.php';

	// SLIM BOOTSTRAP SETUP
	require '../core/bootstrap.php';

	// EVENT HANDLERS
	require '../core/Home_controller.php';

	$app->run();
?>