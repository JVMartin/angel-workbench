<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AngelWorkbench extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'angel:workbench';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update all workbench submodules.';

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
		return array(
			array('assets', 'a', InputOption::VALUE_NONE, 'Also publish assets.')
		);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Updating all Angel submodules...');

		if (!is_dir(base_path('workbench/angel'))) {
			$this->error('The workbench/angel directory does not exist.');
			return;
		}

		chdir(base_path());
		$this->exec('git submodule init');
		$this->exec('git submodule update');

		if ($this->option('assets')) {
			$this->exec('rm -rf public/packages/angel');
		}

		foreach (glob(base_path('workbench/angel/*')) as $dir) {
			if (!is_dir($dir)) {
				$this->error('Not a directory: ' . $dir);
				continue;
			}
			$this->info('Entering ' . $dir);
			chdir($dir);
			$this->exec('git checkout master');
			$this->exec('git pull');
			if ($this->option('assets')) {
				chdir(base_path());
				$package = basename($dir);
				$this->exec('php artisan asset:publish --bench=angel/' . $package);
			}
		}
		$this->info('...all Angel submodules have been updated.');
	}

	private function exec($command)
	{
		$this->info('Executing: ' . $command);
		echo shell_exec($command);
	}

}
