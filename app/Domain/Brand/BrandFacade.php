<?php declare(strict_types = 1);

namespace App\Domain\Brand;

use App\Model\Database\EntityManagerDecorator;

class BrandFacade
{

	public function __construct(
		private readonly BrandRepository $repo,
		private readonly EntityManagerDecorator $em
	)
	{
	}

	public function createBrand(string $name): Brand
	{
		$brand = new Brand($name);

		$this->em->persist($brand);
		$this->em->flush();

		return $brand;
	}

	public function updateBrand(int $id, string $name): Brand
	{
		$brand = $this->repo->find($id);
		if (!(bool) $brand) {
			throw new \Exception('Brand not found');
		}

		/** @var Brand $brand */
		$brand->setName($name);

		$this->em->persist($brand);
		$this->em->flush();

		return $brand;
	}

	public function deleteBrand(int $id): int
	{
		$brand = $this->repo->find($id);
		if (!(bool) $brand) {
			throw new \Exception('Brand not found');
		}

		/** @var Brand $brand */
		$id = $brand->getId();

		$this->em->remove($brand);
		$this->em->flush();

		return $id;
	}

}
