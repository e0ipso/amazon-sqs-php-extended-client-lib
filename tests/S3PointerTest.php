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
      ['lorem', 'ipsum', NULL, '[[],{"s3BucketName":"lorem","s3Key":"ipsum"}]'],
      ['lorem', 'ipsum', new Result([]), '[[null,null],{"s3BucketName":"lorem","s3Key":"ipsum"}]'],
      [NULL, NULL, NULL, '[[],{"s3BucketName":null,"s3Key":null}]'],
      ['lorem', TRUE, NULL, '[[],{"s3BucketName":"lorem","s3Key":true}]'],
      ['lorem', 'ipsum', new Result([
        '@metadata' => 'fake_metadata',
        'ObjectUrl' => 'fake_object_url'
      ]), '[["fake_metadata","fake_object_url"],{"s3BucketName":"lorem","s3Key":"ipsum"}]'],
    ];
  }

}
