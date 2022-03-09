<?php
require "vendor/autoload.php";
$data = [
    'AppID' => "LluR184juC6ExkrOz0Y5x6",//"zek0xVoCCSA1UnX3kZaX66",
    'AppKey' => "tKtHQV8ruc5mBQGaLGwuC6",//"wew88uN2GH7YsTrl66PBj1",
    'AppSecret' => "oWphGYtp8uAb99NZqMxMn8",//"tVx5Qd5MIj5oCxoSRA0Jd6",
    'MasterSecret' => "j1sWQHHue56PGHesruK8T4",//"0ivIwpj9FZ62HbMfGhTFU6",
];
$api = \Pxwei\SimpleUniPush\UniPush::make(...$data);
$r =  $api->push("push/all",'post',[
    'request_id' => (string)time(),
    'group_name' => "数据报表",
    'settings' => [
        "ttl" => 3600000
    ],
    "push_message" => [
        "notification" => [
            "title" => "03月09日数据报表" . rand(100,200),
            "body" => "本日共营收2000万元！",
            "click_type" => "payload",
            "payload" => json_encode(["expire_info_id" => 1]),
        ]
    ],
    'push_channel' => [
        'ios' => [
            "type" => "notify",
        "aps"=>[
        "alert"=>[
        "title"=>"通知标题",
                "body"=>"通知内容"
            ],
            "content-available"=>0,
            "sound"=>"default",
            "category"=>"ACTIONABLE"
        ],
            'payload' =>json_encode(["expire_info_id" => 1]),

        ]
    ]
]);
var_dump($r);