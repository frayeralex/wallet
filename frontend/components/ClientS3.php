<?php


namespace frontend\components;


use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use yii\base\Component;
use yii\helpers\ArrayHelper;


class ClientS3 extends Component
{
    const BUCKET = "raccon.wallet";
    const AVATAR_FOLDER = "avatar/";
    public $key;
    public $secret;
    public $region;
    public $version;

    private $client;

    public function getClient()
    {
        if($this->client === null) {
            $this->client = new S3Client([
                'credentials' => [
                    'key' => $this->key,
                    'secret' => $this->secret
                ],
                'region' => $this->region,
                'version' => $this->version
            ]);
        }

        return $this->client;
    }

    /**
     * @param string $fileName
     * @param string $filePath
     * @param boolean $returnUrl
     * @return \Aws\Result|string|false
     */
    public function uploadAvatar($fileName,$filePath,$returnUrl = true)
    {
        $result = null;
        try{
            $result = $this->getClient()->putObject([
                'Bucket' => self::BUCKET,
                'Key' => self::AVATAR_FOLDER.$fileName,
                'Body' => fopen($filePath, 'r'),
                'ACL' => 'public-read'
            ]);
            unlink($filePath);
        }catch(S3Exception $e){
            die("Error uploading file {$e}");
        }

        if($returnUrl && $result){
            $result = $result->get('ObjectURL');
        }

        return $result;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getAvatarUrl($fileName)
    {
        return $this->getClient()->getObjectUrl(self::BUCKET,self::AVATAR_FOLDER.$fileName);
    }

    /**
     * @param string $fileUrl
     * @return \Aws\Result
     */
    public function deleteAvatar($fileUrl)
    {
        $key = explode('/',$fileUrl);
        $key = strtolower(end($key));

        return $this->getClient()->deleteObject([
            'Bucket' => self::BUCKET,
            'Key' => self::AVATAR_FOLDER.$key
        ]);
    }
}