<?php

declare(strict_types=1);

namespace App\Services\Auth;

use DateTimeImmutable;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JWT auth service
 *
 * @package App\Services\Auth
 * @author Michael Arawole <michael@logad.net>
 */
final class JwtService
{

    /**
     * Encode data into JWT
     * @param array $data data to encode
     * @return array [expiry, token]
     */
    public static function encode(array $data): array
    {
        $secretKey = config('security.jwt.secret');
        $expiry = config('security.jwt.expiry');
        $domainName = config('security.jwt.domain');

        $date = new DateTimeImmutable();
        $expireAt = $date
            ->modify("+$expiry seconds")
            ->getTimestamp();

        $requestData = [
            'iat'  => $date->getTimestamp(),
            'iss'  => $domainName,
            'nbf'  => $date->getTimestamp(),
            'exp'  => $expireAt,
            'data' => $data
        ];

        return [
            'expiry' => (int) $expiry,
            'token' => JWT::encode(
                $requestData,
                $secretKey,
                'HS512'
            )
        ];
    }

    /**
     * Verify JWT and return data passed during encode
     *
     * Returns false if verification fails
     * @param string $token
     * @return bool|array
     */
    public static function verify(string $token): bool|array
    {
        $secretKey = config('security.jwt.secret');
        $serverName = config('security.jwt.domain');
        $now = new DateTimeImmutable();

        try {
            $decoded = JWT::decode(
                $token,
                new Key($secretKey, 'HS512')
            );
        } catch (\Exception) {
            return false;
        }

        if ($decoded->iss !== $serverName
            || $decoded->nbf > $now->getTimestamp()
            || $decoded->exp < $now->getTimestamp()
        ) {
            return false;
        }

        return (array) $decoded->data;
    }
}