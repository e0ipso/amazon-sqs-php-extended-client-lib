<?php

namespace AwsExtended;

use Aws\S3\S3Client;
use Aws\Sqs\SqsClient as AwsSqsClient;
use Ramsey\Uuid\Uuid;

/**
 * Class SqsClient.
 *
 * @package AwsExtended
 */
class SqsClient implements SqsClientInterface {

  /**
   * The AWS client to push messages to SQS.
   *
   * @var \Aws\Sqs\SqsClient
   */
  protected $sqsClient;

  /**
   * The S3 client to interact with AWS.
   *
   * @var \Aws\S3\S3Client
   */
  protected $s3Client;

  /**
   * The configuration object containing all the options.
   *
   * @var \AwsExtended\ConfigInterface
   */
  protected $config;

  /**
   * SqsClient constructor.
   *
   * @param ConfigInterface $configuration
   *   The configuration object.
   *
   * @throws \InvalidArgumentException if any required options are missing or
   * the service is not supported.
   */
  public function __construct(ConfigInterface $configuration) {
    $this->config = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function sendMessage($message, $queue_url = NULL) {
    switch ($this->config->getSendToS3()) {
      case ConfigInterface::ALWAYS:
        $use_sqs = FALSE;
        break;

      case ConfigInterface::NEVER:
        $use_sqs = TRUE;
        break;

      case ConfigInterface::IF_NEEDED:
        $use_sqs = !$this->isTooBig($message);
        break;

      default:
        $use_sqs = TRUE;
        break;
    }
    $use_sqs = $use_sqs || !$this->config->getBucketName();
    if (!$use_sqs) {
      // First send the object to S3. The modify the message to store an S3
      // pointer to the message contents.
      $uuid = $this->generateUuid();
      $receipt = $this->getS3Client()->upload(
        $this->config->getBucketName(),
        $uuid,
        $message
      );
      // Swap the message for a pointer to the actual message in S3.
      $message = (string) (new S3Pointer($this->config->getBucketName(), $uuid, $receipt));
    }
    $queue_url = $queue_url ?: $this->config->getSqsUrl();
    return $this->getSqsClient()->sendMessage([
      'QueueUrl' => $queue_url,
      'MessageBody' => $message,
    ]);

  }

  /**
   * {@inheritdoc}
   */
  public function isTooBig($message, $max_size = NULL) {
    // The number of bytes as the number of characters. Notice that we are not
    // using mb_strlen() on purpose.
    $max_size = $max_size ?: static::MAX_SQS_SIZE_KB;
    return strlen($message) > $max_size * 1024;
  }

  /**
   * {@inheritdoc}
   */
  public function getSqsClient() {
    if (!$this->sqsClient) {
      $this->sqsClient = new AwsSqsClient($this->config->getConfig());
    }
    return $this->sqsClient;
  }

  /**
   * {@inheritdoc}
   */
  public function getS3Client() {
    if (!$this->s3Client) {
      $this->s3Client = new S3Client($this->config->getConfig());
    }
    return $this->s3Client;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfig($config) {
    $this->config = $config;
  }

  /**
   * Routes all unknown calls to the sqsClient.
   *
   * @param $name
   *   The name of the method to call.
   * @param $arguments
   *   The arguments to use.
   *
   * @return mixed
   *   The return of the call.
   */
  function __call($name, $arguments) {
    // Send any unknown method calls to the SQS client.
    return call_user_func_array([$this->getS3Client(), $name], $arguments);
  }

  /**
   * Generate a UUID v4.
   *
   * @return string
   *   The uuid.
   */
  protected function generateUuid() {
    return Uuid::uuid4()->toString();
  }

}