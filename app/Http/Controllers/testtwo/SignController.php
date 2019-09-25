<?php

namespace App\Http\Controllers\testtwo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SignController extends Controller
{
    public function create_menu()
    {
        return view('testtwo.create_menu');
    }

    public function save_menu(Request $request)
    {
        $req = $request->all();
        unset($req['_token']);
        if (empty($req['name2'])) {
            $req['button_type'] = 1;
        }else{
            $req['button_type'] = 2;
        }
        $res = DB::table('menu')->insertGetId($req);
        if ($res) {
            $this->menu();
        }else{
            dd($res);
        }
    }

    public function menu()
    {
        $data = [];
        $menu_list = DB::table('menu')->select(['name1'])->groupBy('name1')->get(); // 获取一级菜单
        foreach($menu_list as $vv) {
            $menu_info = DB::table('menu')->where(['name1'=>$vv->name1])->get(); // 获取一级菜单下所有 三维数组
            $menu = [];
            foreach ($menu_info as $v) {
                $menu[] = (array)$v; // 获取一级菜单下所有中的一个 二维数组
            }
            $arr = [];
            foreach ($menu as $v) { // 获取一级菜单下所有中的一个 一维数组
                if ($v['button_type'] == 1) { // 普通一级菜单
                    if ($v['type'] == 1) { // click
                        $arr = [
                            'type' => 'click',
                            'name' => $v['name1'],
                            'key'  => $v['event_value']
                        ];
                    }elseif ($v['type' == 2]) { // view
                        $arr = [
                            'type' => 'view',
                            'name' => $v['name1'],
                            'url'  => $v['event_value']
                        ];
                    }
                }elseif ($v['button_type'] == 2) { // 带有二级菜单的一级菜单
                    $arr['name'] = $v['name1'];
                    if ($v['type'] == 1) { // click
                        $button_arr = [
                            'type' => 'click',
                            'name' => $v['name2'],
                            'key'  => $v['event_value']
                        ];
                    }elseif ($v['type'] == 2) { // view
                        $button_arr = [
                            'type' => 'view',
                            'name' => $v['name2'],
                            'url'  => $v['event_value']
                        ];
                    }
                    $arr['sub_button'][] = $button_arr;
                }
            }
            $data['button'][] = $arr;
        }
//         dd($data);

        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->get_access_token();
        /*$data = [
            'button' => [
                [
                    'type' => 'click',
                    'name' => '今日歌曲',
                    'key' => 'V1001_TODAY_MUSIC'
                ],
                [
                    'name' => '菜单',
                    'sub_button' => [
                        [
                            'type' => 'view',
                            'name' => '搜索',
                            'url'  => 'http://www.soso.com/'
                        ],
                        [
                            'type' => 'miniprogram',
                            'name' => 'wxa',
                            'url' => 'http://mp.weixin.qq.com',
                            'appid' => 'wx286b93c14bbf93aa',
                            'pagepath' => 'pages/lunar/index'
                        ],
                        [
                            'type' => 'click',
                            'name' => '赞一下我们',
                            'key'  => 'V1001_GOOD'
                        ]
                    ]
                ]
            ]
        ];*/
        $res = $this->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = json_decode($res,1);
        if ($result['errcode'] == 0) {
            return redirect('sign/create_menu');
        }else{
            dd($result);
        }
    }



    public $redis;
    public function __construct()
    {
        $this->redis = new \Redis;
        $this->redis->connect('127.0.0.1','6379');
    }

    public function get_access_token()
    {
        $token = 'token';
        if ($this->redis->exists($token)) {
            return $this->redis->get('token');
        }else{
            $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf3c63fea45354eec&secret=6ccc59fd6ec3879bad2ad8d420536da3');
            $res = json_decode($result,1);
            $this->redis->set($token,$res['access_token'],$res['expires_in']);
            return $res['access_token'];
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
