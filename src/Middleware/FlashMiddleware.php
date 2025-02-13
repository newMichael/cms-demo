<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class FlashMiddleware
{
	protected $view;

	public function __construct(Twig $view)
	{
		$this->view = $view;
	}

	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$flash = $_SESSION['flash_message'] ?? null;
		if ($flash) {
			$this->view->getEnvironment()->addGlobal('flash_message', $flash);
			unset($_SESSION['flash_message']);
		}

		return $handler->handle($request);
	}
}
