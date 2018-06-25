<?php
declare(strict_types=1);

/**
 * Gets the value of an encrypted environment variable
 *
 * This merely obfuscates the passwords in the env file. It's not a real level of security in any way.
 *
 * @param  string $key
 * @param  mixed  $default
 * @return mixed
 * @codeCoverageIgnore
 * TODO: Ambiguous return value cannot be specified in PHP7.0
 */
if (!function_exists('encrypted_env')) {
    function encrypted_env(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        // Values can look like '(true)'
        switch (strtolower(trim($value, '()'))) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'empty':
                return '';

            case 'null':
                return;
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

/**
 * Decrypt a string
 *
 * @param string $encoded
 * @param string $default
 * @return string
 */
if (!function_exists('decrypt_env')) {
function decrypt_env(string $encoded): string
{
    // @codeCoverageIgnoreStart
    if (!getenv('APP_KEY')) {
        throw new RuntimeException('APP_KEY must be set in environmental variables');
    }
    // @codeCoverageIgnoreEnd

    list($iv, $value) = unserialize(base64_decode(substr($encoded, 4)));

    return rtrim(
        mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256,
            base64_decode(substr(getenv('APP_KEY'), 7)),
            $value,
            MCRYPT_MODE_CBC,
            $iv
        ),
        "\0"
    );
}
}

/**
 * Encrypt a string and return output
 *
 * @param string $text
 * @return string
 */
if (!function_exists('encrypt_env')) {
    function encrypt_env(string $text): string
    {
        // @codeCoverageIgnoreStart
        if (!getenv('APP_KEY')) {
            throw new RuntimeException('APP_KEY must be set in environmental variables');
        }
        // @codeCoverageIgnoreEnd

        // create and randomized initial vector
        mt_srand(intval(microtime() * 1000000));
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);

        // encrypt the value
        $value = mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            base64_decode(substr(getenv('APP_KEY'), 7)),
            $text,
            MCRYPT_MODE_CBC,
            $iv
        );

        // encode & print
        $encoded = rtrim(base64_encode(serialize([$iv, $value])), "\0\3");

        return "ENC:$encoded";
    }
}
