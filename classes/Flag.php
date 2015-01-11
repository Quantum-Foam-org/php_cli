<?php

namespace cli\classes;

use common\object;
use common\logging;


/**
 * Should be extended when a cli command needs to support flags.
 */
class Flag extends \common\object\Config {
	protected $prefix = '--';
	
	public function exchangeArray($arguments) {
		if (!is_array($arguments)) {
			$arguments = array($arguments);
		}
		foreach (substr_replace(array_map('strval', $arguments), '', 0, strlen($this->prefix)) as $value) {
			$tmp = (strpos($value, '=') === FALSE ? array($value, 1) : explode('=', $value, 2));
			try {
				$this->offsetSet($tmp[0], $tmp[1]);
			} catch (\OutOfBoundsException $oe) {
				\common\logging\Error::handle($oe);
			}
		}
		unset($tmp, $value);
	}
}