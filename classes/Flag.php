<?php

namespace cli\classes;

use common\obj;
use common\logging\Logger;


/**
 * Should be extended when a cli command needs to support flags.
 */
class Flag extends obj\Config {
	protected $prefix = '--';
	
	public function __construct() {
	    if ($_SERVER['argc'] < 1) {
	        throw new \ArgumentCountError('You must set CLI arguments: '.implode(', ', $this->getConfiguredParams()));
	    }
	}
	
	public function exchangeArray($arguments) {
	    $oldArray = $this->getArrayCopy();
	    
		if (!is_array($arguments)) {
			$arguments = array($arguments);
		}
		foreach (substr_replace(array_map('strval', $arguments), '', 0, strlen($this->prefix)) as $value) {
			$tmp = (strpos($value, '=') === FALSE ? array($value, 1) : explode('=', $value, 2));
			try {
				$this->offsetSet($tmp[0], $tmp[1]);
			} catch (\OutOfBoundsException | \UnexpectedValueException | \RuntimeException $oe) {
                            Logger::obj()->write(
                                    "Parameter must be set using = '--{option}={value}", 
                                    1, 
                                    true);
                            
                            exit(Logger::obj()->writeException($oe, -1, true));
			}
		}
		unset($tmp, $value);
		
		return $oldArray;
	}
}