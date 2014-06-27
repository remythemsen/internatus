<?php

require_once('app/core/helpers/Auth.php');

use Internatus\Core\Helpers\Auth;

// TODO: FIND OUT HOW TO AUTOLOAD WITH PHPUNIT

class Auth_Test extends PHPUnit_Framework_TestCase {
    function testAttempt() {
        $email = 'John@Doe.com';
        $password = 'SecretPassWord123';

        $this->assertEquals(false, Auth::attempt($email, $password, false));
    }
}