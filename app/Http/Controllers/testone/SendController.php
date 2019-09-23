<?php

namespace App\Http\Controllers\testone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SendController extends Controller
{
    public function send()
    {
        // 功能 业务逻辑
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->get_access_token();
        $data = [
            "filter" => [
                "is_to_all" => false,
                "tag_id" => 101
            ],
            "text" => [
                "content" => '12345，上山打老虎。'
            ],
            "msgtype" => "text"
        ];
        \Log::Info(json_encode($data,JSON_UNESCAPED_UNICODE));
        $this->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
    }
}
