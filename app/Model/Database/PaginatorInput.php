<?php declare(strict_types = 1);

namespace App\Model\Database;

class PaginatorInput
{

	public function __construct(
		public int $page = 1,
		public int $itemsPerPage = 10
	)
	{
	}

}
