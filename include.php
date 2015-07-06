<?php
function __autoload($class_name) {
	$class_name = str_replace('_','/',$class_name);
    require_once 'classes/'.$class_name . '.php';
}
