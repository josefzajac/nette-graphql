<?php declare(strict_types = 1);

namespace App\Model\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	private RouteList $router;

	public function __construct()
	{
		$this->router = new RouteList();
	}

	public function create(): RouteList
	{
		$this->buildGraphQL();
		$this->buildFront();

		return $this->router;
	}

	protected function buildFront(): void
	{
		$this->router[] = $list = new RouteList('Front');
		$list->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
	}

	protected function buildGraphQL(): void
	{
		$this->router[] = $list = new RouteList('GraphQL');
		$list->addRoute('graphql', 'GraphQL:default');
		$list->addRoute('graphiql', 'GraphiQL:default');
	}

}
