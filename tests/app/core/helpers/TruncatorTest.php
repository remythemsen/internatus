<?php namespace Internatus\Tests\Core\Helpers;

use Internatus\Core\Helpers\Truncator;

// TODO: Replace Require with Autoloader class.
require_once('app/core/helpers/Truncator.php');

class TruncatorTest extends \PHPUnit_Framework_TestCase {

    public function testExcerptCorrectLength() {
        // Arrange
        $string = 'This string is should be truncated';
        $length = 10;
        $trailing = '...';

        // Act
        $truncatedString = Truncator::excerpt($string, $length, $trailing);

        // Assert
        $this->assertEquals(10, strlen($truncatedString));

    }

    public function testExcerptCorrectTrailing() {
        //Arrange
        $string = 'This string is should be truncated';
        $length = 10;
        $trailing = '...';

        // Act
        $truncatedString = Truncator::excerpt($string, $length, $trailing);

        //Assert
        $this->assertContains('...', $truncatedString);
    }

    public function testExcerptShouldNotBeTruncated() {

        //Arrange
        $string = 'This string is should be truncated!';
        $length = 40;
        $trailing = '...';

        // Act
        $truncatedString = Truncator::excerpt($string, $length, $trailing);

        //Assert
        $this->assertNotContains('...', $truncatedString);
    }
}

