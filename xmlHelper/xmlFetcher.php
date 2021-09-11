<?php

// this fetches the xml value with xml xpath
class xmlFetcher {
	
	public $domxPath;

	public function __construct($xml) {
		$dom = new DOMDocument();
		$dom->loadXML($xml);
		if (!$dom->loadXML($xml)) {
			trigger_error(print_r('Error parsing XML', true), E_USER_ERROR);
		}
		$this->domxPath = new DOMXPath($dom);
	}

	function getElement($xPath) {

        $result = $this->domxPath->query($xPath);
        $value = trim($result->item(0)->nodeValue);

        return $value;

    }
	
	function count($xPath) {
		return $this->domxPath->query($xPath)->length;
	}

}

?>