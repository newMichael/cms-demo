<?php

namespace App\Controller;

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
		$infoMessage = $flash->getFirstMessage('login_message');
		$errorMessage = $flash->getFirstMessage('login_error');
		$email = $flash->getFirstMessage('email');

		return $view->render($response, 'login.html.twig', [
			'document_title' => 'Login',
			'info_message' => $infoMessage,
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

	public function showForgotPasswordForm(Request $request, Response $response, $args)
	{
		$view = Twig::fromRequest($request);
		$flash = $this->container->get('flash');
		$errorMessage = $flash->getFirstMessage('forgot_password_error');
		$infoMessage = $flash->getFirstMessage('forgot_password_message');
		$email = $flash->getFirstMessage('email');

		return $view->render($response, 'forgot-password.html.twig', [
			'document_title' => 'Forgot Password',
			'error_message' => $errorMessage,
			'info_message' => $infoMessage,
			'email' => $email
		]);
	}

	public function forgotPassword(Request $request, Response $response, $args)
	{
		$data = $request->getParsedBody();
		$email = $data['email'];
		$authService = $this->container->get('AuthService');
		$authService->forgotPassword($email);
		$message = "If the email address you entered is associated with an account, you will receive an email with instructions on how to reset your password.";
		$this->container->get('flash')->addMessage('forgot_password_message', $message);
		return $response->withHeader('Location', '/admin/forgot-password')->withStatus(302);
	}

	public function showResetPasswordForm(Request $request, Response $response, $args)
	{
		$token = $request->getQueryParams()['token'];
		if (empty($token)) {
			$this->container->get('flash')->addMessage('forgot_password_error', 'Invalid reset password URL');
			return $response->withHeader('Location', '/admin/forgot-password')->withStatus(302);
		}

		$authService = $this->container->get('AuthService');
		$isValidToken = $authService->validateResetPasswordToken($token);
		if (!$isValidToken) {
			$this->container->get('flash')->addMessage('forgot_password_error', 'Invalid reset password URL');
			return $response->withHeader('Location', '/admin/forgot-password')->withStatus(302);
		}

		$view = Twig::fromRequest($request);
		$flash = $this->container->get('flash');
		$errorMessage = $flash->getFirstMessage('reset_password_error');

		return $view->render($response, 'reset-password.html.twig', [
			'document_title' => 'Reset Password',
			'error_message' => $errorMessage,
			'token' => $token
		]);
	}

	public function resetPassword(Request $request, Response $response, $args)
	{
		$data = $request->getParsedBody();
		$token = $data['token'];
		$password = $data['password'];
		$confirmPassword = $data['confirm_password'];

		$authService = $this->container->get('AuthService');
		try {
			$authService->handleResetPassword($token, $password, $confirmPassword);
		} catch (\InvalidArgumentException $e) {
			$this->container->get('flash')->addMessage('forgot_password_error', $e->getMessage());
			return $response->withHeader('Location', '/admin/forgot-password')->withStatus(302);
		} catch (\Exception $e) {
			$this->container->get('flash')->addMessage('reset_password_error', $e->getMessage());
			return $response->withHeader('Location', '/admin/reset-password?token=' . $token)->withStatus(302);
		}

		$this->container->get('flash')->addMessage('login_message', 'Password reset successfully. Please login.');
		return $response->withHeader('Location', '/admin/login')->withStatus(302);
	}
}