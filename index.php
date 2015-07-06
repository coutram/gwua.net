<?php
require_once('include.php');

$master_template = new FileTemplate('template.template');
$master_template->version = time();
$master_template->nav_bar = PageHelper::buildNav();
$master_template->url = Config::$CONFIG['url'];
$section = PageHelper::getParam('section', 'index');
$template = new FileTemplate(FileTemplate::getNamedTemplate($section));
$template->url = Config::$CONFIG['url'];

//document id
$doc = PageHelper::getParam('doc');

switch ($section) {
	case 'academics':
		if (!is_null($doc)){
			$template->setTemplate($section.'/'.FileTemplate::getNamedTemplate($doc));
			$right_column = new FileTemplate('contact.html');
			$template->right_column = $right_column->parseTemplate();
		}
		break;
	case 'catalog':
		if(!is_null($doc)){
			$template->setTemplate($section.'/'.FileTemplate::getNamedTemplate($doc));
		}
		break;
	case 'request_information':
	case 'application':
	case 'scholarship_information':
	case 'about_us':
	case 'deans':
	case 'contact':
	case 'paypal':
	case 'cie':
		break;
	case 'alumni':
		$template->setTemplate('catalog/'.FileTemplate::getNamedTemplate($section));
		break;
	case 'index':
		$right_column = new FileTemplate('testimonials.html');
		$template->right_column = $right_column->parseTemplate();
		break;
	default:
		$template->setTemplate('index.html');
		$right_column = new FileTemplate('testimonials.html');
		$template->right_column = $right_column->parseTemplate();
		break;
		
		break;
}

$master_template->content = $template->parseTemplate();
echo $master_template->parseTemplate();
