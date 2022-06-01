<?php

//scraping data php via curl
$url = "https://andrafarm.com/_andra.php?_i=daftar-co19-kota&noneg=378-28&urut=1&asc=01100000000/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);

preg_match_all('/<div style="font-size:18pt; margin:10px 0 3px 0;">([\w\W]*?)<\/div>/', $output, $kota);
preg_match_all('/<div style="margin:7px 0 4px 0; font-size:11pt;">update <b>(.*?)<\/b> WIB<\/div>/', $output, $title);

preg_match_all('/<tr valign="top"><td style="width=10px; padding:5px 0 5px 0;"><span style="font-size:9pt; color:red;">[\w\W]*?<\/span><\/td><td style="padding:5px 0 5px 0;">(.*?)<\/td><td width="15" align="center" style="padding:5px 0 5px 0;">:<\/td><td align="right" style="padding:5px 0 5px 0;">(.*?)<\/td><\/tr>/', $output, $body);



$data = [
    "kota" => strip_tags($kota[1][0]),
    "update" => $title[1][0],

];

// restructuring
for ($i = 0; $i < count($body[1]); $i++) {
    $data['data'][$i] = [
        "title" => strip_tags($body[1][$i]),
        "value" => strip_tags($body[2][$i])
    ];
}


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
echo json_encode($data);
