<?php namespace RAL;
include 'jBBCode/Parser.php';
include 'LineBreakVisitor.php';

class ResourceManager {
	public $raldb;
	public $bbparser;
	public $linebreakvisitor;

	function getdb() {
		if ($this->raldb) return $this->raldb;
		$dbh = mysqli_connect(
			CONFIG_RAL_SERVER,
			CONFIG_RAL_USERNAME,
			CONFIG_RAL_PASSWORD,
			CONFIG_RAL_DATABASE
		);
		mysqli_set_charset($dbh, 'utf8');
		$this->raldb = $dbh;
		return $this->raldb;
	}
	function getbbparser() {
		if ($this->bbparser) return $this->bbparser;

		$bbparser = new \jBBCode\Parser();
	        $urlValidator = new \JBBCode\validators\UrlValidator();

		/* [b] bold tag */
	        $builder = new \jBBCode\CodeDefinitionBuilder(
			'b',
			'<strong>{param}</strong>'
		);
		$bbparser->addCodeDefinition($builder->build());

		/* [i] emphasis tag */
	        $builder = new \jBBCode\CodeDefinitionBuilder(
			'i',
			'<em>{param}</em>'
		);
		$bbparser->addCodeDefinition($builder->build());

		/* [url] link tag */
		$builder = new \jBBCode\CodeDefinitionBuilder(
			'url',
			'<a href="{param}">{param}</a>'
		);
		$builder->setParseContent(false)->setBodyValidator(
			$urlValidator);
		$bbparser->addCodeDefinition($builder->build());

		/* [url=http://example.com] link tag */
		$builder = new \jBBCode\CodeDefinitionBuilder(
			'url',
			'<a href="{option}">{param}</a>'
		);
		$builder->setUseOption(true)->setParseContent(
			true)->setOptionValidator($urlValidator);
		$bbparser->addCodeDefinition($builder->build());

	        /* [color] color tag */
		$builder = new \jBBCode\CodeDefinitionBuilder(
			'color',
			'<span style="color: {option}">{param}</span>'
		);
		$builder->setUseOption(true)->setOptionValidator(
			new \JBBCode\validators\CssColorValidator());
		$bbparser->addCodeDefinition($builder->build());

		/* [spoiler] hidden tag */
	        $builder = new \jBBCode\CodeDefinitionBuilder(
			'spoiler',
			'<span class=spoiler>{param}</span>'
		);
		$bbparser->addCodeDefinition($builder->build());

		/* [aa] ASCII Art tag */
		$builder = new \jBBCode\CodeDefinitionBuilder(
			'aa',
			'<pre>{param}</pre>'
		);
		$builder->setParseContent(false);
		$bbparser->addCodeDefinition($builder->build());

		$this->bbparser = $bbparser;
		return $this->bbparser;
	}
	function getLineBreakVisitor() {
		if ($this->linebreakvisitor) return $this->linebreakvisitor;
		$this->linebreakvisitor =  new \JBBCode\Visitors\LineBreakVisitor();
		return $this->linebreakvisitor;
	}
}
