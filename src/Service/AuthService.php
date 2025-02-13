<?php

namespace App\Service;

class AuthService
{
	protected $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function login($email, $password): bool
	{
		$stmt = $this->db->prepare('SELECT * FROM cms_users WHERE email = :email');
		$stmt->execute(['email' => $email]);
		$user = $stmt->fetch();

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

	public function isAuthenticated(): bool
	{
		return isset($_SESSION['cms']);
	}
}
