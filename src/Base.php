<?php


namespace Pxwei\SimpleUniPush;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Base
{
    protected $Token = "";
    protected $AppID = "";
    protected $AppKey = "";
    protected $AppSecret = "";
    protected $MasterSecret = "";
    protected $RequestUrl = "https://restapi.getui.com/v2";

    /**
     * @var Client $Client
     */
    protected $Client;

    public function setGuzzleHttpClient($Client = null)
    {
        $this->Client = empty($Client) ? $this->getGuzzleHttpClient() : $Client;
    }

    /**
     * @return Client
     */
    public function getGuzzleHttpClient()
    {
        return $this->Client ? : new Client();
    }

    public function getRequestUrl()
    {
        return $this->RequestUrl;
    }

    public function setRequestUrl($RequestUrl)
    {
        $this->RequestUrl = $RequestUrl;
    }

    public function auth()
    {
        $cacheFile = sys_get_temp_dir() . "/auth.{$this->AppID}.txt";
        //判断之前的token是否过期
        if (file_exists($cacheFile))
        {
            $authContent = json_decode(file_get_contents($cacheFile),true);
            $authContent['expire_time'] = 0;
        }

        $authContent = empty($authContent) ?  ['expire_time' => 0,'token' => ''] : $authContent;


        if ((int)$authContent['expire_time'] < time() * 1000)
        {
            $action = "auth";
            $timestamp = time() * 1000;
            $sign =  hash("sha256", $this->AppKey . (string)$timestamp . $this->MasterSecret);
            $data = [
                'sign' => $sign,
                'timestamp' => $timestamp,
                'appkey' => $this->AppKey,
            ];
            try {
                $authContent = $this->query($action, 'post', $data);
            } catch (IllegalResultException $e) {
                $authContent = ['expire_time' => 0,'token' => ''];
            }

            file_put_contents($cacheFile,json_encode($authContent,256));
        }
        return $this->Token = $authContent['token'];
    }

    public function query($action,$method,$data,$token = false)
    {
        $fullUri = $this->RequestUrl . '/' . $this->AppID . '/' . $action;
        $Client = $this->getGuzzleHttpClient();

        if (empty($this->Token) && $token){
            $this->auth();
        }

        /**
         * @var ResponseInterface $result
         */

        $result = $Client->{$method}($fullUri,[
            'json' => $data,
           'headers' => [
               'token' => $this->Token
           ]
        ]);
        $jsonRes = json_decode($result->getBody()->getContents(),true);


        if ($jsonRes['code'] != 0 ){
            throw new IllegalResultException($jsonRes['msg'],$jsonRes['code']);
        }

        return $jsonRes['data'];
    }

}