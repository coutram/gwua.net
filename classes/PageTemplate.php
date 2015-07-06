<?php
/**
 * PageTemplate - Class
 *
 * This subclass of FileTemplate does something
 */
class PageTemplate extends FileTemplate{

	const MASTER_GROUP = 0;
	const PLUS_GROUP = 1;

	private $externals = array(
		'css' => array(
			self::MASTER_GROUP => array(),
			self::PLUS_GROUP => array(),
		),
		'javascript' => array(
			self::MASTER_GROUP => array(),
			self::PLUS_GROUP => array(),
		)
	);

	private $sub_template;
	private $flags = array();
	private $partials = array();
	private $response_type = PageHelper::RESPONSE_PRINT;

	public function __construct($filename = ''){
		parent::__construct($filename);
	}

	/**
	 * set the page content
	 * @param Template $template
	 * @return PageTemplate $this
	 */
	public function setSubTemplate(Template $template){
		$this->sub_template = $template;
		return $this;
	}

	/**
	 * set the page title
	 * @param string
	 * @return PageTemplate $this
	 */
	public function setTitle($title){
		$this->title = $title;
		return $this;
	}

	/**
	 * add a css file to the template
	 * @param string/array $filenames
	 * @param mixed group file should be cached with
	 * @return PageTemplate $this
	 */
	public function addCSS($filenames, $group = self::PLUS_GROUP){
		return $this->addExternal('css',$filenames,$group);
	}

	/**
	 * add a js file to the template
	 * @param string/array $filenames
	 * @param mixed group file should be cached with
	 * @return PageTemplate $this
	 */
	public function addJS($filenames, $group = self::PLUS_GROUP){
		return $this->addExternal('javascript',$filenames,$group);
	}

	/**
	 * add css or js files to the template
	 * @param string $type css/js
	 * @param string/array $filenames list of files
	 * @param mixed $group external grouping files will get minized/cached with
	 * @return PageTemplate $this
	 */
	private function addExternal($type, $filenames, $group){
		if(!is_array($filenames)){
			$filenames = array($filenames);
		}
		foreach($filenames as $filename){
			$this->externals[$type][$group][] = $filename;
		}

		return $this;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @return PageTemplate $this
	 */
	public function addFlag($key,$value){
		$this->flags[$key] = $value;
		return $this;
	}

	/**
	 * add an array of flags to the list
	 * @param array $flags key => value
	 * @return PageTemplate $this
	 */
	public function mergeFlags($flags){
		$this->flags = array_merge($this->flags,$flags);
		return $this;
	}

	/**
	 * add an html file to the bottom of the template
	 * @param string $filename
	 * @return PageTemplate $this
	 */
	public function addPartial($filename){
		$this->partials[] = $filename;
		return $this;
	}

	private function parsePartials(){
		$result = '';
		foreach($this->partials as $filename){
			if(!file_exists('templates/partials/'.$filename)){
				throw new Exception('included non-existent partial');
			}
			$result .= file_get_contents('templates/partials/'.$filename);
		}
		return $result;
	}

	private function setParsedFlags(){
		$this->flags_data = json_encode($this->flags);
	}

	private function parseSubTemplate(){
		return $this->sub_template->parseTemplate();
	}

	private function setParsedExternals(){
		foreach($this->externals as $content_type => $groups){
			$tag_name = $content_type.'_tag';
			$tag = '';
			foreach($groups as $group=>$files){
				if(!empty($files)){
					$tag .= StaticContentParser::generateContentTag($content_type, $files, Config::$CONFIG['static_content_version'], Config::$CONFIG['static_content_minify'], false);
				}
			}
			$this->$tag_name = $tag;
		}
	}

	public function parseTemplate(){
		$template_data = array(
			'page_content' => $this->parseSubTemplate() . $this->parsePartials()
		);
		$this->template_data = array_merge($template_data,$this->template_data);
		$this->setParsedExternals();
		$this->setParsedFlags();
		return parent::parseTemplate();
	}

	public function display(){
		PageHelper::respond($this->parseTemplate(),$this->response_type);
	}
}
?>