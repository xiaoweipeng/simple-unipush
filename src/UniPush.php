<?php


namespace Pxwei\SimpleUniPush;

class UniPush extends Base
{

    public function __construct($AppID,$AppKey,$AppSecret,$MasterSecret)
    {
        $this->AppID = $AppID;
        $this->AppKey = $AppKey;
        $this->AppSecret = $AppSecret;
        $this->MasterSecret = $MasterSecret;
    }

    public static function make(...$args)
    {
        return new static(...$args);
    }


    public function push($action,$method,$data)
    {
        return $this->query($action,$method,$data,true);
    }


    
    
}