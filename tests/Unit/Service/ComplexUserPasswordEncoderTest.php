<?php


namespace App\Tests\Unit\Service;


use App\Service\ComplexUserPasswordEncoder;
use PHPUnit\Framework\TestCase;

class ComplexUserPasswordEncoderTest extends TestCase
{
    private ComplexUserPasswordEncoder $subject;

    public function setUp(): void
    {
        $this->subject = new ComplexUserPasswordEncoder(
            5,
            't3st_p4ss_phr4s3'
        );
    }

    /**
     * @test
     */
    public function shouldVerifyPasswordWhenPasswordsMatch()
    {
        $hash = '$argon2i$v=19$m=65536,t=4,p=1$ZkZBMXluY0lML1Vqa1ZZYw$Uw8hXel29v3pnrdg09TP2F+p6HuYvdGu5JYlBLF1Ans';
        $password = '12345678';

        $this->assertTrue($this->subject->verify($password, $hash));
    }

    /**
     * @test
     */
    public function shouldNotVerifyPasswordWhenPasswordsMatch()
    {
        $hash = '$argon2i$v=19$m=65536,t=4,p=1$ZkZBMXluY0lML1Vqa1ZZYw$Uw8hXel29v3pnrdg09TP2F+p6HuYvdGu5JYlBLF1Ans';
        $password = '12345679';

        $this->assertFalse($this->subject->verify($password, $hash));
    }
}