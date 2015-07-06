<?php
/**
 * StaticContentParser - Class
 *
 * @author Wei Kin Huang
 * @name StaticContentParser
 */
class StaticContentParser {

	/**
	 * The document root of the files to be loaded
	 *
	 * @var string
	 */
	protected $doc_root = '';
	/**
	 * The files to be loaded in the path defined in $this->doc_root
	 *
	 * @var array
	 */
	protected $files = array();
	/**
	 * The template object that allows for the parsing of the files
	 *
	 * @var StringTemplate
	 */
	protected $template;
	/**
	 * The content to be outputted to the client
	 *
	 * @var string
	 */
	public $content;
	/**
	 * The mime type of the file to be outputted to the client
	 *
	 * @var string
	 */
	protected $content_type = 'text/plain';
	/**
	 * The local representation of the $_GET superglobal
	 *
	 * @var array
	 */
	protected $get_vars;

	/**
	 * Should we output on destruct? This is why you shouldn't have real functionality in destruct
	 *
	 * @ var boolean
	 */
	public $output_on_destruct = true;

	public static $STATIC_JAVASCRIPT_FILES = array(
		'jquery-1.3.1.min.js',
		'jquery-ui-1.7.1.custom.min.js',
		'jquery.simplemodal-1.2.3.min.js',
		'jquery.validate.1.5.2.min.js',
		'jquery.pronoun-0.1.0.js',
		'jquery.timeago.js',
		'jquery.Jcrop.min.js',
		'jquery.livequery-1.0.3.js',
		'jquery.autocomplete-1.0.2.js',
		'utils.js',
		'webtoolkit.aim.js',
		'gift-sender.js',
	);
	
	public static $STATIC_CSS_FILES = array(
		'ayi_temp.css',
		'template.css',
		'simplemodal.css',
		'jquery.Jcrop.css',
		'jquery.autocomplete-1.0.2.css',
		'jquery-ui-1.7.1.custom.css',
		'gift-sender.css',
	);
	
	public static $WINWEB_JAVASCRIPT_FILES = array(
		'jquery-1.3.1.min.js',
		'utils.js',
		'lbs.js',
	);
	
	public static $WINWEB_CSS_FILES = array(
		'whoisnear.css',
	);
	
	public static $WINMOBILE_JAVASCRIPT_FILES = array(
	);
	
	public static $WINMOBILE_CSS_FILES = array(
		'whoisnear.css',
	);
	
	
	
	/**
	 * Constructor that sets class variables
	 *
	 * @param string $doc_root
	 * @param string $content_type
	 * @param array $files
	 * @param array $get_vars
	 * @return StaticContentParser
	 */
	public function __construct($doc_root = '', $content_type = 'text/plain', $files = array(), &$get_vars) {
		$this->get_vars = $get_vars;
		$this->doc_root = $doc_root;
		$this->content_type = $content_type;
		$this->files = $files;
	}

	/**
	 * Destructor function that handles the logic to output the content to the client
	 *
	 */
	public function generateContent() {
		$this->loadCustomFiles();

		$this->template = new StringTemplate($this->getFileContents());
		$this->setTemplateVars();
		$translator = Translator::getInstance(PageHelper::setDefault($this->get_vars['lang'],'','STR'));
		$this->content = $this->template->parseTemplate();

		if(PageHelper::assureVariable($this->get_vars['minify'], 'INT') && $this->get_vars['minify'] == '1') {
			$this->minify();
		}
	}

	public function __destruct(){
		if(!$this->output_on_destruct){
			return;
		}

		$this->generateContent();
		$max_age=(86400*365);

		header("Content-type: {$this->content_type}; charset: utf-8");
      		header("Cache-Control: public");
       		header('Expires: ' . gmdate("D, d M Y H:i:s", (time() + $max_age)) . ' GMT');

		echo $this->content;
	}

	/**
	 * Function that gets the file contents of the files defined in $this->files
	 *
	 * @return string
	 */
	protected function getFileContents() {
		$contents = '';
		foreach($this->files as $file) {
			if(file_exists($this->doc_root . '/' . $file)) {
				$contents .= file_get_contents($this->doc_root . '/' . $file) . "\n";
			}
		}
		return $contents;
	}

	/**
	 * Minification function that handles the logic to minify a file in child classes
	 *
	 */
	protected function minify() {

	}

	/**
	 * Sets the template vars necessary to handle the parsing of the strings
	 *
	 */
	protected function setTemplateVars() {

	}

	/**
	 * This function loads custom files that were sent in through the $_GET variable
	 * use a |(pipe) to define directories
	 * usage => files=utils.js;jquery.js;autocomplete.js;premium|premium.css
	 * if the $_GET variable is sent in with 1, it will load the default files in addition to the requested files
	 *
	 */
	protected function loadCustomFiles() {
		if(PageHelper::assureVariable($this->get_vars['files'], 'STR')) {
			$additional_files = array();
			$file_items = explode(';', $this->get_vars['files']);
			foreach($file_items as $file) {
				$additional_files[] = basename($file);
			}

			//add ability to include the default files in addition to other files
			if(PageHelper::assureVariable($this->get_vars['add_default'], 'INT')) {
				switch ($this->get_vars['add_default']) {
					case '1' :
						$this->files = array_merge($this->files, $additional_files);
						break;
					case '2' :
						$this->files = array_merge($additional_files, $this->files);
						break;
					default :
						$this->files = $additional_files;
						break;
				}
			} else {
				$this->files = $additional_files;
			}
		}
	}

	/**
	 * Generates the css/javascript tag for use in templates
	 *
	 * @param string $type javascript or css
	 * @param array $files list of files to load,
	 * @param int $version version number of this file
	 * @param bool $minify
	 * @param int $add_default
	 * @return string
	 */
	public static function generateContentTag($type, $files = array(), $version = 1, $minify = false, $add_default = false) {
		$minify_string = '';
		if($minify) {
			$minify_string = '&minify=1';
		}
		$default_string = '';
		if($add_default) {
			$default_string = '&add_default=' . $add_default;
		}
		$version_string = 'v=' . $version;
		$files_string = '';

		switch ($type) {
			case 'js' :
			case 'javascript' :
				if(empty($files) || $files == self::$STATIC_JAVASCRIPT_FILES) {
					$files = array();
				}
				if(!empty($files)) {
					$files_string = '&files=' . implode(';', $files);
				}
				return sprintf('<script type="text/javascript" src="%1$sstatic.js.php?%2$s%3$s%4$s%5$s"></script>', Config::$CONFIG['js_url'], $version_string, $minify_string, $default_string, $files_string);
				break;
			case 'css' :
				if(empty($files) || $files == self::$STATIC_CSS_FILES) {
					$files = array();
				}
				if(!empty($files)) {
					$files_string = '&files=' . implode(';', $files);
				}
				return sprintf('<link rel="stylesheet" type="text/css" media="screen,projection" href="%1$sstatic.css.php?%2$s%3$s%4$s%5$s" />', Config::$CONFIG['css_url'], $version_string, $minify_string, $default_string, $files_string);
				break;
		}
		return '';
	}
}
