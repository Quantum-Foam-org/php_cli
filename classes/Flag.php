<?php

namespace cli\classes;

use common\object;


/**
 * Should be extended when a cli command needs to support flags.
 */
class Flag extends \common\object\Config {
	protected $prefix = '--';
	
	public function exchangeArray(array $arguments) {
		foreach (substr_replace($arguments, '', 0, strlen($this->prefix)) as $value) {
			$tmp = (strpos($value, '=') === FALSE ? array($value, 1) : explode('=', $value, 2));
			$this->offsetSet($tmp[0], $tmp[1]);
		}
		unset($tmp, $value);
	}
}