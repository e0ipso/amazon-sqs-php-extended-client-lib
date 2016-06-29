<?php

namespace AwsExtended;

class Config implements ConfigInterface {

  /**
   * The configuration array to send to the Aws client.
   *
   * @var array
   */
  protected $config;

  /**
   * The name of the alternative S3 bucket.
   *
   * @var string
   */
  protected $bucketName;

  /**
   * The URL of the SQS queue to send messages to.
   *
   * @var null|string
   */
  protected $sqsUrl;

  /**
   * Indicates when to send messages to S3.
   *
   * Allowed values are: ALWAYS, NEVER, IF_NEEDED.
   *
   * @var null|string
   */
  protected $sendToS3 = self::IF_NEEDED;

  /**
   * Config constructor.
   *
   * @param array $config
   *   Client configuration arguments. The same arguments passed to AwsClient.
   * @param string $bucket_name
   *   The name of the S3 bucket to send the data to.
   * @param string $sqs_url
   *   The SQS url to push messages to.
   * @param string $send_to_s3
   *   Code that indicates when to send data to S3. Allowed values are:
   *     - IF_NEEDED
   *     - ALWAYS
   *     - NEVER
   *
   * @throws \InvalidArgumentException if any required options are missing or
   * the service is not supported.
   */
  public function __construct(array $config, $bucket_name, $sqs_url = NULL, $send_to_s3 = NULL) {
    $this->config = $config;
    $this->bucketName = $bucket_name;
    if ($sqs_url) {
      $this->sqsUrl = $sqs_url;
    }
    $this->sqsUrl = $sqs_url;
    if ($send_to_s3) {
      if (!in_array($send_to_s3, [self::ALWAYS, self::IF_NEEDED, self::NEVER])) {
        throw new \InvalidArgumentException(sprintf('Invalid code for send_to_s3: %s.', $send_to_s3));
      }
      $this->sendToS3 = $send_to_s3;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * {@inheritdoc}
   */
  public function getBucketName() {
    return $this->bucketName;
  }

  /**
   * {@inheritdoc}
   */
  public function getSqsUrl() {
    return $this->sqsUrl;
  }

  /**
   * {@inheritdoc}
   */
  public function getSendToS3() {
    return $this->sendToS3;
  }

}
