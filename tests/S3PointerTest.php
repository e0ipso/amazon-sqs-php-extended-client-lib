<?php

namespace AwsExtended;

use Aws\Result;
use Aws\ResultInterface;

/**
 * Class S3PointerTest.
 *
 * @package AwsExtended
 *
 * @coversDefaultClass \AwsExtended\S3Pointer
 */
class S3PointerTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers ::__toString
   * @dataProvider toStringProvider
   */
  public function testToString($bucket, $key, $receipt, $expected) {
    $pointer = $receipt ?
      new S3Pointer($bucket, $key, $receipt) :
      new S3Pointer($bucket, $key);
    $this->assertSame($expected, (string) $pointer);
  }

  /**
   * Test data for the __toString test.
   *
   * @return array
   *   The data for the test method.
   */
  public function toStringProvider() {
    return [
      ['lorem', 'ipsum', NULL, '[[],{"Bucket":"lorem","Key":"ipsum"}]'],
      ['lorem', 'ipsum', new Result([]), '[[null,null],{"Bucket":"lorem","Key":"ipsum"}]'],
      [NULL, NULL, NULL, '[[],{"Bucket":null,"Key":null}]'],
      ['lorem', TRUE, NULL, '[[],{"Bucket":"lorem","Key":true}]'],
      ['lorem', 'ipsum', new Result([
        '@metadata' => 'fake_metadata',
        'ObjectUrl' => 'fake_object_url'
      ]), '[["fake_metadata","fake_object_url"],{"Bucket":"lorem","Key":"ipsum"}]'],
    ];
  }

}
