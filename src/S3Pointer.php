<?php

namespace AwsExtended;

use Aws\ResultInterface;

class S3Pointer {

  /**
   * The name of the bucket.
   *
   * @var string
   */
  protected $bucketName;

  /**
   * The ID of the S3 document.
   *
   * @var
   */
  protected $key;

  /**
   * The transaction response.
   *
   * @var \Aws\ResultInterface
   */
  protected $s3Result;


  /**
   * S3Pointer constructor.
   *
   * @param $bucket_name
   *   The name of the bucket to point to.
   * @param $key
   *   The name of the document in S3.
   * @param \Aws\ResultInterface $s3_result
   *   The response from the S3 operation that saved to object.
   */
  public function __construct($bucket_name, $key, ResultInterface $s3_result = NULL) {
    $this->bucketName = $bucket_name;
    $this->key = $key;
    $this->s3Result = $s3_result;
  }

  /**
   * Generates a JSON serialization of the pointer.
   *
   * @return string
   *   The string version of the pointer.
   */
  public function __toString() {
    $info_keys = ['@metadata', 'ObjectUrl'];
    $metadata = $this->s3Result ?
      array_map([$this->s3Result, 'get'], $info_keys) :
      [];
    $pointer = ['Bucket' => $this->bucketName, 'Key' => $this->key];
    return json_encode([$metadata, $pointer]);
  }

}
