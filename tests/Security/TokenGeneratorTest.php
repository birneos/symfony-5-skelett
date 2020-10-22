<?php

namespace App\Tests\Security;

use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    public function testTokenGeneration(){

        $tokenGenerator = new TokenGenerator();
        $token = $tokenGenerator->getRandomSecureToken(40);
      #  $token[4]="*";
        echo $token;

        $this->assertEquals(  40, strlen($token ),"Länge stimmt nicht überein");
        $this->assertEquals(  1, preg_match("/^[[:alnum:]]+$/",$token),"Enthält Fehler #1");
      #  $this->assertTrue(  ctype_alnum($token),"Enthält Fehler #2");

    }
}
