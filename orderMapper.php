<?php

require_once 'xmlHelper/xmlFetcher.php';
require_once 'xmlHelper/xmlRoot.php';

// this class deals with the Orders xml
class orderMapper {
	
    // use xmlFecher to grab the node value from the orders xml
    // build an array based on the xml
    // specify the order number and return back the corresponding order array
    function xmlToArrayByOrder($ordersXml, $orderNo){

        $xml = new xmlFetcher($ordersXml);

        $orderArray = [];
        $orderArray['currency'] = $xml->getElement('/orders/order['. $orderNo .']/currency');

        $numberOfProduct = $xml->count('/orders/order['. $orderNo .']/products/product');

 
        for($i=1; $i <= $numberOfProduct; $i++){
            $orderArray['title'][] = $xml->getElement('/orders/order['. $orderNo .']/products/product['. $i . ']/@title');
            $orderArray['price'][] = $xml->getElement('/orders/order['. $orderNo .']/products/product['. $i . ']/@price');
        }

        $orderArray['total price'] = $xml->getElement('/orders/order['. $orderNo .']/total');
        $orderArray['date'] = $xml->getElement('/orders/order['. $orderNo .']/date');

        return $orderArray;

    }

    // takes both the orderArray and the exchangeRateArray as args
    // apply the exchange rate to the orderArray
    // then returns the new orderArray with converted prices
    function convertCurrencyForOrder(array $orderArray, array $exchangeRateArray){

        $orderArrayBaseCcy = $orderArray['currency'];
        $orderArrayDate = $orderArray['date'];
    
        $exchangeRateArrayBaseCcy = $exchangeRateArray['baseCcy'];
        $exchangeRateArrayDate = $exchangeRateArray['exchangeRateDate'];
        $exchangeRateArrayValue = $exchangeRateArray['exchangeRateValue'];
    
        // input validation for base currencies and exchange rate dates for both array
    
        if($orderArrayBaseCcy != $exchangeRateArrayBaseCcy){
            trigger_error('please check your base currency input for both array',E_USER_ERROR);
        }
    
        if($orderArrayDate != $exchangeRateArrayDate){
            trigger_error('please check your exchange rate date input for both array',E_USER_ERROR);
        }
    
        foreach($orderArray['price'] as $key => $value){
            $orderArray['price'][$key] = $value*$exchangeRateArrayValue;
        }
    
        $orderArray['total price'] = $orderArray['total price']*$exchangeRateArrayValue;
    
        return $orderArray;
    
    }

    // create an xml from the order array
    function createXmlFromNewOrderXmlArray(array $array){

        $xml = new xmlRoot('root');
        $orderNode = $xml->newNode('order');
        $orderNode->newValueNode('currency', $array['currency']);
    
        $titleNode = $orderNode->newNode('title');
        foreach($array['title'] as $title){
            $titleNode->newValueNode('item', $title);
        }
    
        $priceNode = $orderNode->newNode('price');
        foreach($array['price'] as $price){
            $priceNode->newValueNode('item', $price);
        }
    
        $orderNode->newValueNode('totalPrice', $array['total price']);
        $orderNode->newValueNode('date', $array['date']);

        $newXML = $xml->save();
    
        return $newXML;
    
    }

}    

?>