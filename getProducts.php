<?php
// username and password for API
$username = "aa99a7d8802a3e3b56a95af34876802b";
$password = "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";

$nextPage = NULL;

$curl = curl_init();

// set result limit and Basic auth

curl_setopt_array(
    $curl,
    array(
        CURLOPT_URL => "https://v12-footwear-ltd.myshopify.com/admin/api/2020-07/products.json?status=active&limit=50",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_USERPWD => $username . ":" . $password

    )
);

// call back function to parse Headers and get Next Page Link
curl_setopt(
    $curl,
    CURLOPT_HEADERFUNCTION,
    function($curl, $header) use (&$nextPage) {
        $len = strlen($header);
        $header = explode(':', $header, 2);

        if (count($header) < 2) // ignore invalid headers
        return $len;

        if (trim($header[0]) === "Link" && strpos($header[1], 'next') !== false) {
            $links = explode(',', $header[1], 2);

            $link = count($links) === 2 ? $links[1] : $links[0];
            if (preg_match('/<(.*?)>/', $link, $match) === 1) $nextPage = $match[1];
        }

        return $len;
    }
);

// First request

$response = curl_exec($curl);

if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    print_r($error_msg);
}
$parsedResponse = json_decode($response);
$result = $parsedResponse->products;

// generate new requests till next page is available

while ($nextPage !== NULL) {
    curl_setopt($curl, CURLOPT_URL, $nextPage);
    $parsedResponse->products = [];
    $nextPage = NULL;

    $response = curl_exec($curl);
    $parsedResponse = json_decode($response);

    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
    } else {
        $result = array_merge($result, $parsedResponse->products);
        sleep(2);
    }
}; 
echo json_encode($result);
curl_close($curl);