<?php
/**
 * PageHelper - Class
 * A object to hold all the variables that are contained within a status
 */
class PageHelper {

	const RESPONSE_JSON = 'JSON';
	const RESPONSE_REDIR = 'REDIR';
	const RESPONSE_PRINT = 'PRINT';

	const INCHES_PER_CENTIMETER = 0.3937;


	public static function queryStringToAssoc($query_string){
		$result=array();
		if($query_string!=''){
			$qs_arr = explode('&',$query_string);
			foreach($qs_arr as $key_val){
				$kv = explode('=',$key_val);
				if(count($kv) == 2) {
					$result[$kv[0]]=$kv[1];
				}
			}
		}
		return $result;
	}


	/**
	 * checks a variables and if not matching, defaults to a given value
	 *
	 * @param mixed $variable
	 * @param mixed $default_value
	 * @param string $type
	 * @return mixed
	 */
	public static function setDefault(&$variable,$default_value,$type=''){
		return self::assureVariable($variable,$type)?$variable:$default_value;
	}

	/**
	 * checks to see if a passed in variable meets certain criteria
	 *
	 * @param mixed $variable
	 * @param mixed $type
	 * @return boolean
	 */
	public static function assureVariable(&$variable,$type=''){
		//check that variable exists
		if(!isset($variable) || $variable==''){
			return FALSE;
		}

		//if list of values for type, check if it's present!
		if(is_array($type)){
			return in_array($variable,$type);
		}

		//if type is a string, check for certain strings!
		switch($type){
			case 'INT':
				return ctype_digit((string) $variable);
				break;
			case 'STR':
				return is_string($variable);
				break;
			case 'ARR':
				return is_array($variable);
				break;
			default:
				return TRUE;
				break;
		}
	}

	/**
	 * Checks if a number is between the min and max values (inclusive)
	 *
	 * @param int $n
	 * @param int $min
	 * @param int $max
	 * @return bool
	 */
	public static function between($n, $min, $max){
		return ($min <= $n && $n <= $max);
	}

	public static function goToPage($page){
		header('Location: '.$page);
		die();
	}

	public static function arrayToOptions($array,$selected=''){
		$return='';
		if(is_array($array)){
			foreach($array as $value=>$label){
				$return.='<option value="'.$value.'">'.$label.'</option>';
			}
		}
		if($selected!=''){
			$return=str_replace(' value="'.$selected.'"',' value="'.$selected.'" selected="selected"',$return);
		}
		return $return;
	}

	public static function arrayPipefy($array){
		$return='';
		if(is_array($array)){
			foreach($array as $value=>$label){
				$return.=$label.'|'.$value."\n";
			}
		}
		return $return;
	}

	public static function truncate($string,$length=15){
		if($length>0 && strlen($string)>$length){
			$string=substr($string,0,$length-3).'...';
		}
		return $string;
	}

	/**
	 * Clans up and removes all empty values from a csv string
	 *
	 * @param string $value
	 * @return string
	 */
	public static function cleanCSV($value){
		return implode(',', array_filter(array_map('trim', explode(',', $value))));
	}

	/**
	 * will do appropriate redirection
	 * if a facebook object is passed in and we are doing a redirect,
	 * it will use the facebook redirect method
	 *
	 * @param string $response
	 * @param string $response_type
	 * @param int $platform
	 */
	public static function respond($response, $response_type=self::RESPONSE_REDIR){
		if($response_type==self::RESPONSE_REDIR){
			self::goToPage($response);
		}elseif($response_type==self::RESPONSE_PRINT){
			die($response.'');
		}elseif($response_type==self::RESPONSE_JSON){
			die(json_encode($response).'');
		}
	}

	public static function kickOffThankYouAlert($page){
		if ($page != 'application') { 
			$page = "inquiry";	
		}
		
		echo '<script language="javascript" type="text/javascript">
				alert(\'Thank you for your submitted '.$page .'. An Hamiliton College of the Bahamas Representative will contact you shortly.\');
			</script>'
		;
	}

	/**
	 * Validate email is only letters, numbers, _, -, and .
	 * Allow unlimited subdomains so long as final is 2-6 char in length
	 * @return boolean TRUE if valid False if not valid
	 */
	public static function isValidEmail($email = '') {
		return (preg_match("/^[A-Za-z0-9\\.\\_\\-]+@[A-Za-z0-9\\.\\_\\-]+\.[A-Za-z0-9\\_\\-]{2,6}$/", $email)) ? true : false;
	}

	public static function removeUnderscoreUpperCase($string){
		return ucwords(str_replace('_',' ',$string));
	}


	public static function debug($type = 'log', $append = '', $options = array('file','line','function'), $nl = "   \n") {
		$backtrace = debug_backtrace();
		$output = ($append == '') ? $append : $append . $nl;

		array_shift($backtrace);
		foreach($backtrace as $entry) {
			$output_piece = '';
			foreach($options as $option) {
				if(is_array($entry[$option])){
					$entry[$option] = print_r($entry[$option],true);
				}
				$output_piece .= strtoupper($option) . ":" . $entry[$option] . " ";
			}

			if($type == 'log') {
				error_log($output_piece);
			}

			$output_piece .= $nl;
			$output .= $output_piece;
		}

		if($type == 'print') {
			print($output);
		}

		if($type=='log' && $append!=''){
			error_log($append);
		}

		return $output;
	}

	public static function xml2array($contents, $get_attributes = 1, $priority = 'tag') {
		if(!function_exists('xml_parser_create')) {
			return array();
		}
		$parser = xml_parser_create('');

		$xml_values = null;

		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if(!$xml_values)
			return; //Hmm...
		$xml_array = array();
		$parent = array();

		$current = & $xml_array;
		$repeated_tag_index = array();
		foreach($xml_values as $data) {
			unset($attributes, $value);
			extract($data);
			$result = array();
			$attributes_data = array();
			if(isset($value)) {
				if($priority == 'tag')
					$result = $value;
				else
					$result['value'] = $value;
			}
			if(isset($attributes) and $get_attributes) {
				foreach($attributes as $attr => $val) {
					if($priority == 'tag')
						$attributes_data[$attr] = $val;
					else
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}
			if($type == "open") {
				$parent[$level - 1] = & $current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) {
					$current[$tag] = $result;
					if($attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current = & $current[$tag];
				} else {
					if(isset($current[$tag][0])) {
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					} else {
						$current[$tag] = array(
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if(isset($current[$tag . '_attr'])) {
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current = & $current[$tag][$last_item_index];
				}
			} elseif($type == "complete") {
				if(!isset($current[$tag])) {
					$current[$tag] = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if($priority == 'tag' and $attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
				} else {
					if(isset($current[$tag][0]) and is_array($current[$tag])) {
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					} else {
						$current[$tag] = array(
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if(isset($current[$tag . '_attr'])) {
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset($current[$tag . '_attr']);
							}
							if($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			} elseif($type == 'close') {
				$current = & $parent[$level - 1];
			}
		}
		return ($xml_array);
	}

	public static function array2simplexml($array = array(), $capital_tags = false, $child = false){
		if($capital_tags){
			$xml = ($child) ? '' : '<XML>' . "\n";
		} else {
			$xml = ($child) ? '' : '<xml>' . "\n";
		}

		foreach($array as $tag => $value){
			$key = $tag;
			if (is_numeric($tag)){
				$key = 'node_' . $tag;
			} else {
				$key = preg_replace('/[^a-z]/i', '', $tag);
			}
			if($capital_tags){
				$key = strtoupper($key);
			}
			$xml .= "<{$key}>";
			if(is_array($value)){
				$xml .= "\n" . self::array2simplexml($value, $capital_tags, true);
			} else {
				$xml .= $value;
			}
			$xml .= "</{$key}>\n";
		}

		if($capital_tags){
			$xml .= ($child) ? '' : '</XML>';
		} else {
			$xml .= ($child) ? '' : '</xml>';
		}

		return $xml;
	}


	public static function scalarToTable($table,$title=''){
		$table_start = '<table cellspacing="0" cellpadding="0">';
		$table_head = '';
		$table_body = '';
		$table_end = '</table>';

		foreach($table as $row_label=>$row_data){
			if(is_array($row_data)){
				$row_data = print_r($row_data,true);
			}elseif(!is_string($row_data)){
				$row_data = round($row_data,2);
			}
			$table_body.='<tr><td>'.$row_label.'</td><td>'.$row_data.'</td></tr>';
		}
		$table_start .= '<tr><td colspan="2" style="font-weight:bold;text-align:center;">'.$title.'</td></tr>';
		return $table_start.$table_body.$table_end;
	}

	public static function arrayToTable($table,$title=''){
		$table_start = '<table cellspacing="0" cellpadding="0">';
		$table_head = '';
		$table_body = '';
		$table_end = '</table>';

		foreach($table as $row_label=>$row_data){
			//note that we reset the head each time
			$col_count = 0;
			$table_head = '<tr>';
			$table_body .= '<tr>';
			foreach($row_data as $column_label=>$column_data){
				$col_count++;
				$table_head.='<th>'.$column_label.'</th>';
				if(is_array($column_data)){
					$column_data = print_r($column_data,true);
				}elseif(!is_string($column_data)){
					$column_data = round($column_data,2);
				}
				$table_body.='<td>'.$column_data.'</td>';
			}
			$table_head .= '</tr>';
			$table_body .= '</tr>';
		}
		$table_start .= '<tr><td colspan="'.$col_count.'" style="font-weight:bold;text-align:center;">'.$title.'</td></tr>';
		return $table_start.$table_head.$table_body.$table_end;
	}

	public static function arrayColSort($a, $b, $column, $asc = false){
		$sign = $asc ? 1 : -1;
		$a = $a[$column]*$sign;
		$b = $b[$column]*$sign;

		if ($a == $b) {
	        return 0;
	    }
	    return ($a < $b) ? -1 : 1;
	}

	/**
	 * will index a numeric array by the key provided
	 *
	 * @param array $array
	 * @param string $key
	 * @return array
	 */
	public static function indexArrayByColumnValue(array $array,$key, $unset_key_col = false){
		$assoc = array();
		foreach($array as $row){
			if(!isset($row[$key])){
				continue;
			}
			$new_key = $row[$key];
			if($unset_key_col){
				unset($row[$key]);
			}
			$assoc[$new_key] = $row;

		}
		return $assoc;
	}

	/**
	 * Converts a number to a ordinal version of itself
	 *
	 * @param int $n
	 * @return string
	 */
	public static function ordinalize($n) {
		$return = $n;
		if($n > 0) {
			$last_two_digits = $n % 100;
			if(11 <= $last_two_digits && $last_two_digits <= 13) {
				$return = $n . 'th';
			} else {
				switch ($n % 10) {
					case 1:
						$return = $n . 'st';
						break;
					case 2:
						$return = $n . 'nd';
						break;
					case 3:
						$return = $n . 'rd';
						break;
					default :
						$return = $n . 'th';
						break;
				}
			}
		}
		return $return;
	}

	/**
	 * Highlights phrases in a text block
	 *
	 * @param string $text
	 * @param array $phrases
	 * @param string $wrapper
	 * @return string
	 */
	public static function highlightText($text, array $phrases, $wrapper = '<span class="highlight">$1</span>'){
		if(empty($phrases)){
			return $text;
		}
		return preg_replace('/(' . str_replace('/', '\\/', implode('|', array_map('preg_quote', $phrases))) . ')/', $wrapper, $text);
	}

	/**
	 * Highlights phrases in a text block
	 *
	 * @param string $text
	 * @param array $phrases
	 * @param string $wrapper
	 * @return string
	 */
	public static function linkifytText($text, array $phrases, $wrapper = '<span class="highlight">$1</span>'){
		if(empty($phrases)){
			return $text;
		}
		$callback = create_function('$matches', 'return PageHelper::linkifyTextCallback($matches, "' . addslashes($wrapper) . '");');
		return preg_replace_callback('/(' . str_replace('/', '\\/', implode('|', array_map('preg_quote', $phrases))) . ')(\s*,|\s*$|\s*\<)/', $callback, $text);
	}

	/**
	 * Callback function for linkifytText
	 *
	 * @param array $matches
	 * @param string $wrapper
	 * @return string
	 */
	public static function linkifyTextCallback($matches, $wrapper){
		$wrapper = str_replace(array('$1', '$2'), array('%1$s', '%2$s'), $wrapper);
		return sprintf($wrapper, $matches[1], htmlentities($matches[1])) . $matches[2];
	}

	/**
	 * Detects if request is AJAX
	 * @return bool
	 */
	public static function isAjax(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Ensures that a request param is never empty.
	 * Allows you to set a default value
	 * @param string $param //param name e.g. $_POST['param'] or $_GET['param']
	 * @param mixed $default_value
	 * @param string $type
	 * @return mixed
	 */
	public static function getParam($param, $default_value=NULL,$type = ''){
		return self::setDefault($_REQUEST[$param],$default_value,$type);
	}

	/**
	 * alias for getParam
	 *
	 * @param string $param
	 * @param mixed $default_value
	 * @param string $type
	 * @return mixed
	 */
	public static function getRequest($param, $default_value=NULL,$type = ''){
		return self::getParam($param, $default_value, $type);
	}

	/**
	 * detects if a request param is empty.
	 *
	 * @param string $param
	 * @param string $type
	 * @return boolean
	 */
	public static function assureRequest($param, $type = ''){
		return self::assureVariable($_REQUEST[$param],$type);
	}

	/**
	 * detects if the $_REQUEST param is present!
	 *
	 * @param string $param
	 * @return boolean
	 */
	public static function existsRequest($param){
		return array_key_exists($param,$_REQUEST);
	}
	
	public static function buildNav() {
		$nav_html = '<ul>';
		foreach (self::buildNavArray() as $id => $nav_elem) { 
			if (isset($nav_elem['url'])) {
				$url = 	$nav_elem['url'];
			} else {
				$url = Config::$CONFIG['url'].'?section='.$id;
			}
			$nav_html .= '<li><a class="'.$id.'" href="'.$url.'">'.$nav_elem['name'].'</a>';
			if (!empty($nav_elem['docs']))	{
				$nav_html .= '<ul>';
				foreach ($nav_elem['docs'] as $doc_id => $doc_elem) {
					if (isset($doc_elem['url'])){
						$doc_url = $doc_elem['url'];
					} else  {
						 $doc_url = $url.'&doc='.$doc_id;
					}
					$nav_html .= '<li><a href="'.$doc_url.'">'.$doc_elem['name'].'</a>';
					if (!empty($doc_elem['divisions'])) {				
						$nav_html .= '<ul>';
						foreach($doc_elem['divisions'] as $div_id => $div_elem) {
							$doc_url = $url.'&doc='.$div_id;
							$nav_html .= '<li><a href="'.$doc_url.'">'.$div_elem['name'].'</a>';
							if (!empty($div_elem[$div_id]['degrees'])) {							
								$nav_html .= '<ul>';
								foreach ($div_elem[$div_id]['degrees'] as $deg_id => $deg_elem) {
									$doc_url = $url.'&doc='.$deg_id;
									$nav_html .= '<li><a href="'.$doc_url.'">'.$deg_elem['name'].'</a>';
								}		
								$nav_html .= '</ul></li>';
							}
						}
						$nav_html .= '</ul></li>';
					} elseif (!empty($doc_elem['degrees'])) {							
						$nav_html .= '<ul>';
						foreach ($doc_elem['degrees'] as $deg_id => $deg_elem) {							
							$doc_url = $url.'&doc='.$deg_id;
							$nav_html .= '<li><a href="'.$doc_url.'">'.$deg_elem['name'].'</a>';
						}		
						$nav_html .= '</ul></li>';
					} elseif (!empty($doc_elem['elements'])){
						$nav_html .= '<ul>';
						foreach ($doc_elem['elements'] as $elem_id => $elem_elem) {							
							$doc_url = $url.'#'.$elem_id;
							$nav_html .= '<li><a href="'.$doc_url.'">'.$elem_elem['name'].'</a>';
						}		
						$nav_html .= '</ul></li>';
					}
				}
				$nav_html .= '</ul></li>';
			}
		}
		$nav_html .= '</ul>';
		return $nav_html;
		
	}
	
	private static function buildNavArray(){	
		return array(
			'home' => array(
				'name' => 'Home',
				'docs' => array(),
			),
			'academics' => array(
				'name' => 'Academics',
				'docs' => array(
					'college_christian_studies' => Courses::$christain_studies,
					'college_guidance' => Courses::$guidance_physc,
					'center_individual_excellence' => Courses::$center_individual_excellence,
				),
			),
			'catalog' => array(
				'name' => 'Catalog',
				'docs' => array(
					'general_information' => array(
						'name' => 'General Information',
						'elements' => array(
							'mission' => array('name' => 'Mission Statement'),
							'objectives' => array('name' => 'Educational Objectives'),	
							'method' => array('name' => 'Instructional Method'),	
							'residence' => array('name' => 'Residence and Time Requirements'),	
							'authority' => array('name' => 'Operating Authority'),	
							'library' => array('name' => 'Online Services and Library Facilities'),	
							'legal' => array('name' => 'Statement of Legal Control'),	
						)
					),
					'school_information' => array('name' => 'School Information'),
					'admissions' => array('name' => 'Admissions'),
					'education_requirements' => array('name' => 'Education Requirements'),
					'tuition' => array('name' => 'Tuition, Fees & Tools'),
					'school_policy' => array('name' => 'School Policy'),
					'alumni' => array('name' => 'Alumni'),
				),
			),
			'request_information' => array(
				'name' => 'Request Information',
				'docs' => array(),
			),
			'application' => array(
				'name' => 'Application',
			),
			'scholarship_information' => array(
				'name' => 'Scholarship Information',
				'docs' => array(), 
			),
			'alumni' => array(
				'name' => 'Alumni'
			),
			'cie' => array(
				'name' => 'CIE'
			)
		); 
	}
}
