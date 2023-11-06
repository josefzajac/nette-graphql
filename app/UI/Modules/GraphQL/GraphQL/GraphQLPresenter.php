<?php declare(strict_types = 1);

namespace App\UI\Modules\GraphQL\GraphQL;

use App\Domain\Brand\BrandFacade;
use App\Domain\Brand\BrandRepository;
use App\Domain\Brand\BrandSchemaResolver;
use App\UI\Modules\Base\BasePresenter;
use Exception;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GraphQLPresenter extends BasePresenter
{

	#[Inject]
	public BrandFacade $brandFacade;

	#[Inject]
	public BrandRepository $brandRepository;

	#[Inject]
	public BrandSchemaResolver $brandSchemaResolver;

	protected string $inputBody;

	protected array $inputBodyDecoded;

	public function actionDefault(): void
	{
		$this->checkMethodType();

		$schema = new Schema([
			'query' => new ObjectType([
				'name' => 'Query',
				'fields' => [
					'brands' => $this->brandSchemaResolver->getQuerySchemaList(),
				],
			]),
			'mutation' => new ObjectType([
				'name' => 'Mutation',
				'fields' => [
					'createBrand' => $this->brandSchemaResolver->getMutationSchemaCreate(),
					'updateBrand' => $this->brandSchemaResolver->getMutationSchemaUpdate(),
					'deleteBrand' => $this->brandSchemaResolver->getMutationSchemaDelete(),
				],
			]),
		]);

		try {
			$result = GraphQL::executeQuery(
				schema: $schema,
				source: $this->inputBodyDecoded['query'],
				rootValue: [
					'brandFacade' => $this->brandFacade,
					'brandRepository' => $this->brandRepository,
				],
				variableValues: $this->inputBodyDecoded['variables'] ?? null
			);

			$this->formatObjectPayload((object) $result->toArray());
		} catch ( \Throwable $e) {
			$this->formatException(new Exception(sprintf("GQL error '%s'", $e->getMessage())));
		}
	}

	public function beforeRender(): void
	{
		$this->formatException(new Exception('Zadaná URL adresa API neexistuje.'));
	}

	protected function startup(): void
	{
		parent::startup();

		$this->inputBody = file_get_contents('php://input');
		if (!(bool) $this->inputBody) {
			throw new \Exception('Missing input');
		}

		$this->inputBodyDecoded = json_decode((string) $this->inputBody, true);
	}

	protected function checkMethodType(): void
	{
		if (!$this->getHttpRequest()->isMethod(IRequest::Post)) {
			$this->formatException(new Exception('Chybně odeslaná metoda POST.'));
		}

		if ($this->inputBody !== '' && !is_object(json_decode($this->inputBody))) {
			$this->formatException(new Exception('Chybně zadaný json.'));
		}
	}

	protected function formatException(\Throwable $e): never
	{
		$this->apiResponse(['message' => $e->getMessage()], IResponse::S200_OK, 'error');
	}

	protected function formatObjectPayload(object $payload): never
	{
		$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

		$this->apiJsonResponse($serializer->serialize($payload, 'json'), IResponse::S200_OK, 'ok');
	}

	protected function apiJsonResponse(string $responseJson, int $code, string $status): never
	{
		$this->apiResponse(json_decode($responseJson, false), $code, $status);
	}

	protected function apiResponse(mixed $responseArray, int $code, string $status): never
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
