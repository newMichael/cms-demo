<?php

namespace App\Repository;

class PostRepository
{
	private $db;

	public function __construct(\PDO $db)
	{
		$this->db = $db;
	}

	public function getRecentPosts()
	{
		$stmt = $this->db->query('SELECT p.*, c.name, c.image, c.handle FROM posts p JOIN characters c ON c.id = p.character_id ORDER BY p.created_at DESC LIMIT 20');
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getPostsByCharacterId($id)
	{
		$stmt = $this->db->prepare('SELECT p.*, c.name, c.image, c.handle FROM posts p JOIN characters c ON c.id = p.character_id WHERE c.id = :id ORDER BY p.created_at DESC LIMIT 20');
		$stmt->execute([':id' => $id]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function savePost(array $post)
	{
		$stmt = $this->db->prepare('INSERT INTO posts (parent_id, character_id, content, created_at) VALUES (:parent_id, :character_id, :content, :created_at)');
		$stmt->execute([
			':parent_id' => $post['parent_id'],
			':character_id' => $post['character_id'],
			':content' => $post['content'],
			':created_at' => date('Y-m-d H:i:s'),
		]);
	}
}