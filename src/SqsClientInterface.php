<?php

namespace AwsExtended;

use Aws\S3\S3Client;
use Aws\Sqs\SqsClient as AwsSqsClient;
use Ramsey\Uuid\Uuid;

interface SqsClientInterface {

  /**
   * The maximum size that SQS can accept.
   */
  const MAX_SQS_SIZE_KB = 256;

  /**
   * Sends the message to SQS.
   *
   * If the message cannot fit SQS, it gets stored in S3 and a pointer is sent
   * to SQS.
   *
   * @param $message
   *   The message to send.
   * @param string $queue_url
   *   The SQS queue. Defaults to the one configured in the client.
   *
   * @return \Aws\ResultInterface
   *   The result of the transaction.
   */
  public function sendMessage($message, $queue_url = NULL);

  /**
   * Gets a message from the queue.
   *
   * @param string $queue_url
   *   The SQS queue. Defaults to the one configured in the client.
   *
   * @return \Aws\ResultInterface
   *   The message
   */
  public function receiveMessage($queue_url = NULL);

  /**
   * Deletes an item from the queue, and from S3 if it applies.
   *
   * @param string $queue_url
   *   The URL for the SQS queue. Set to NULL to use the configured queue URL.
   * @param string $receipt_handle
   *   The receipt handle of the message. Every time you recieve a message the
   *   receipt handle is different, you need to use the most recent receipt
   *   handle to successfully delete the message.
   *
   * @return \Aws\ResultInterface
   *   The response from AWS.
   */
  public function deleteMessage($queue_url, $receipt_handle);

  /**
   * Checks if a message is too big to be sent to SQS.
   *
   * @param $message
   *   The message to check.
   * @param int $max_size
   *   The (optional) max considered for SQS.
   *
   * @return bool
   *   TRUE if the message is too big.
   */
  public function isTooBig($message, $max_size = NULL);

  /**
   * Gets the underlying SQS client.
   *
   * @return \Aws\Sqs\SqsClient
   *   The underlying SQS client.
   */
  public function getSqsClient();

  /**
   * Gets the underlying S3 client.
   *
   * @return \Aws\S3\S3Client
   *   The underlying S3 client.
   */
  public function getS3Client();

  /**
   * Sets the config.
   *
   * @param \AwsExtended\ConfigInterface $config
   */
  public function setConfig($config);

}
