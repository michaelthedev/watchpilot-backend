<?php

declare(strict_types=1);

namespace App\Services;

final class Encryption
{
    private string $encryptionKey;

    public function __construct()
    {
        $this->encryptionKey = config('app.key');
    }

	/**
	 * Encrypt data using AES-256-CBC algorithm.
	 *
	 * @param string $data
	 * @return string
	 */
    public function encrypt(string $data): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);

        return base64_encode($iv . $encryptedData);
    }

    /**
     * Decrypt data using AES-256-CBC algorithm.
     *
     * @param string $encryptedData
     * @return false|string
     */
    public function decrypt(string $encryptedData): string|false
    {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encryptedData = substr($data, $ivLength);

        return openssl_decrypt($encryptedData, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }
}