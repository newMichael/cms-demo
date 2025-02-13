<?php

namespace App\Repository;

class CharacterRepository
{
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function getById(int $id)
	{
		$stmt = $this->db->prepare('SELECT * FROM characters WHERE id = :id');
		$stmt->execute([':id' => $id]);
		return $stmt->fetch();
	}

	public function saveCharacter(array $character)
	{
		$stmt = $this->db->prepare('INSERT INTO characters (name, handle, bio, description, posts_about, traits, ai_model) VALUES (:name, :handle, :bio, :description, :posts_about, :traits, :ai_model)');
		$stmt->execute([
			':name' => $character['name'],
			':handle' => $character['handle'],
			':bio' => $character['bio'],
			':description' => $character['description'],
			':posts_about' => $character['posts_about'],
			':traits' => $character['traits'],
			':ai_model' => $character['ai_model'],
		]);
	}
}