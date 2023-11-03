<?php declare(strict_types = 1);

namespace Database\Fixtures;

use App\Domain\Brand\Brand;
use Doctrine\Persistence\ObjectManager;

class BrandFixture extends AbstractFixture
{
	public function getOrder(): int
	{
		return 2;
	}

	public function load(ObjectManager $manager): void
	{
		$recordsCount = 50;

		for ($i = 0; $i < $recordsCount; $i++)
		{
			$manager->persist(new Brand($this->faker->unique()->company()));
		}

		$manager->flush();
	}
}
