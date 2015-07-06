<?php

/**
 * StringTemplate - Class
 *
 * This subclass of Template has all the functionality of Template, with the constructor
 * setting $template to a passed in string.
 */
class StringTemplate extends Template{

	/**
	 * Sets the $template string.
	 *
	 * Sets $template to the passed in string, $template_string
	 * @param string $template_string
	 */
	public function __construct($template_string = ''){
		$this->setTemplate($template_string);
	}

	/**
	 * sets the $template string for usage
	 * can be called lazily
	 *
	 * @param string $template_string
	 */
	public function setTemplate($template_string){
		$this->template = $template_string;
	}
	
	/**
	 * Replace a group of template vars
	 * @param array $new_vars
	 */
	public function replaceTemplateVars($new_vars = array()){
		$this->template_data = $new_vars;
	}
}