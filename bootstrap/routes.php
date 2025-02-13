<?php
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use App\Controller\AuthController;
use App\Controller\PageController;

return function ($app) {
	$app->get('/', function (Request $request, Response $response, $args) {
		return $response->withHeader('Location', '/admin')->withStatus(302);
	});

	$app->get('/admin/login', [AuthController::class, 'showLoginForm'])->setName('login');
	$app->post('/admin/login', [AuthController::class, 'login']);
	$app->get('/admin/logout', [AuthController::class, 'logout'])->setName('logout');

	$app->group('/admin', function ($app) {
		$app->get('', function (Request $request, Response $response, $args) {
			$view = Twig::fromRequest($request);
			return $view->render($response, 'dashboard.html.twig', [
				'document_title' => 'CMS',
				'current_route' => 'dashboard'
			]);
		})->setName('dashboard');

		$app->get('/pages', [PageController::class, 'index'])->setName('pages');
	})->add(new \App\Middleware\AuthMiddleware($app->getContainer()));

	// $app->get('/password', function (Request $request, Response $response, $args) {
	// 	$hash = password_hash('password', PASSWORD_DEFAULT);
	// 	$response->getBody()->write($hash);
	// 	return $response;
	// });
};
