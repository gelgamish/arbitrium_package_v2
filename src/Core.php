<?php

namespace Arbitrium\Api;

class Core {
    const LEND_URL      = 'http://localhost:1337/api/lend/';
    const UPLOAD_URL    = 'http://localhost:1337/api/transaction/upload';

    protected $file_url = null;
    protected $token    = null;
    protected $cardId   = null;
    protected $service  = null;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function setFile($data)
    {
        $this->file_url = $data;
        return $this;
    }

    public function setCardId($data)
    {
        $this->cardId = $data;
        return $this;
    }

    public function setService($data)
    {
        $this->service = $data;
        return $this;
    }

    public function lend()
    {
        $is_error = $this->validate([
            'setFile'       => $this->file_url,
            'setCardId'     => $this->cardId,
            'setService'    => $this->service
        ]);

        if ($is_error)
        {
            return json_encode($is_error);
        }

        $data = json_encode(array(
            'file'          => $this->file_url,
            'cardId'        => $this->cardId
        ));

        $link = self::LEND_URL . $this->service;
        return $this->coreCurl($data, $link, $this->token);
    }

    public function upload()
    {
        $is_error = $this->validate([
            'setFile'       => $this->file_url
        ]);

        if ($is_error)
        {
            return json_encode($is_error);
        }


        $data = json_encode(array(
            'file'          => $this->file_url,
        ));

        $link = self::UPLOAD_URL;
        return $this->coreCurl($data, $link, $this->token);
    }

    private function coreCurl($data = null, $link = null, $token = null)
    {
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

    public function validate($argument)
    {
        $ret = [];
        foreach ($argument as $key => $value) {
            if (!$value)
            {
                $ret[$key] = 'Cannot be null';
            }
        }
        return $ret;
    }
}