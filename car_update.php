<?php

require('ZipApi.php');
$zipApi = new ZipApi('0a1f2ff1-4216-2a:17-346d-7fb1fa7bf27f');

// update car ad
$re = $zipApi->updateAd([
	'extId' => 1,
	'price' => 20000.00,
	'text' => 'This is a great car with many extras.'
]);

var_dump( $re );