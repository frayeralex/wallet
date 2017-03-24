<?php


namespace frontend\components;


use Aws\S3\S3Client;
use yii\base\Component;


/**
 *
 * @property $S3 The user component. This property is read-only.
 *
 */
class S3 extends Component
{
    const BUCKET = "racoon.wallet";
    private $accessKey;
    private $secretKey;

    private $s3;

    private function getS3()
    {
        if($this->s3) return $this->s3;
        $this->s3 = new S3Client([
            'key' => $this->accessKey,
            'secret' => $this->secretKey
        ]);
        return $this->s3;
    }



}