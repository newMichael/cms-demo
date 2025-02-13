<?php

namespace App\Controller;

use App\Service\AuthService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AuthController
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function showLoginForm(Request $request, Response $response, $args)
	{
		$view = Twig::fromRequest($request);
		$flash = $this->container->get('flash');
		$errorMessage = $flash->getFirstMessage('login_error');
		$email = $flash->getFirstMessage('email');

		return $view->render($response, 'login.html.twig', [
			'document_title' => 'Login',
			'error_message' => $errorMessage,
			'email' => $email
		]);
	}

	public function login(Request $request, Response $response, $args)
	{
		$data = $request->getParsedBody();
		$email = $data['email'];
		$password = $data['password'];

		$authService = $this->container->get('AuthService');
		$loginSuccess = $authService->login($email, $password);
		if ($loginSuccess) {
			return $response->withHeader('Location', '/admin')->withStatus(302);
		} else {
			$this->container->get('flash')->addMessage('login_error', 'Invalid email or password');
			$this->container->get('flash')->addMessage('email', $email);
			return $response->withHeader('Location', '/admin/login')->withStatus(302);
		}
	}

	public function logout(Request $request, Response $response, $args)
	{
		$authService = $this->container->get('AuthService');
		$authService->logout();
		return $response->withHeader('Location', '/')->withStatus(302);
	}
}