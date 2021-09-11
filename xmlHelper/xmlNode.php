<?php

require_once 'xmlMapper.php';

// DomNode constructor
class xmlNode extends xmlMapper {
    
    public $dom;
	public $node;

	function __construct($dom, $node) {

		$this->dom = $dom;
		$this->node = $node;

	}

}




?>