<?php declare(strict_types=1);

namespace App\Domain\Brand;

use App\Model\Database\EntityManagerDecorator;

class BrandFacade
{
	public function __construct(
		private readonly EntityManagerDecorator $em
	) {}

	/**
	 * @param array<string> $data
	 */
	public function createBrand(array $data): Brand
	{
		$user = new Brand((string)$data['name']);

		$this->em->persist($user);
		$this->em->flush();

		return $user;
	}
}
