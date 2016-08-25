<?php

namespace Arbitrium\Core;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Core {

    protected static $file_url = '';
    protected static $token = '';
    protected static $cardId = '';
    protected static $service = '';

    public static function setFile($data) {
        return self::$file_url = $data;
    }

    public static function setToken($data) {
        return self::$token = $data;
    }

    public static function setCardId($data) {
        return self::$cardId = $data;
    }

    public static function setService($data) {
        return self::$service = $data;
    }

    public static function Lend() {
        $data = json_encode(array(
            'file' => self::$file_url,
            'cardId' => self::$cardId
        ));

        $link = "http://localhost:1337/api/lend/" . self::$service;
        return self::coreCurl($data, $link, self::$token);
    }

    public static function Upload() {
        $data = json_encode(array(
            'file' => self::$file_url,
        ));

        $link = "http://localhost:1337/api/transaction/upload";
        return self::coreCurl($data, $link, self::$token);
    }

    private static function coreCurl($data = null, $link = null, $token = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'x-token: ' . $token;
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        return $server_output ;
    }
}