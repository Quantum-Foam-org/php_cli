<?php

namespace cli\classes;


/**
 * Readline interface
 */
class ReadLine {
	protected $defaultTextColor = 107;
	protected $defaultBackgroundColor = 0;
	protected $terminationString = 'q';
	protected $prompt = null;
	protected $completion = TRUE;
	
	/**
	 * Enables completion if the completion variable is set to TRUE
	 * @throws RuntimeException
	 */
	public function __construct() {
		if ($this->completion === TRUE && !readline_completion_function(array($this, 'completion'))) {
			throw new RuntimeException('Unable to add readline completion function');
		}
	}
	
	
	/**
	 * Override this method to create the menu
	 */
	public function menu() {
		echo "1.) Test Item 1\n";
		echo "2.) Test Item 3\n";
	}
	
	/**
	 * Override this method to support tab completion.
	 */
	public function completion() {
		
	}
	
	/**
	 * Override this method to do something with the user data.
	 */
	public function handleInput() {
		echo $string."\n";
	}
	
	/**
	 * Will read a line from the user with readline.  handleInput is then called with the user input string.
	 * If the object supports hashistory than the input string will be added to the readline_add_history
	 */
	public function readline() {
		
		do {
			$this->menu();
			$string = readline(($this->prompt !== null ? $this->prompt : '# '));
			$this->handleInput(trim($string));
			if ($this->hasHistory) {
				readline_add_history($string);
			}
		} while ($string !== $this->terminationString);
	}
	
	public function redraw() {
		readline_redisplay();
	}
	
	/**
	 * @param String $text output text
	 * @param number $fmt format of the text
	 * 		1-bold, 2-dim, 4-underline, 5-blink, 7-inverted, 8-hidden
	 * @param number $textColor color of the text
	 * @param number $backgroundColor color of the background
	 * @return string
	 */
	public function text($text, $fmt = 0, $textColor = 0, $backgroundColor = 0) {
		if (!in_array($fmt, array(1,2,4,5,7,8))) {
			logger::obj()->write('Output format may not be supported', 1);
		}
		if (!is_int($textColor) || $textColor < 0 || $textColor > 256) {
			logger::obj()->write('Invalid color, '.$textColor.', should be between 0 and 256');
		}
		if (!is_int($backgroundColor) || $backgroundColor < 0 || $backgroundColor > 256) {
			logger::obj()->write('Invalid color, '.$backgroundColor.', should be between 0 and 256');
		}
		
		return "\e[{$fmt};{$textColor};{$backgroundColor}m{$text}\e[2{$fmt};\e0m";
	}
}