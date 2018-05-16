<?php namespace JBBCode\visitors;

class LineBreakVisitor implements \JBBCode\NodeVisitor
{

	function visitDocumentElement(\JBBCode\DocumentElement $documentElement) {
		foreach($documentElement->getChildren() as $child)
			$child->accept($this);
	}

	function visitTextNode(\JBBCode\TextNode $textNode) {
		$textNode->setValue(nl2br($textNode->getValue()));
	}

	function visitElementNode(\JBBCode\ElementNode $elementNode) {
		if ($elementNode->getCodeDefinition()->parseContent())
			foreach ($elementNode->getChildren() as $child)
				$child->accept($this);
	}
}
