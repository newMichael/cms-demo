<?php

namespace App\Service;

use PDO;
use PHPMailer\PHPMailer\PHPMailer;

class AuthService
{
	protected $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function isAuthenticated(): bool
	{
		return isset($_SESSION['cms']);
	}

	public function login($email, $password): bool
	{
		$user = $this->getUserFromEmail($email);

		if ($user && password_verify($password, $user['password'])) {
			$_SESSION['cms'] = [
				'user_id' => $user['user_id'],
				'email' => $user['email']
			];
			return true;
		}

		return false;
	}

	public function logout()
	{
		if (isset($_SESSION['cms'])) {
			unset($_SESSION['cms']);
		}
	}

	public function forgotPassword(string $email)
	{
		$user = $this->getUserFromEmail($email);
		if ($user) {
			$token = $this->generatePasswordResetToken();
			$this->storePasswordResetToken($user, $token);
			
			// TODO: consider how PHPMailer is instantiated
			$mailService = new MailService(new PHPMailer());
			$mailService->sendPasswordResetEmail($email, $token);
		}
	}

	public function handleResetPassword($token, $password, $confirmPassword)
	{
		$isValidToken = $this->validateResetPasswordToken($token);
		if (!$isValidToken) {
			throw new \InvalidArgumentException('Invalid reset password URL');
		}

		if (strlen($password) < 8) {
			throw new \Exception('Password must be at least 8 characters');
		}

		if ($password !== $confirmPassword) {
			throw new \Exception('Passwords do not match');
		}

		$this->resetPasswordFromToken($token, $password);
	}

	public function validateResetPasswordToken($token)
	{
		$stmt = $this->db->prepare('SELECT * FROM cms_password_resets WHERE token = :token');
		$stmt->execute(['token' => $token]);
		$stmt->fetch(PDO::FETCH_ASSOC);
		return $stmt->rowCount() > 0;
	}

	private function resetPasswordFromToken($token, $password)
	{
		$stmt = $this->db->prepare('SELECT cpr.* FROM cms_password_resets cpr JOIN cms_users u ON u.user_id = cpr.user_id WHERE token = :token');
		$stmt->execute(['token' => $token]);
		$resetRow = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$resetRow) {
			throw new \Exception('Your password could not be reset. Please try again.');
		}

		$stmt = $this->db->prepare('UPDATE cms_users SET password = :password WHERE user_id = :user_id');
		$stmt->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'user_id' => $resetRow['user_id']]);

		$stmt = $this->db->prepare('DELETE FROM cms_password_resets WHERE token = :token');
		$stmt->execute(['token' => $token]);
	}

	private function getUserFromEmail(string $email)
	{
		$stmt = $this->db->prepare('SELECT * FROM cms_users WHERE email = :email');
		$stmt->execute(['email' => $email]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	private function generatePasswordResetToken(): string
	{
		$token = bin2hex(random_bytes(32));
		return $token;
	}

	private function storePasswordResetToken($user, $token)
	{
		$stmt = $this->db->prepare('INSERT INTO cms_password_resets (user_id, token) VALUES (:user_id, :token)');
		$stmt->execute(['user_id' => $user['user_id'], 'token' => $token]);
	}
}
