<?php

require_once 'xmlMapper.php';

// creates a DOMDocument obj and a root node 
class xmlRoot extends xmlMapper {

    public $dom;
	public $node;
	public $valueNodes;

	function __construct($nodeName) {

		$this->dom = new DOMDocument('1.0', 'utf-8');
		$this->node = $this->dom->createElement($nodeName);
		$this->dom->appendChild($this->node);
		$this->dom->preserveWhiteSpace = false;
		$this->dom->formatOutput = true;

	}

	function save() {
		return $this->dom->saveXML();
	}

}

?>