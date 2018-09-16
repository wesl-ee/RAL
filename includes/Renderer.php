<?php namespace RAL;
include "{$ROOT}includes/Theme.php";
class Renderer {
	public $Title;
	public $Desc;
	public $Theme;
/*	public $Language;*/

	public function setTheme($name = null) {
		$this->Theme = new Theme($name);
	}
/*	public function setLanguage($language) {
		$this->Language = $language;
	}*/
	public function themeFromCookie($cookie) {
		$theme = $cookie['Theme'];
		if ($theme && in_array($theme, CONFIG_THEMES))
			$this->setTheme($theme);
		else
			$this->setTheme(CONFIG_DEFAULT_THEME);
	}
	public function configForm() {
		print <<<HTML
<form method=POST>
	<h2>Site Theme</h2>
HTML;
		foreach (CONFIG_THEMES as $theme) {
			$q_theme = htmlentities($theme, ENT_QUOTES);
			$h_theme = htmlentities($theme);
			if (!strcmp($theme, $this->Theme->name)) print <<<HTML
	<input type=radio name=Theme id="theme-$q_theme" value="$q_theme" checked>
	<label for="Theme-$q_theme">$h_theme</label><br />
HTML;
			else print <<<HTML
	<input type=radio name=Theme id="theme-$q_theme" value="$q_theme">
	<label for=Theme-"$q_theme">$h_theme</label><br />
HTML;
		}
/*		print <<<HTML
	<h2>Language</h2>
HTML;
		foreach (CONFIG_LANGS as $lang) {
			$q_lang = htmlentities($lang, ENT_QUOTES);
			$h_lang = htmlentities($lang);
			if (!strcmp($lang, $this->Language)) print <<<HTML
	<input type=radio name=Language id=Language-$q_lang
	value=$q_lang checked>
	<label for=Language-$q_lang>$h_lang</label>
HTML;
			else print <<<HTML
	<input type=radio name=Language id=Language-$q_lang
	value=$q_lang>
	<label for=Language-$q_lang>$h_lang</label>
HTML;
		}*/
		print <<<HTML
	<input class=button type=submit value=Submit>
</form>
HTML;
	}

	public function putHead() {
	$WROOT = CONFIG_WEBROOT;
	$LOCALROOT = CONFIG_LOCALROOT;
	@$themefile = $this->Theme->css();
	print
<<<HTML
	<meta name=viewport content="width=device-width,
	maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="${WROOT}css/Base.css">
	<link rel=icon type="image/x-icon" href="${WROOT}favicon.gif">

HTML;
	if (@$themefile) {
		print <<<HTML
	<link rel=stylesheet href="$themefile">

HTML;
	} if (isset($this->Title)) {
		$title = htmlspecialchars($this->Title);
		print <<<HTML
	<title>$title - RAL Neo-Forum Textboard</title>

HTML;
	} if (isset($this->Desc)) {
		$desc = htmlspecialchars($this->Desc, ENT_QUOTES);
		print <<<HTML
	<meta name=description content="$desc"/>

HTML;
	}
/*	if (@file_exists("${LOCALROOT}www/js/themes/$theme.js")) {
		print <<<HTML
	<script src="${WROOT}js/themes/$theme.js"></script>

HTML;*/
	}
}
