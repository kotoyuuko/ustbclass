<?php

namespace App\Helpers;

class FxxkWengineVPN
{
    private $iv = 'wrdvpnisthebest!';
    private $key = 'wrdvpnisthebest!';

    private function prefix()
    {
        return bin2hex($this->iv);
    }

    public function encryptUrl($input)
    {
        $length = strlen($input);
        $input = $this->textRightAppend($input);
        $data = openssl_encrypt($input, 'AES-128-CFB', $this->key, OPENSSL_RAW_DATA, $this->iv);
        return $this->prefix() . substr(bin2hex($data), 0, $length * 2);
    }

    private function textRightAppend($str)
    {
        $length = strlen($str);
        $segmentByteSize = 16;

        if ($length % $segmentByteSize == 0) {
            return $str;
        }

        $appendLength = $segmentByteSize - $length % $segmentByteSize;
        for ($i = 0; $i < $appendLength; $i++) {
            $str .= '0';
        }

        return $str;
    }
}
