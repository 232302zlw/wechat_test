<?php

namespace App\Http\Controllers\testone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    /** 登录授权 */
    public function login()
    {
        $redirect_uri = 'http://www.wechat.com/wechat/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header('Location:'.$url);
    }

    /** 换取code */
    public function code(Request $request)
    {
        $req = $request->all();
        $result = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$req['code'].'&grant_type=authorization_code');
        $res = json_decode($result,1);
        $user_info = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$res['access_token'].'&openid='.$res['openid'].'&lang=zh_CN');
        $info = json_decode($user_info,1);
        dd($info);
    }

    /** 创建标签 */
    public function create_tag()
    {
        return view('testone.create_tag');
    }

    /** 保存标签 */
    public function save_tag(Request $request)
    {
        $req = $request->all();
        $data = [
            'tag' => [
                'name' => $req['name']
            ]
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->get_access_token();
        $res = $this->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($res,1);
        if($result){
            return redirect('/wechat/list_tag');
        }
    }

    /** 标签列表 */
    public function list_tag()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->get_access_token();
        $res = file_get_contents($url);
        $result = json_decode($res,1);
//        dd($result);
        return view('testone.list_tag',['info'=>$result['tags']]);
    }

    /** 用户列表 */
    public function user_list(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->get_access_token().'&next_openid=';
        $result = file_get_contents($url);
        $res = json_decode($result,1);
//        dd($result);
        $last_info = [];
        foreach($res['data']['openid'] as $k => $v){
            $user_info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->get_access_token().'&openid='.$v.'&lang=zh_CN');
            $user = json_decode($user_info,1);
            $last_info[$k]['nickname'] = $user['nickname'];
            $last_info[$k]['openid'] = $v;
        }
//        dd($last_info);
//        dd($res['data']['openid']);
        return view('testone.user_list',['info'=>$last_info,'tagid'=>$req['tagid']]);

    }

    /** 添加标签用户 */
    public function save_tag_openid(Request $request)
    {
        $req = $request->all();
        $url ='https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->get_access_token();
        $data = [
            'openid_list' => $req['openid_list'],
            'tagid' => $req['tagid']
        ];
        $res = $this->curl_post($url,json_encode($data));
        $result = json_decode($res,1);
        if ($result['errmsg'] == 'ok') {
            return redirect('/wechat/list_tag');
        }else{
            dd($result);
        }
    }

    /** 群发消息视图 */
    public function send_message(Request $request)
    {
        $req = $request->all();
        return view('testone.send_message',['tagid'=>$req['tagid']]);
    }

    /** 群发消息处理 */
    public function send_message_do(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->get_access_token();
        $data = [
            "filter" => [
                "is_to_all" => false,
                "tag_id" => $req['tag_id']
            ],
            "text" => [
                "content" => $req['content']
            ],
            "msgtype" => "text"
        ];

        $res = $this->curl_post($url,json_encode($data));
        $result = json_decode($res,1);
        if($result['errcode'] == 0){
            return redirect('wechat/list_tag');
        }else{
            dd($result);
        }
    }





    public $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', '6379');
    }

    /** 获取token */
    public function get_access_token()
    {
        //加入缓存
        $access_token = 'access_token';
        if ($this->redis->exists($access_token)) {
            //存在
            return $this->redis->get($access_token);
        } else {
            //不存在
            $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf3c63fea45354eec&secret=6ccc59fd6ec3879bad2ad8d420536da3');
            $re = json_decode($result, 1);
            $this->redis->set($access_token, $re['access_token'], $re['expires_in']);  //加入缓存
            return $re['access_token'];
        }
    }
    public function curl_post($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);  //发送post
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($curl);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        curl_close($curl);
        return $data;
    }

}
