<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class NotFoundMiddleware
{
	protected $twig;

	public function __construct(Twig $twig)
	{
		$this->twig = $twig;
	}

	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		try {
			return $handler->handle($request);
		} catch (HttpNotFoundException $e) {
			$response = new \Slim\Psr7\Response();
			return $this->twig->render($response->withStatus(404), '404.html.twig');
		}
	}
}
