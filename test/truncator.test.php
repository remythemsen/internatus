<?php

require_once('app/core/helpers/Truncator.php');

use Internatus\Core\Helpers\Truncator;

class Truncator_Test extends PHPUnit_Framework_TestCase {
    public function testExcerpt() {
        // a string with more than 15(-trailing) characters
        $string = '123456789012thisisextra';

        $result = Truncator::excerpt($string, 15, '...');
        $this->assertEquals('123456789012...', $result);
    }
}