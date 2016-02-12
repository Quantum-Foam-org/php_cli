#!/usr/local/bin/php

<?php

use cli\classes as cli;

$common_php_dir = '../php_common';
$common_autoload_file = $common_php_dir.'/autoload.php';
require($common_autoload_file);

$cli_php_dir = '.';
$cli_autoload_file = $cli_php_dir.'/autoload.php';
require($cli_autoload_file);

\common\Config::obj(__DIR__ . '/config/config.ini');


class RunMenu extends cli\Readline {
	private $dir = __DIR__;
	
	public function __construct($argv, $argc) {
		
		if ($argc >= 1) {
			$f = new \Flag();
			$f->exchangeArray(array_slice($argv, 1));
			if ($f->help === TRUE) {
				exit("\n\nHELP\n\n");
			}
			if (strlen($f->dir) > 0) {
				$this->dir = $f->dir;
			}
		}
	}
	
	public function menu() {
		$output = array(
				1 => array('Press', '`d`', "to return directory contents"),
				2 => array('Press', '`r`', "to redraw the display"),
				3 => array('Press', '`q`', "to quit")
		);
		foreach ($output as $i => $t) {
			echo $this->text($i.'.) ', 1).$this->text($t[0].' ', 1).$this->text($t[1].' ', 0, 31, 0).$this->text($t[2])."\n";
		}
	}
	
	public function handleInput($text) {
		switch ($text) {
			case 'd':
				echo "\n\n-----------------------------------------\n\n";
				echo "\t\n".implode("\t\n", scandir($this->dir))."\n";
				echo "\n\n-----------------------------------------\n\n";
				break;
			case 'r':
				$this->redraw();
				break;
			case 'q':
				exit(0);
				break;
			default: 
				echo "\n\nCommand not found\n\n";
				break;
		}
	}
	
}

class Flag extends cli\Flag {
	protected $help;
	protected $dir;
	
	protected $config = array(
		'help' => array(FILTER_VALIDATE_BOOLEAN),
		'dir' => array(FILTER_SANITIZE_STRING)	
	);
}

$rm = new RunMenu($argv, $argc);
$rm->readLine();