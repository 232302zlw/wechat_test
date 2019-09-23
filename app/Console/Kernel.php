<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /** 获取token */
    public function get_access_token()
    {
        $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf3c63fea45354eec&secret=6ccc59fd6ec3879bad2ad8d420536da3');
        $re = json_decode($result, 1);
        return $re['access_token'];
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

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function(){
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
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
