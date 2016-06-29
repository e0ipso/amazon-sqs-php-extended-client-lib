[![Build Status](https://travis-ci.org/e0ipso/amazon-sqs-php-extended-client-lib.svg?branch=master)](https://travis-ci.org/e0ipso/amazon-sqs-php-extended-client-lib)

Amazon SQS Extended Client Library for PHP
==========================================
The **Amazon SQS Extended Client Library for PHP** enables you to manage Amazon SQS message payloads with Amazon S3. This is especially useful for storing and retrieving messages with a message payload size greater than the current SQS limit of 256 KB, up to a maximum of 2 GB. Specifically, you can use this library to:

* Specify whether message payloads are always stored in Amazon S3 or only when a message's size exceeds a max size (defaults to 256 KB).
* Send a message that references a single message object stored in an Amazon S3 bucket.
* Get the corresponding message object from an Amazon S3 bucket.
* Delete the corresponding message object from an Amazon S3 bucket.

You can install this library using composer doing:
```
composer require e0ipso/amazon-sqs-php-extended-client-lib
```

## Getting Started
* **Sign up for AWS** -- Before you begin, you need an AWS account. For more information about creating an AWS account and retrieving your AWS credentials, see [AWS Account and Credentials](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html?highlight=credentials) in the AWS SDK for PHP Developer Guide.
* **Sign up for Amazon SQS** -- Go to the Amazon [SQS console](https://console.aws.amazon.com/sqs/home?region=us-east-1) to sign up for the service.
* **Minimum requirements** -- To use the sample application, you'll need PHP 5.6+ and [Composer](http://composer.org/). For more information about the requirements, see the [Getting Started](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/getting-started/) section of the Amazon SQS Developer Guide.
* **Further information** - Read the [API documentation](http://aws.amazon.com/documentation/sqs/) and the [SQS & S3 recommendations](http://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/s3-messages.html).

## Acknowledgements
* This library is inspired by the [very similar Java library](https://github.com/awslabs/amazon-sqs-java-extended-client-lib).

## Feedback
* Give us feedback [here](https://github.com/e0ipso/amazon-sqs-php-extended-client-lib/issues).
* If you'd like to contribute a new feature or bug fix, we'd love to see Github pull requests from you.
