<?php


use cli\classes as cli;

require_once('../php_common/object/Config.php');
require_once('./classes/Flag.php');
require_once('./classes/Readline.php');



class RunMenu extends cli\Readline {
	private $dir = '.*';
	
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
		echo "1.) Press 1 to return directory contents\n";
		echo "2.) Press r to redraw the display\n";
		echo "3.) Press q to quit\n";
	}
	
	public function handleInput($text) {
		switch ($text) {
			case '1':
				echo "\n\n-----------------------------------------\n\n";
				echo "\t\n".implode("\t\n", glob($this->dir))."\n";
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