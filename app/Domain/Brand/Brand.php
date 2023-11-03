<?php declare(strict_types = 1);

namespace App\Domain\Brand;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Brand\BrandRepository")
 * @ORM\Table(name="brand")
 * @ORM\HasLifecycleCallbacks
 */
class Brand extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

}
