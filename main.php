<?php

require_once 'xmlHelper/xmlFetcher.php';
require_once 'xmlHelper/xmlMapper.php';
require_once 'xmlHelper/xmlRoot.php';
require_once 'xmlHelper/xmlNode.php';
require_once 'orderMapper.php';
require_once 'exchangeRateMapper.php';

// to load the content of both xmls
$ordersXml = file_get_contents('./Orders.xml', true);
$exchangeRatesXml = file_get_contents('./ExchangeRates.xml', true);

// orderMapper - convert orders xml into two array filtered by order number
$orderMapper = new orderMapper();
$orderOneArray = $orderMapper->xmlToArrayByOrder($ordersXml,1);
$orderTwoArray = $orderMapper->xmlToArrayByOrder($ordersXml,2);

###############################################################
//echo print_r($orderOneArray,1);
//echo print_r($orderTwoArray,1);
###############################################################

// exchangeRateMapper - converts exchangeRates xml into array
$exchangeRateMapper = new exchangeRateMapper();
$convertXmlToArray = $exchangeRateMapper->convertXmlToArray($exchangeRatesXml);
// simplify the exchangerate array further
$simplifyExchangeArray = $exchangeRateMapper->simplifyExchangeArray($convertXmlToArray);
// get back a new exchangeRateArray containing the exchange rate of the specifed ccy on a given exchange date

###############################################################
//echo print_r($convertXmlToArray,1);
//echo print_r($simplifyExchangeArray,1);
###############################################################

// example 1 - retrieve the exchange rate for GBP to EUR on 01/01/2016 (matching order 1)
$getExchangeRateArrayMatchingOrderOne = $exchangeRateMapper->getExchangeRateArray($simplifyExchangeArray, '01/01/2016', 'GBP', 'EUR');

// example 2 - retrieve the exchange rate for GBP to EUR on 01/01/2016 (matching order 1)
$getExchangeRateArrayMatchingOrderTwo = $exchangeRateMapper->getExchangeRateArray($simplifyExchangeArray, '02/01/2016', 'EUR', 'GBP');

###############################################################
//echo print_r($getExchangeRateArrayMatchingOrderOne,1);
//echo print_r($getExchangeRateArrayMatchingOrderTwo,1);
###############################################################

// task 1 - convert the currency of order (id=1) into EUR
$newOrderOneArray = $orderMapper->convertCurrencyForOrder($orderOneArray, $getExchangeRateArrayMatchingOrderOne);
// task 2 - convert the currency of order (id=2) into GBP
$newOrderTwoArray = $orderMapper->convertCurrencyForOrder($orderTwoArray, $getExchangeRateArrayMatchingOrderTwo);

###############################################################
//echo print_r($newOrderOneArray,1);
//echo print_r($newOrderTwoArray,1);
###############################################################

$newOrderOneXml = $orderMapper->createXmlFromNewOrderXmlArray($newOrderOneArray);
$newOrderTwoXml = $orderMapper->createXmlFromNewOrderXmlArray($newOrderTwoArray);

########################## comment out the two lines below to execute the result for orders 1 and 2 ######################################## 

//echo $newOrderOneXml;
//echo $newOrderTwoXml;




?>