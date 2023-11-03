<?php declare(strict_types = 1);

namespace App\UI\Modules\Base;

use Nette\Security\UserStorage;

abstract class SecuredPresenter extends BasePresenter
{

	public function checkRequirements(mixed $element): void
	{
		if (!$this->user->isLoggedIn()) {
			if ($this->user->getLogoutReason() === UserStorage::LOGOUT_INACTIVITY) {
				$this->flashInfo('You have been logged out for inactivity');
			}

			$this->redirect(
				':Front:Home',
				['backlink' => $this->storeRequest()]
			);
		}
	}

}
