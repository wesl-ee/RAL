<?php namespace RAL;
class Theme {
	public $name;

	public function __construct($name) {
		$this->name = $name;
	}
	public function css() {
		$WROOT = CONFIG_WEBROOT;
		return "{$WROOT}css/{$this->name}/style.css";
	}
}
