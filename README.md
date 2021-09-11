# Order Exchange Rate Project in PHP
PHP project for Order and ExchangeRate XMLs

## Introduction 
The	technical	test	is	your	opportunity	to	showcase	skills	and	demonstrate	familiarity	with	PHP features,	OOP	and	good	coding	practices.
The	problem	we	ask	you	to	solve	is	described	in	Gherkin	language	in	the	section “Acceptance	Criteria”.	It	should	give	you	a	bit	of	a	feeling	of	how	we	work,	since	this	is	how	we	describe	features	in	our	projects.

## The Task
You were given two xml files in Orders.xml and ExchangeRates.xml

You	should	implement	a	simple	command	line	utility,	which	will	load	orders	and	exchange	rates from	the	following XML	files	(see	next	page).

### You should:
- Convert	the	currency	of	order	(id	=	1)	into	EUR
- Convert	the	currency	of	order	(id	=	2)	into	GBP

## Feature:	Calculating	exchange	rates	on	orders
In	order	to	charge	customers	in	their	local	currencies. As a customer, I	need	to	see	prices	converted	to	my	chosen	currency.

## Solution break down

### xmlHelper folder
- xmlFetcher.php - fetches the xml value with xml xpath as the argument
- xmlMapper.php - main xml mapper that allows user to create parent node and child nodes with child elements
- xmlRoot.php - creates a DOMDocument object and a root node element (extends xmlMapper)
- xmlNode.php - DomNode constructor (extends xmlMapper)

### exchangeRateMapper.php
- converts exchangeRates xml into json format
- then from json converting to an array
- simplifies the exchangeRate array further and filter by exchange rate date
- getExchangeRateArray($array, $date, $baseCcy, $otherCcy) that takes 4 arguments and returns an array back with the exchange rate value

### orderMapper.php
- use xmlFetcher class for getting the neccessary node values from the Orders.xml
- user can specify which order number (1 or 2) and get back the corresponding array
- convertCurrencyForOrder(array $orderArray, array $exchangeRateArray) takes 2 arguments in both array
- and return back one array containing the converted prices of a specific order number
- createXmlFromNewOrderXmlArray(array $array) returns the xml from the converted array file

### main.php
- executes all of the neccessary functions in a sequential order to return the result in xml format

### Orders.xml and ExchangeRates.xml
- original xml files

