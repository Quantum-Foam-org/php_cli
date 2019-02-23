<?php

namespace cli\classes;

use common\logger;

/**
 * Readline interface
 */
class ReadLine {
	protected $defaultTextColor = 107;
	protected $defaultBackgroundColor = 0;
	protected $terminationString = 'q';
	protected $prompt = null;
	protected $completion = FALSE;
	protected $hasHistory = TRUE;
	
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
	public function menu() : void {
		echo "1.) Test Item 1\n";
		echo "2.) Test Item 3\n";
	}
	
	/**
	 * Override this method to support tab completion.
	 */
	public function completion() : void {
		
	}
	
	/**
	 * Override this method to do something with the user data.
	 */
	protected function handleInput(string $text) : void{
		echo $string."\n";
	}
	
	/**
	 * Will read a line from the user with readline.  handleInput is then called with the user input string.
	 * If the object supports hashistory than the input string will be added to the readline_add_history
	 */
	public function readline() : void {
		
		do {
			$this->menu();
			$string = readline(($this->prompt !== null ? $this->prompt : '# '));
			$this->handleInput(filter_var($string, FILTER_SANITIZE_STRING, array('flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)));
			if ($this->hasHistory) {
				readline_add_history($string);
			}
		} while ($string !== $this->terminationString);
	}
	
	public function redraw() : void {
		readline_redisplay();
	}
	
	/**
	 * @param String $text output text
	 * @param number $fmt format of the text
	 * 		1-bold, 2-dim, 4-underline, 5-blink, 7-inverted, 8-hidden
	 * @param number $textColor color of the text
	 * @param number $backgroundColor color of the background
	 *  Color	Foreground	Background
	 * 	black	30			40
	 *	red		31			41
	 *	green	32			42
	 *	yellow	33			43
	 *	blue	34			44
	 *	magenta	35			45
	 *	cyan	36			46
	 *	white	37			47
	 * @return string
	 */
	public function text(string $text, int $fmt = 0, int $textColor = 37, int $backgroundColor = 40) : string {
		if (!in_array($fmt, array(0,1,2,4,5,7,8))) {
			\common\logging\Logger::obj()->write('Output format may not be supported', 1);
		}
		if (!in_array($textColor, range(30, 37))) {
			\common\logging\Logger::obj()->write('Invalid color, '.$textColor.', should be between 0 and 256');
			$textColor = '';
		} else {
			$textColor = ';'.$textColor;
		}
		if (!in_array($backgroundColor, range(40, 47))) {
			\common\logging\Logger::obj()->write('Invalid color, '.$backgroundColor.', should be between 0 and 256');
			$backgroundColor = '';
		} else {
			$backgroundColor = ';'.$backgroundColor;
		}
		
		return chr(27).'['.$fmt.$textColor.$backgroundColor.'m'.$text.chr(27).'[0m';
	}
}