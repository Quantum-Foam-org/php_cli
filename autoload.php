<?php

namespace common;

spl_autoload_register(function($class) {
	if (strpos($class, 'cli\\') === 0) {
		$class = substr($class, 4);
		$classFile = realpath(__DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php');
		
		if (file_exists($classFile)) {
			require($classFile);
		}
		unset($classFile);
	}
});