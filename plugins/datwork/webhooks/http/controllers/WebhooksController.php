<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 2019/1/4
 * Time: 6:25 PM
 */

namespace Datwork\Webhooks\Http\Controllers;

use Datwork\Webhooks\Models\Settings;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Datwork\Webhooks\Classes\GitUtils;

class WebhooksController extends BaseController
{

    private $git_coding = 'Coding.net Hook';
    private $github='GitHub-Hookshot';


    /**
     * Controller constructor
     *
     */
    public function __construct()
    {

    }


    private function check($signature, $bodyData, $key)
    {
        //验证签名
        return $signature == 'sha1=' . hash_hmac('sha1', $bodyData, $key, false);
    }

    public function checkSignature()
    {

        return Settings::get('key') . ':' . Settings::get('enabled');

    }

    private function pull($origin = '')
    {
        GitUtils::pull($origin);
        // 拉最新版本
    }


    private function onGitEvent($event, $uniqueid, $origin = '')
    {
//        trace_log('git:'.$event.' '.$uniqueid.' '.$origin);
        switch ($event){
            case 'ping':
                break;
            case 'push':
                GitUtils::pull($origin);
                break;
            case 'merge request':
                GitUtils::pull($origin);
                break;
        }
    }


    public function webhooks()
    {
//        trace_log('webhooks:enabled '.Settings::get('enabled'));
        if (Settings::get('enabled')) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $bodyData = @file_get_contents('php://input');


            $origin='';

            if ($user_agent == $this->git_coding) {
                //Coding Hooks
                $signature = $_SERVER['HTTP_X_CODING_SIGNATURE'];//sha1=b5f93b63af018c61261caf6117e757f94f87cbb5
                $event = $_SERVER['HTTP_X_CODING_EVENT'];
                $uniqueid = $_SERVER['HTTP_X_CODING_DELIVERY'];

            }else if(strpos($user_agent,$this->github,0)!==false){
                //git hub
                $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];//sha1=b5f93b63af018c61261caf6117e757f94f87cbb5
                $event = $_SERVER['HTTP_X_GITHUB_EVENT'];
                $uniqueid = $_SERVER['HTTP_X_GITHUB_DELIVERY'];
            }

            $key=Settings::get('key');

            if(strlen($key)==0){
                $this->onGitEvent($event, $uniqueid,$origin);
            }else if ($this->check($signature, $bodyData, $key)) {
                //签名验证通过 执行
                $this->onGitEvent($event, $uniqueid,$origin);
            }else{
                return App::abort(403);
            }

            return 'success';
        } else {
            return App::abort(503);
        }

    }


}