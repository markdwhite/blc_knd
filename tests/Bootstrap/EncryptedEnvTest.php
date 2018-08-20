<?php

use Orchestra\Testbench\TestCase;

class EncryptedEnvTest extends TestCase
{
    public function testEncryptDecrypt()
    {
        $text = 'this is a test';

        $encrypted = encrypt_env($text);
        $decrypted = decrypt_env($encrypted);

        $this->assertEquals($decrypted, $text);
    }

    public function testEncryptedEnvIsEncrypted()
    {
        $text = 'this is a test';

        putenv('test=' . encrypt_env($text));

        $decrypted = encrypted_env('test');

        $this->assertEquals($decrypted, $text);
    }

    public function testEncryptedEnvIsFalse()
    {
        putenv('test=false');

        $decrypted = encrypted_env('test');

        $this->assertFalse($decrypted);
    }

    public function testEncryptedEnvIsNull()
    {
        putenv('test=null');

        $decrypted = encrypted_env('test');

        $this->assertNull($decrypted);
    }

    public function testEncryptedEnvIsEmpty()
    {
        putenv('test=empty');

        $decrypted = encrypted_env('test');

        $this->assertEmpty($decrypted);
    }

    public function testEncryptedEnvIsTrue()
    {
        putenv('test=true');

        $decrypted = encrypted_env('test');

        $this->assertTrue($decrypted);
    }

    public function testEncryptedEnvIsQuoted()
    {
        putenv('test="quoted"');

        $decrypted = encrypted_env('test');

        $this->assertEquals("quoted", $decrypted);
    }

    public function testEncryptedEnvIsFalsey()
    {
        $decrypted = encrypted_env('falsey');

        $this->assertNull($decrypted);
    }
}
