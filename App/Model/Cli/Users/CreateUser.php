<?php

namespace App\Model\Cli\Users;

use App\Model\Security\UserFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{

	/** @var UserFacade */
	private $userFacade;



	public function __construct(UserFacade $userFacade)
	{
		parent::__construct();
		$this->userFacade = $userFacade;
	}



	protected function configure()
	{
		$this->setName('user:create');
		$this->setDescription('Create new user.');
	}



	protected function interact(InputInterface $input, OutputInterface $output)
	{
		/** @var DialogHelper $dialog */
		$dialog = $this->getHelper('dialog');

		$username = $dialog->askAndValidate($output, 'Please give the email:', function ($username) {
				if (empty($username)) {
					throw new \Exception('email can not be empty');
				}

				return $username;
			}
		);
		$this->addArgument('email');
		$input->setArgument('email', $username);

		$password = $dialog->askHiddenResponseAndValidate($output, 'Please enter the new password:', function ($password) {
				if (empty($password)) {
					throw new \Exception('Password can not be empty');
				}

				return $password;
			}
		);
		$this->addArgument('password');
		$input->setArgument('password', $password);

		$name = $dialog->askAndValidate($output, 'Please give the name:', function ($name) {
				if (empty($name)) {
					throw new \Exception('Name can not be empty');
				}

				return $name;
			}
		);
		$this->addArgument('name');
		$input->setArgument('name', $name);
	}



	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$user = $this->userFacade->createUser($input->getArgument("name"), $input->getArgument("email"), $input->getArgument("password"));
		if (!$user) {
			$output->writeln('<error>Email already registered, pick another email</error>');
		}
	}

}