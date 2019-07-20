<?php namespace RAL;
class NewsItem {
	public $Id;
	public $Created;
	public $Author;
	public $Email;
	public $Title;
	public $Content;

	public $Parent;

	const TYPE = 'News';

	public function __construct($row, $parent) {
		$this->Id = $row['Id'];
		$this->Created = $row['Created'];
		$this->Author = $row['Author'];
		$this->Email = $row['Email'];
		$this->Title = $row['Title'];
		$this->Content = $row['Content'];

		$this->Parent = $parent;
	}

	/* :3 */
	public function Type() { return static::TYPE; }
}
