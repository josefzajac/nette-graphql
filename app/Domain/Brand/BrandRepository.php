<?php declare(strict_types = 1);

namespace App\Domain\Brand;

use App\Domain\User\User;
use App\Model\Database\PaginatorInput;
use App\Model\Database\Repository\AbstractRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends AbstractRepository<User>
 */
class BrandRepository extends AbstractRepository
{
	public function __construct(
		ObjectManager $manager,
		private readonly string $entityClass = Brand::class,
	) {
		parent::__construct($manager, $manager->getClassMetadata($this->entityClass));
	}

	public function getAll(PaginatorInput $pagination): Paginator
	{
		$query = $this->createQueryBuilder('b')
				->orderBy('b.id', 'DESC')
				->setFirstResult(($pagination->page - 1) * $pagination->itemsPerPage)
				->setMaxResults($pagination->itemsPerPage);

		return new Paginator($query);
	}
}
