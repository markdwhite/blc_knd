<?php
declare(strict_types=1);

if (!function_exists('encrypted_env')) {
    /**
     * Gets the value of an encrypted environment variable
     *
     * This merely obfuscates the passwords in the env file. It's not a real level of security in any way.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     * TODO: Ambiguous return value cannot be specified in PHP7.0
     */
    function encrypted_env(string $key, $default = null)
    {
        $value = get_env($key);

        if ($value === false) {
            return value($default);
        }

        if (strpos($value, '"') === 0 && substr($value, -1) === '"') {
            $value = substr($value, 1, -1);
        }

        // Checks for an encoded ENV
        if (strpos($value, 'ENC:') === 0) {
            $value = decrypt_env($value);
        }

        return $value;
    }
}

if (!function_exists('decrypt_env')) {
    /**
     * Decrypt a string
     *
     * @param string $encoded
     * @param string $default
     * @return string
     */
    function decrypt_env(string $encoded): string
    {
        // @codeCoverageIgnoreStart
        if (!get_env('APP_KEY')) {
            throw new RuntimeException('APP_KEY must be set in environmental variables');
        }
        // @codeCoverageIgnoreEnd

        $data = base64_decode(substr($encoded, 4));
        $method = 'AES-256-CBC';
        $ivSize = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivSize);
        $key = openssl_digest(get_env('APP_KEY'), 'sha256');
        $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);
        return $data;
    }
}

if (!function_exists('encrypt_env')) {
    /**
     * Encrypt a string and return output
     *
     * @param string $text
     * @return string
     */
    function encrypt_env(string $text): string
    {
        // @codeCoverageIgnoreStart
        if (!get_env('APP_KEY')) {
            throw new RuntimeException('APP_KEY must be set in environmental variables');
        }
        // @codeCoverageIgnoreEnd

        $method = 'AES-256-CBC';
        $ivSize = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $key = openssl_digest(get_env('APP_KEY'), 'sha256');
        $encrypted = openssl_encrypt($text, $method, $key, OPENSSL_RAW_DATA, $iv);

        // For storage/transmission, we simply concatenate the IV and cipher text
        $encrypted = base64_encode($iv . $encrypted);
        return "ENC:$encrypted";
    }
}
if (!function_exists('get_env')) {
    /**
     * Retrieves the env from $_ENV now Laravel overwrites getenv()
     *
     * @param string $text
     * @return mixed
     * TODO: Ambiguous return value cannot be specified in PHP7.0
     */
    function get_env(string $text)
    {
        if (!empty($_ENV[$text])) {
            return $_ENV[$text];
        }
        return false;
    }
}
