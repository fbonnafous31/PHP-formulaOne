<?php

namespace App\Utils\Curl;

class CurlController
{
    public static function extract_xml($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($data);

        return $xml;
    }
}
