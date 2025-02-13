<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
	public function getFunctions()
	{
		return [
			new TwigFunction('relative_time', [$this, 'getRelativeTime']),
		];
	}

	public function getRelativeTime($postTime)
	{
		$now = new \DateTime();
		$postedAt = new \DateTime($postTime);
		$interval = $now->diff($postedAt);
		$intervalString = '';
		
		if ($interval->d > 7) {
			$intervalString = $postedAt->format('m/d/Y');
		} elseif ($interval->d > 0) {
			$intervalString = $interval->d . ' days ago';
		} elseif ($interval->h > 0) {
			$intervalString = $interval->h . ' hours ago';
		} elseif ($interval->i > 0) {
			$intervalString = $interval->i . ' minutes ago';
		} else {
			$intervalString = 'just now';
		}
		return $intervalString;
	}
}
