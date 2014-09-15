<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AngelReset extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'angel:reset';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Empty the database and run all package migrations.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Emptying the database...');
		$host      = Config::get('database.connections.mysql.host');
		$database  = Config::get('database.connections.mysql.database');
		$username  = Config::get('database.connections.mysql.username');
		$password  = Config::get('database.connections.mysql.password');
		$this->exec('mysql -h ' . $host . ' -u ' . $username . ' -p\'' . $password . '\' -e "DROP DATABASE ' . $database . '"');
		$this->exec('mysql -h ' . $host . ' -u ' . $username . ' -p\'' . $password . '\' -e "CREATE DATABASE ' . $database . '"');

		if (!is_dir(base_path('workbench/angel'))) {
			$this->error('The workbench/angel directory does not exist.');
			return;
		}

		chdir(base_path());

		$this->info('Running all migrations...');
		$this->exec('php artisan migrate --bench=angel/core');

		foreach (glob(base_path('workbench/angel/*')) as $dir) {
			if (!is_dir($dir)) {
				$this->error('Not a directory: ' . $dir);
				continue;
			}
			$package = basename($dir);
			if ($package == 'core') continue;
			$this->exec('php artisan migrate --bench=angel/' . $package);
		}
		$this->info('...all migrations have been ran.');
	}

	private function exec($command)
	{
		$this->info('Executing: ' . $command);
		echo shell_exec($command);
	}

}
