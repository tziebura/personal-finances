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
    public function shouldEncodePassword()
    {
        $expected = '$2y$05$c29tZV9yYW5kb21fc2Fsd.5wJQNPuog9BLUrAVlmgiX0JX1kKjFpC';
        $this->subject->setSalt('some_random_salt_value');

        $this->assertEquals($expected, $this->subject->encode('12345'));
    }

    /**
     * @test
     */
    public function shouldVerifyPasswordWhenPasswordsMatch()
    {
        $hash = '$2y$05$WsWs.9eQEs6Wvzncl11ZHOgadybQwXYFBv/eYz5I50H2D/POPzFY2';
        $password = '12345678';

        $this->assertTrue($this->subject->verify($password, $hash));
    }

    /**
     * @test
     */
    public function shouldNotVerifyPasswordWhenPasswordsMatch()
    {
        $hash = '$2y$05$WsWs.9eQEs6Wvzncl11ZHOgadybQwXYFBv/eYz5I50H2D/POPzFY2';
        $password = '12345679';

        $this->assertFalse($this->subject->verify($password, $hash));
    }
}