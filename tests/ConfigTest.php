<?php

namespace AwsExtended;

/**
 * Class ConfigTest.
 *
 * @package AwsExtended
 *
 * @coversDefaultClass \AwsExtended\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers ::__construct
   * @dataProvider constructorProvider
   */
  public function testConstructor($args, $bucket_name, $sqs_url, $send_to_s3) {
    $configuration = new Config($args, $bucket_name, $sqs_url, $send_to_s3);
    $this->assertSame($args, $configuration->getConfig());
    $this->assertSame($bucket_name, $configuration->getBucketName());
    $this->assertSame($sqs_url, $configuration->getSqsUrl());
    $this->assertSame($send_to_s3, $configuration->getSendToS3());
  }

  /**
   * Test data for the contructor test.
   *
   * @return array
   *   The data for the test method.
   */
  public function constructorProvider() {
    return [
      [[], 'lorem', 'ipsum', 'ALWAYS'],
      [[1, 2, 'c'], 'dolor', 'sid', 'NEVER'],
    ];
  }

  /**
   * @covers ::__construct
   * @expectedException \InvalidArgumentException
   */
  public function testConstructorFail() {
    new Config([], 'lorem', 'ipsum', 'INVALID');
  }

}
