<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;

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
		$flash = $this->container->get('flash')->getMessages();
		$error = isset($flash['error']) ? $flash['error'][0] : null;
		
		$view = Twig::fromRequest($request);
		return $view->render($response, 'pages.html.twig', [
			'document_title' => 'Pages | CMS',
			'page_tree' => $pageTree,
			'error_message' => $error,
			'current_route' => 'pages'
		]);
	}

	public function showEditPage(Request $request, Response $response)
	{
		$pageId = $request->getAttribute('id');
		$db = $this->container->get('db');
		$stmt = $db->prepare("SELECT * FROM pages WHERE page_id = :page_id");
		$stmt->execute(['page_id' => $pageId]);
		$page = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (!$page) {
			$this->container->get('flash')->addMessage('error', 'Page not found');
			$routeParser = RouteContext::fromRequest($request)->getRouteParser();
			$url = $routeParser->urlFor('pages');
			return $response->withHeader('Location', $url)->withStatus(302);
		}

		$stmt = $db->query("SELECT * FROM pages");
		$pages = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$pageTree = $this->buildPageTree($pages);
		$flash = $this->container->get('flash')->getMessages();
		$error = isset($flash['error']) ? $flash['error'][0] : null;

		$view = Twig::fromRequest($request);
		return $view->render($response, 'page-edit.html.twig', [
			'document_title' => 'Edit Page | CMS',
			'page' => $page,
			'page_tree' => $pageTree,
			'error_message' => $error,
			'current_route' => 'pages'
		]);
	}

	public function createNew(Request $request, Response $response, $args)
	{
		try {
			$data = $request->getParsedBody();
			$this->validateNewPageData($data);
		} catch (\Exception $e) {
			$this->container->get('flash')->addMessage('error', $e->getMessage());
			$routeParser = RouteContext::fromRequest($request)->getRouteParser();
			$url = $routeParser->urlFor('pages');
			return $response->withHeader('Location', $url)->withStatus(302);
		}

		$pageTitle = $data['title'];
		$parentPageId = empty($data['parent_id']) ? null : $data['parent_id'];
		$slug = $data['slug'];

		$uri = $this->getUriFromParent($parentPageId) . '/' . $slug;
		$db = $this->container->get('db');
		$stmt = $db->prepare("INSERT INTO pages (title, parent_id, slug, uri) VALUES (:title, :parent_id, :slug, :uri)");
		$stmt->execute([
			'title' => $pageTitle,
			'parent_id' => $parentPageId,
			'slug' => $slug,
			'uri' => $uri
		]);
		
		$routeParser = RouteContext::fromRequest($request)->getRouteParser();
		$url = $routeParser->urlFor('pages');
		return $response->withHeader('Location', $url)->withStatus(302);
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

	private function validateNewPageData(array $data)
	{
		$errors = [];

		if (empty($data['title'])) {
			throw new \Exception('Title is required');
		}
		if (empty($data['slug'])) {
			throw new \Exception('Slug is required');
		}

		$db = $this->container->get('db');
		$stmt = $db->prepare("SELECT * FROM pages WHERE slug = :slug AND parent_id = :parent_id");
		$stmt->execute([
			'slug' => $data['slug'],
			'parent_id' => $data['parent_id']
		]);
		$page = $stmt->fetch(\PDO::FETCH_ASSOC);
		if ($page) {
			throw new \Exception('This URL is already in use');
		}

		return $errors;
	}

	private function getUriFromParent($parentId)
	{
		$uri = '';
		if ($parentId) {
			$db = $this->container->get('db');
			$stmt = $db->prepare("SELECT * FROM pages WHERE page_id = :page_id");
			$stmt->execute(['page_id' => $parentId]);
			$page = $stmt->fetch(\PDO::FETCH_ASSOC);
			$uri = $page['uri'];
		}
		return $uri;
	}
}