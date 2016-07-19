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
    $pointer = ['s3BucketName' => $this->bucketName, 's3Key' => $this->key];
    return json_encode([$metadata, $pointer]);
  }

  /**
   * Checks if a result response is an S3 pointer.
   *
   * @param \Aws\ResultInterface $result
   *   The result from the SQS request.
   *
   * @return bool
   *   TRUE if the result corresponds to an S3 pointer. FALSE otherwise.
   */
  public static function isS3Pointer(ResultInterface $result) {
    // Check that the second element of the 2 position array has the expected
    // keys (and no more).
    return $result->count() == 2 &&
    is_array($result->get(1)) &&
    empty(array_diff($result->get(1), ['s3BucketName', 's3Key']));
  }

}
