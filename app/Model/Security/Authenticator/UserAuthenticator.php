<?php declare(strict_types = 1);

namespace App\Model\Security\Authenticator;

use App\Domain\User\User;
use App\Model\Database\EntityManagerDecorator;
use App\Model\Exception\Runtime\AuthenticationException;
use App\Model\Security\Passwords;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;

final class UserAuthenticator implements Authenticator
{

	public function __construct(
		private EntityManagerDecorator $em,
		private Passwords $passwords
	)
	{
	}

	/**
	 * @throws AuthenticationException
	 */
	public function authenticate(string $username, string $password): IIdentity
	{
		/** @var User|null $user */
		$user = null;

		if ($user === null) {
			throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!$user->isActivated()) {
			throw new AuthenticationException('The user is not active.', self::INVALID_CREDENTIAL);
		} elseif (!$this->passwords->verify($password, $user->getPasswordHash())) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$user->changeLoggedAt();
		$this->em->flush();

		return $this->createIdentity($user);
	}

	protected function createIdentity(User $user): IIdentity
	{
		return $user->toIdentity();
	}

}
