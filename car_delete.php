<?php

require('ZipApi.php');
$zipApi = new ZipApi('0a1f2ff1-4216-2a:17-346d-7fb1fa7bf27f');

// delete car ad
$re = $zipApi->deleteAd([
	'extId' => 1
]);

var_dump( $re );