<?php

require_once 'xmlNode.php';

// main xml mapper that creates element as parent node 
// maps value to its parent node and appending to the child elements
class xmlMapper {

	public $dom;
	public $node;
	public $valueNodes;

	function __construct() {	
		
		$this->valueNodes = array();
		$this->dom = $dom;
		$this->node = $node;

	}

	// parent node
	function newNode($nodeName) {

		$newNode = $this->dom->createElement($nodeName);
		$this->node->appendChild($newNode);
        return new xmlNode($this->dom, $newNode);

	}

	// child node and to append node value 
	function newValueNode($nodeName, $nodeValue) {

        $this->nodeName = $nodeName; 

		if ($nodeValue === false) {
			$nodeValue = 'false';
		} elseif ($nodeValue === true) {
			$nodeValue = 'true';
		}

		$newNode = $this->dom->createElement($nodeName, $nodeValue);
        $this->node->appendChild($newNode);
        
        $this->valueNodes[] = [$this->nodeName];

	}

}


?>