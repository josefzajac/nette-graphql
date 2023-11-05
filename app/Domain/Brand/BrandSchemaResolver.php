<?php declare(strict_types = 1);

namespace App\Domain\Brand;

use App\Model\Database\PaginatorInput;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class BrandSchemaResolver
{

	private static ObjectType $brandType;

	public function getQuerySchemaList(): array
	{
		self::$brandType = new ObjectType([
			'name' => 'Brand',
			'fields' => [
				'id' => ['type' => Type::id()],
				'name' => ['type' => Type::string()],
			],
			'resolveField' => fn (Brand $brand, array $args, $context, ResolveInfo $info) => match ($info->fieldName) {
				'id' => $brand->getId(),
				'name' => $brand->getName(),
				default => null,
			},
		]);

		$brandConnectionType = new ObjectType([
			'name' => 'BrandConnection',
			'fields' => [
				'items' => ['type' => ListOfType::listOf(self::$brandType)],
				'totalCount' => ['type' => Type::int()],
				'currentPage' => ['type' => Type::int()],
				'perPage' => ['type' => Type::int()],
			],
		]);

		return [
			'type' => $brandConnectionType,
			'resolve' => function ($rootValue, array $args): array {
				/** @var BrandRepository $brandRepository */
				$brandRepository = $rootValue['brandRepository'];
				$paginator = $brandRepository->getAll(new PaginatorInput($args['page'], $args['itemsPerPage'], $args['order'] ?? PaginatorInput::DEFAULT_ORDER));

				return [
					'items' => $paginator,
					'currentPage' => $args['page'],
					'totalCount' => $paginator->count(),
					'perPage' => $args['itemsPerPage'],
				];
			},
			'args' => [
				'itemsPerPage' => Type::int(),
				'page' => Type::int(),
				'order' => Type::string(),
			],
		];
	}

	public function getMutationSchemaCreate(): array
	{
		return [
			'type' => self::$brandType,
			'resolve' => fn ($rootValue, array $args): Brand => $rootValue['brandFacade']->createBrand((string) $args['name']),
			'args' => [
				'name' => ['type' => Type::string()],
			]];
	}

	public function getMutationSchemaUpdate(): array
	{
		return [
			'type' => self::$brandType,
			'resolve' => fn ($rootValue, array $args): Brand => $rootValue['brandFacade']->updateBrand((int) $args['id'], (string) $args['name']),
			'args' => [
				'id' => ['type' => Type::int()],
				'name' => ['type' => Type::string()],
			]];
	}

	public function getMutationSchemaDelete(): array
	{
		return [
			'type' => Type::int(),
			'resolve' => fn ($rootValue, array $args): int => $rootValue['brandFacade']->deleteBrand((int) $args['id']),
			'args' => [
				'id' => ['type' => Type::int()],
			]];
	}

}
