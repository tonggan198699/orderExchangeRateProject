<?php

// this class deals with the ExchangeRates xml
class exchangeRateMapper {

    // converts exchangeRates xml into json
    // then from json to array
    function convertXmlToArray($xml){

        $loadXml = simplexml_load_string($xml);
        $json = json_encode($loadXml);
        $array = json_decode($json,true);
    
        return $array;
    
    }
    
    // simplify the exchangeRate array
    // filtered by the exchange rate date
    function simplifyExchangeArray($array){
    
        $count = count($array['currency']);
        $simplifiedArray = [];
        $j=1;
    
        for($i= 0; $i < $count-1; $i++){
    
            $date1 = $array['currency'][$i]['rateHistory']['rates'][$i]['@attributes']['date'];
            $date2 = $array['currency'][$i]['rateHistory']['rates'][$j]['@attributes']['date'];
    
            $simplifiedArray[$i][$date1][] = $array['currency'][$i]['rateHistory']['rates'][$i]['rate'][$i]['@attributes']['code'] . ' ' . $array['currency'][$i]['rateHistory']['rates'][$i]['rate'][$i]['@attributes']['value']; // 000
            $simplifiedArray[$i][$date1][] = $array['currency'][$i]['rateHistory']['rates'][$i]['rate'][$j]['@attributes']['code'] . ' ' . $array['currency'][$i]['rateHistory']['rates'][$i]['rate'][$j]['@attributes']['value']; // 001
            $simplifiedArray[$j][$date2][] = $array['currency'][$i]['rateHistory']['rates'][$j]['rate'][$i]['@attributes']['code'] . ' ' . $array['currency'][$i]['rateHistory']['rates'][$j]['rate'][$i]['@attributes']['value']; // 010
            $simplifiedArray[$j][$date2][] = $array['currency'][$i]['rateHistory']['rates'][$j]['rate'][$j]['@attributes']['code'] . ' ' . $array['currency'][$i]['rateHistory']['rates'][$j]['rate'][$j]['@attributes']['value']; // 011
            $simplifiedArray[$i][$date1][] = $array['currency'][$j]['rateHistory']['rates'][$i]['rate'][$i]['@attributes']['code'] . ' ' . $array['currency'][$j]['rateHistory']['rates'][$i]['rate'][$i]['@attributes']['value']; // 100
            $simplifiedArray[$i][$date1][] = $array['currency'][$j]['rateHistory']['rates'][$i]['rate'][$j]['@attributes']['code'] . ' ' . $array['currency'][$j]['rateHistory']['rates'][$i]['rate'][$j]['@attributes']['value']; // 101
            $simplifiedArray[$j][$date2][] = $array['currency'][$j]['rateHistory']['rates'][$j]['rate'][$i]['@attributes']['code'] . ' ' . $array['currency'][$j]['rateHistory']['rates'][$j]['rate'][$i]['@attributes']['value']; // 110
            $simplifiedArray[$j][$date2][] = $array['currency'][$j]['rateHistory']['rates'][$j]['rate'][$j]['@attributes']['code'] . ' ' . $array['currency'][$j]['rateHistory']['rates'][$j]['rate'][$j]['@attributes']['value']; // 111
                
            $j++;
    
        }
    
        return $simplifiedArray;
    
    }
        
    // perform steps to find the exchange rate of the otherCcy a specified exchange rate date
    function getExchangeRateArray($array, $date, $baseCcy, $otherCcy) {
    
        // input validation for accepted currencies and exchange rate dates
        $acceptedCurrencies = ['GBP', 'EUR'];
        $acceptedDates = ['01/01/2016', '02/01/2016'];
        
        if( !in_array($baseCcy,$acceptedCurrencies) ){
            trigger_error('please check your ccy input',E_USER_ERROR);
        }
    
        if( !in_array($otherCcy,$acceptedCurrencies) ){
            trigger_error('please check your ccy input',E_USER_ERROR);
        }
    
        if( !in_array($date,$acceptedDates) ){
            trigger_error('please check your date input',E_USER_ERROR);
        }
    
        $arrayFilterByDate = [];
    
        // filter the array by exchange rate date input
        foreach($array as $key => $value){
    
            if(isset($value[$date])){
                $arrayFilterByDate = $value[$date];
            }    
        }
    
        $explodeValue = [];
        $exchangeRateValue = 0;
        
        // checks the currency pairs and get back the correct exchange rate value for the base ccy
        foreach($arrayFilterByDate as $key => $value){
            
            $containsOne = substr($value,-2);
            $containsOne = trim($containsOne);
    
            // must be different ccy to input ccy AND the value not equal to 1
            if(strpos($value,$baseCcy) === false && $containsOne != '1'){    
                $explodeValue = explode(' ',$value);
                $exchangeRateValue = $explodeValue[1];
            }
            
        };

        $currencyExchange = $baseCcy.' 1 = '.$otherCcy.' '.$exchangeRateValue;

        $newExchangeRateArray = [
            'baseCcy' => $baseCcy,
            'otherCcy' => $otherCcy,
            'currencyExchange' => $currencyExchange,
            'exchangeRateValue' => $exchangeRateValue,
            'exchangeRateDate' => $date

        ];
    
        return $newExchangeRateArray;
    }

}    

?>