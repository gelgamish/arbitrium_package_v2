<?php

namespace Arbitrium\Core;

// use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;

class Core {

    protected $file_url = '';
    protected $token = '';
    protected $cardId = '';
    protected $service = '';

    public function setFile($data) {
        return $this->file_url = $data;
    }

    public function setToken($data) {
        return $this->token = $data;
    }

    public function setCardId($data) {
        return $this->cardId = $data;
    }

    public function setService($data) {
        return $this->service = $data;
    }

    public function Lend() {
        $data = json_encode(array(
            'file' => $this->file_url,
            'cardId' => $this->cardId
        ));

        $link = "http://localhost:1337/api/lend/" . $this->service;
        return $this->coreCurl($data, $link, $this->token);
    }

    public function Upload() {
        $data = json_encode(array(
            'file' => $this->file_url,
        ));

        $link = "http://localhost:1337/api/transaction/upload";
        return $this->coreCurl($data, $link, $this->token);
    }

    private function coreCurl($data = null, $link = null, $token = null) {
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