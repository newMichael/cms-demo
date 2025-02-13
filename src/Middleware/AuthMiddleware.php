<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Container\ContainerInterface;

class AuthMiddleware
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$authService = $this->container->get('AuthService');
		if (!$authService->isAuthenticated()) {
			$response = new \Slim\Psr7\Response();
			return $response->withHeader('Location', '/admin/login')->withStatus(302);
		}

		return $handler->handle($request);
	}
}