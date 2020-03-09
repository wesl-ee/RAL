<?php namespace JBBCode\visitors;

class SmileyVisitor implements \JBBCode\NodeVisitor
{

	const SMILEYS = [
		':grin:' => '<img alt=":grin:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Grin.gif" />',
		':smile:' => '<img alt=":smile:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Smile.gif" />',
		':wow:' => '<img alt=":wow:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Wow.gif" />',
		':beatup:' => '<img alt=":beatup:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Beatup.gif" />',
		':roll:' => '<img alt=":roll:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Roll.gif" />',
		':mad:' => '<img alt=":mad:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Mad.gif" />',
		':frown:' => '<img alt=":frown:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Frown.gif" />',
		':sick:' => '<img alt=":sick:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Sick.gif" />',
		':yes:' => '<img alt=":yes:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Yes.gif" />',
		':music:' => '<img alt=":music:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Music.gif" />',
		':think:' => '<img alt=":think:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Think.gif" />',
		':nida:' => '<img alt=":nida:" src="' .
			CONFIG_WEBROOT .
			'res/smiley/Nida.gif" />' ];


	function visitDocumentElement(\JBBCode\DocumentElement $documentElement) {
		foreach($documentElement->getChildren() as $child)
			$child->accept($this);
	}

	function visitTextNode(\JBBCode\TextNode $textNode) {
		$textNode->setValue(str_replace(array_keys(self::SMILEYS), 
			array_values(self::SMILEYS),
			$textNode->getValue()));
	}

	function visitElementNode(\JBBCode\ElementNode $elementNode) {
		if ($elementNode->getCodeDefinition()->parseContent())
			foreach ($elementNode->getChildren() as $child)
				$child->accept($this);
	}
}
