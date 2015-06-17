<?php

namespace App\Model\Security;

use Nette;

class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator
{

	/** @var \App\Model\Security\UserFacade */
	private $userFacade;



	public function __construct(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}



	/**
	 * Performs an authentication.
	 *
	 * @param array $credentials
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
		$row = $this->userFacade->findUserByEmail($email);

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!self::verifyPassword($password, $row->password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		return $row;
	}



	/**
	 * Computes salted password hash.
	 *
	 * @param $password
	 * @param null $options
	 * @return string
	 */
	public static function hashPassword($password, $options = NULL)
	{
		if ($password === Nette\Utils\Strings::upper($password)) { // perhaps caps lock is on
			$password = Nette\Utils\Strings::lower($password);
		}
		$password = substr($password, 0, 4096);
		$options = $options ?: implode('$', [
			'algo' => PHP_VERSION_ID < 50307 ? '$2a' : '$2y', // blowfish
			'cost' => '07',
			'salt' => Nette\Utils\Strings::random(22),
		]);

		return crypt($password, $options);
	}



	/**
	 * Verifies that a password matches a hash.
	 *
	 * @param $password
	 * @param $hash
	 * @return bool
	 */
	public static function verifyPassword($password, $hash)
	{
		return self::hashPassword($password, $hash) === $hash
		|| (PHP_VERSION_ID >= 50307 && substr($hash, 0, 3) === '$2a' && self::hashPassword($password, $tmp = '$2x' . substr($hash, 3)) === $tmp);
	}
}
