<?php namespace Mamok\TestParser;


use \ProgressBar\Manager as ProgressBarManager;


class ConsoleController
{
	private static $instance;

	private $progressBar;

	private $listConsole;

	/**
	 * List funcs
	 */

	public function initList() {
		$this->listConsole = "";
	}


	public function listAddRow($message, $needEol=True) {
		$this->listConsole .= $message . ($needEol ? PHP_EOL : "");
	}


	public function listRender() {
		echo $this->listConsole;
	}


	public function unsetList() {
		$this->listConsole = null;
	}


	/**
	 * Progress bar funcs
	 */

	public function initProgressBar($maxProgress) {
		$this->progressBar = new ProgressBarManager(0, $maxProgress);
	}


	public function progressBarTick() {
		$this->progressBar->advance();
	}

	
	public function progressComplete() {
		// $this->progressBar->complete();
	}

	
	public function unsetProgressBar() {
		$this->progressBar = null;
	}

	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new ConsoleController();
		} 

		return self::$instance;
	}
}