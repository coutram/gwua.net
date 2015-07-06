<?php

/**
 * FileTemplate - Class
 *
 * This subclass of Template has all the functionality of Template, with the constructor
 * setting $template to the contents of a passed in filename.
 */
class FileTemplate extends Template{

	/**
	 * Sets the $template string.
	 *
	 * Sets $template to the contents of a passed in filename.
	 * @param string $filename
	 */
	public function __construct($filename = ''){
		$this->setTemplate($filename);
	}

	/**
	 * sets the $template string for usage
	 * can be called lazily
	 *
	 * @param string $filename
	 */
	public function setTemplate($filename){
		if ($filename != '' && file_exists('templates/'.$filename)) {
			$fcontents = file_get_contents('templates/'.$filename);
			$this->template = $fcontents;
		} else {
			$this->template = '';
		}
	}

	/**
	 * will return a filename based on a defined action,
	 * if it can't find a named template for the action, it just slaps .html onto the action
	 *
	 * @param string $action
	 * @return string
	 */
	public static function getNamedTemplate($action){
		return $action.'.html';
	}


}