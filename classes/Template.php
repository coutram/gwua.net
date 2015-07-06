<?php

/**
 * Template - Class
 *
 * The base class for all classes that set variables for a template file or string.  The parsed Template is ultimately
 * used for display purposes, displaying an entire HTML page or a snippet of HTML.  Setting of variables
 * is accomplished with overwriting the __set function.  Subclasses only differ in where the string comes
 * from, an input file or a passed in string.  Subclasses may also overwrite.  Template's parseTemplate() function.
 * @todo create __set function to replace setTemplateVar
 */
class Template{
	/**
	 * Associative array of values to be replaced in the template.
	 *
	 * @var array
	 */
	protected $template_data = array();

	/**
	 * actual template where $template_data will be placed into
	 *
	 * @var string
	 */
	protected $template = '';
	
	/**
	 * Empty private constructor
	 *
	 * Constructor does nothing.  It's made private to force instantiation of Base classes
	 */
	private function __construct(){}

	
	/**
	 * Setter function for Template variables
	 *
	 * This function, overwriting __set, set's a variable in $template_date, with name, $field, and
	 * value, $value.  This $value will replace occurences of "$field" in $template when parseTemplate()
	 * is called
	 * @param string $field
	 * @param mixed $value
	 */
	final public function __set($field, $value){
		if(!is_array($value)){
			$this->template_data[$field] = $value;
		}
	}

	/**
	 * Setter function for Template data as arrays
	 *
	 * @param array $template_data
	 */
	final public function merge(array $template_data,$name_space=''){
		$name_space_prepend = $name_space ? $name_space.='.' : '';
		foreach($template_data as $key => $val){
			if(!is_array($val)){
				$this->template_data[$name_space_prepend.$key] = $val;
			}
		}
	}

	/**
	 * Setter function for Template data as arrays
	 *
	 * @param array $template_data an array of arrays of item descriptors
	 */
	final public function parseArray(array $template_data){
		$result = '';
		foreach ($template_data as $row){
			$this->reset();
			$this->merge($row);
			$result .= $this->parseTemplate();
		}
		return $result;
	}

	/**
	 * resetter function for Template data as arrays
	 */
	final public function reset(){
		$this->template_data = array();
	}

	/**
	 * Parses $template, making appropriate variable replacements
	 *
	 * For each key/value pair in $template_data, this function searches through $template for ##key##,
	 * replacing the occurence with ##value##.  Once this function has iterated through each element
	 * of $template_data, it returns the parsed template.  This function may be overwritten in subclasses,
	 * should template variables take a different form or a template engine, such as Smarty, is used.
	 * @return string
	 */
	public function parseTemplate(){
		$parsed_template = $this->template;
		foreach($this->template_data as $key => $val){
			$parsed_template = str_replace('##'.$key.'##', $val, $parsed_template);
		}

		return $parsed_template;
	}
}