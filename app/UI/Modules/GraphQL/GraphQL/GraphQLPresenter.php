<?php declare(strict_types = 1);

namespace App\UI\Modules\GraphQL\GraphQL;

use App\Domain\Brand\Brand;
use App\Domain\Brand\BrandFacade;
use App\Domain\Brand\BrandRepository;
use App\Model\Database\PaginatorInput;
use App\UI\Modules\Base\BasePresenter;
use Exception;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Nette\Application\Responses\JsonResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GraphQLPresenter extends BasePresenter
{
	/** @var false|mixed|string */
	protected mixed $inputBody;

	/** @var false */
	protected mixed $inputBodyDecoded;

	/** @inject */
	public BrandFacade $brandFacade;

	/** @inject */
	public BrandRepository $brandRepository;

	protected function startup(): void
	{
		parent::startup();

		$this->inputBody = file_get_contents('php://input');
		$this->inputBodyDecoded = json_decode($this->inputBody, true);
	}

	public function actionDefault(): void
	{
		$this->checkMethodType();

		$brandType = new ObjectType([
			'name' => 'Brand',
			'fields' => [
				'id' => ['type' => Type::id()],
				'name' => ['type' => Type::string()],
			],
			'resolveField' => function (Brand $brand, array $args, $context, ResolveInfo $info) {
				return match ($info->fieldName) {
					'id' => $brand->getId(),
					'name' => $brand->getName(),
					default => null,
				};
			},
		]);

		$brandConnectionType = new ObjectType([
			'name' => 'BrandConnection',
			'fields' => [
				'items' => ['type' => ListOfType::listOf($brandType)],
				'totalCount' => ['type' => Type::int()],
				'currentPage' => ['type' => Type::int()],
				'perPage' => ['type' => Type::int()],
			],
		]);

		$schema = new Schema([
			'query' => new ObjectType([
				'name' => 'Query',
				'fields' => [
					'brands' => [
						'type' => $brandConnectionType,
						'resolve' => function($rootValue, array $args): array {
							$paginator = $this->brandRepository->getAll(new PaginatorInput($args['page'], $args['itemsPerPage']));

							return [
								'items' => $paginator,
								'currentPage' => $args['page'],
								'totalCount' => $paginator->count(),
								'perPage' => $args['itemsPerPage']
								];
						},
						'args' => [
							'itemsPerPage' => Type::Int(),
							'page' => Type::Int()
						],
					],
				],
			]),
			'mutation' => new ObjectType([
				'name' => 'Mutation',
				'fields' => [
					'createBrand' => [
						'type' => $brandType,
						'resolve' => function($rootValue, array $args): Brand {
							return $this->brandFacade->createBrand((string) $args['name']);
						},
						'args' => [
							'name' => ['type' => Type::string()],
						]],
					'updateBrand' => [
						'type' => $brandType,
						'resolve' => function($rootValue, array $args): Brand {
							return $this->brandFacade->updateBrand((int) $args['id'], (string) $args['name']);
						},
						'args' => [
							'id' => ['type' => Type::int()],
							'name' => ['type' => Type::string()],
						]],
					'deleteBrand' => [
						'type' => Type::int(),
						'resolve' => function($rootValue, array $args): int {
							return $this->brandFacade->deleteBrand((int) $args['id']);
						},
						'args' => [
							'id' => ['type' => Type::int()],
						]],
				],
			])
		]);

		try{
			$result = GraphQL::executeQuery(
				schema: $schema,
				source: $this->inputBodyDecoded['query'],
				variableValues: $this->inputBodyDecoded['variables'] ?? null);

			$this->formatObjectPayload((object) $result->toArray());
		}
		catch ( Exception $e) {
			$this->formatException(new Exception(sprintf("GQL error '%s'", $e->getMessage())));
		}
	}

	public function checkMethodType()
	{
		if (!$this->getHttpRequest()->isMethod(IRequest::Post)) {
			$this->formatException(new Exception("Chybně odeslaná metoda POST."));
		}

		if ($this->inputBody !== '' && !is_object(json_decode((string)$this->inputBody))) {
			$this->formatException(new Exception("Chybně zadaný json."));
		}
	}

	public function beforeRender(): void
	{
		$this->formatException(new Exception("Zadaná URL adresa API neexistuje."));
	}

	protected function formatException(Exception $e): never
	{
		$this->ApiResponse(['message' => $e->getMessage()], IResponse::S200_OK, 'error');
	}

	protected function formatObjectPayload(object $payload): never
	{
		$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

		$this->ApiJsonResponse($serializer->serialize($payload, 'json'), IResponse::S200_OK, 'ok');
	}

	protected function ApiJsonResponse(string $responseJson, $code, $status): never
	{
		$this->ApiResponse(json_decode($responseJson, false), $code, $status);
	}

	protected function ApiResponse($responseArray, $code, $status): never
	{
		$httpResponse = $this->getHttpResponse();
		$httpResponse->setCode($code);
		$httpResponse->addHeader('State', $status);
		$httpResponse->setHeader('X-Powered-By', '');

		$response = new JsonResponse($responseArray);
		$response->send($this->getHttpRequest(), $httpResponse);
		exit;
	}
}
