<?php

$fields = 'all';
$url = "http://localhost/objects/_search?size=100&q=" . $_GET['q'] . "&fields=" . $fields . "&_source=true";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_PORT, 9200);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);


$results=curl_exec($ch);

echo $results;

?>
