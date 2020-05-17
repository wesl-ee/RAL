<?php namespace RAL;
class PreviewReply extends Reply {
	function __construct($row) {
		$this->Created = date('Y-m-d G:i:s');
		$this->Year = $row['year'];
		$this->Content = $row['content'];
		$this->Continuity = $row['continuity'];
		$this->Topic = $row['topic'];
		$this->UserIdentity = "ID - ID";
	}
	public function title() {
		return "[{$this->Continuity}/{$this->Year}/$this->Topic/Post Preview]";
	}
}
