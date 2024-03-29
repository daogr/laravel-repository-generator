<?php

namespace Otodev\Crypto;

use InvalidArgumentException;

class CryptoJsAes
{

    /**
     * @param      $data
     * @param      $passphrase
     * @param bool $encode
     * @param null $salt ONLY FOR TESTING
     *
     * @return string encrypted data in base64 OpenSSL format
     */
    public static function encrypt($data, $passphrase, bool $encode = true, $salt = null)
    {
        if ($encode) {
            $data = json_encode($data);
        }

        $salt = $salt ?: openssl_random_pseudo_bytes(8);
        list($key, $iv) = self::evpkdf($passphrase, $salt);

        $ct = openssl_encrypt($data, 'aes-256-cbc', $key, true, $iv);

        return self::encode($ct, $salt);
    }

    public static function evpkdf($passphrase, $salt)
    {
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        return [$key, $iv];
    }

    public static function encode($ct, $salt)
    {
        return base64_encode("Salted__" . $salt . $ct);
    }

    /**
     * @param string $base64 encrypted data in base64 OpenSSL format
     * @param string $passphrase
     * @param bool $decode
     *
     * @return string
     */
    public static function decrypt(string $base64, string $passphrase, bool $decode = true)
    {
        list($ct, $salt) = self::decode($base64);
        list($key, $iv) = self::evpkdf($passphrase, $salt);

        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

        if ($decode) {
            return json_decode($data);
        }

        return $data;
    }

    public static function decode($base64)
    {
        $data = base64_decode($base64);

        if (substr($data, 0, 8) !== "Salted__") {
            throw new InvalidArgumentException();
        }

        $salt = substr($data, 8, 8);
        $ct = substr($data, 16);

        return [$ct, $salt];
    }
}
