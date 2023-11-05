<?php declare(strict_types = 1);

namespace App\Domain\Brand;

use App\Model\Database\PaginatorInput;
use App\Model\Database\Repository\AbstractRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectManager;

/**
 * @extends AbstractRepository<Brand>
 */
class BrandRepository extends AbstractRepository
{

	public function __construct(
		ObjectManager $manager,
		private readonly string $entityClass = Brand::class,
	)
	{
		parent::__construct($manager, $manager->getClassMetadata($this->entityClass));
	}

	public function getAll(PaginatorInput $pagination): Paginator
	{
		$order = explode(self::ORDER_BY_SEPARATOR, $pagination->order);
		$query = $this->createQueryBuilder('b')
			->orderBy('b.' . $order[0], $order[1])
			->setFirstResult(($pagination->page - 1) * $pagination->itemsPerPage)
			->setMaxResults($pagination->itemsPerPage);

		return new Paginator($query);
	}

}
