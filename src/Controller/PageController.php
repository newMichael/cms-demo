<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PageController
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function index(Request $request, Response $response, $args)
	{
		$db = $this->container->get('db');
		$stmt = $db->query("SELECT * FROM pages");
		$pages = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$pageTree = $this->buildPageTree($pages);

		$view = Twig::fromRequest($request);

		return $view->render($response, 'pages.html.twig', [
			'document_title' => 'Pages | CMS',
			'page_tree' => $pageTree,
			'current_route' => 'pages'
		]);
	}

	private function buildPageTree(array $pages, $parentId = null)
	{
		$branch = [];

		foreach ($pages as $page) {
			if ($page['parent_id'] == $parentId) {
				$children = $this->buildPageTree($pages, $page['page_id']);
				if ($children) {
					$page['children'] = $children;
				}
				$branch[] = $page;
			}
		}

		return $branch;
	}
}

