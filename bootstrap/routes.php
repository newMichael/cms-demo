<?php
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use App\Controller\AuthController;
use App\Controller\PageController;

return function ($app) {
	// temporarily redirect to admin
	$app->get('/', function (Request $request, Response $response, $args) {
		return $response->withHeader('Location', '/admin')->withStatus(302);
	});

	$app->get('/admin/login', [AuthController::class, 'showLoginForm'])->setName('login');
	$app->post('/admin/login', [AuthController::class, 'login']);
	$app->get('/admin/logout', [AuthController::class, 'logout'])->setName('logout');
	$app->get('/admin/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->setName('forgot-password');
	$app->post('/admin/forgot-password', [AuthController::class, 'forgotPassword']);
	$app->get('/admin/reset-password', [AuthController::class, 'showResetPasswordForm'])->setName('reset-password');
	$app->post('/admin/reset-password', [AuthController::class, 'resetPassword']);
 
	$app->group('/admin', function ($app) {
		$app->get('', function (Request $request, Response $response, $args) {
			$view = Twig::fromRequest($request);
			return $view->render($response, 'dashboard.html.twig', [
				'document_title' => 'CMS',
				'current_route' => 'dashboard'
			]);
		})->setName('dashboard');

		$app->get('/pages', [PageController::class, 'index'])->setName('pages');
		$app->post('/pages', [PageController::class, 'createNew']);
		$app->get('/pages/{id}', [PageController::class, 'showEditPage'])->setName('edit-page');
	})->add(new \App\Middleware\AuthMiddleware($app->getContainer()));
};
