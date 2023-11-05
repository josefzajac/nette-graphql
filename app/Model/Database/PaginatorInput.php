<?php declare(strict_types = 1);

namespace App\Model\Database;

use App\Model\Database\Repository\AbstractRepository;

class PaginatorInput
{

	public const DEFAULT_ORDER = 'id' . AbstractRepository::ORDER_BY_SEPARATOR . 'DESC';

	public function __construct(
		public int $page = 1,
		public int $itemsPerPage = 10,
		public string $order = self::DEFAULT_ORDER,
	)
	{
	}

}
