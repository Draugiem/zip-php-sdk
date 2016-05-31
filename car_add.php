<?php

require('ZipApi.php');
$zipApi = new ZipApi('0a1f2ff1-4216-2a:17-346d-7fb1fa7bf27f');

// add car ad
$re = $zipApi->addAd([
	'extId' => 1,
	'typeId' => 1,
	'categoryId' => 69,
	'images' => [
		'http://i6.ifrype.com/zip/lv/790/776/6790776.jpg',
		'http://i6.ifrype.com/zip/lv/790/777/6790777.jpg'
	],
	'phone' => '+371 12345678',
	'price' => 25000.00,
	'text' => 'This is a great car.',
	'make' => 83, // Audi
	'engine' => 3.0,
	'model' => 'A6',
	'year' => 2010,
	'body' => 9, // Saloon
	'extras' => [1434, 1435, 1438, 1439, 1440, 1441, 1442, 1443, 1447, 1450, 1451, 6479, 6488],
	'fuel' => 20, // Gasoline
	'gear' => 15, // Automatic
	'mileage' => 98567
]);

var_dump( $re );
