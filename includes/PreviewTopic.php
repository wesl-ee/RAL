<?php namespace RAL;
class PreviewTopic extends Reply {
	function __construct($row) {
		$this->Created = date('Y-m-d G:i:s');
		$this->Year = date('Y');
		$this->Content = $row['content'];
		$this->Continuity = $row['continuity'];
	}
	public function title() {
		return "[{$this->Continuity}/{$this->Year}/New Topic]";
	}
	public function resolve() { return false; }
}
