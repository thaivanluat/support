<?php


namespace Datwork\Webhooks\Classes;


class GitUtils{
    public static function initGit($source){
        $pwd = getcwd();
        $command = 'cd ' . $pwd . '/../ && git clone ' . $source . ' 2>&1';
        $output = '';
        $return_val = '';
        exec($command, $output, $return_val);
        trace_log('Webhooks << initGit return_val:'.$return_val.' '.json_encode($output));
        return $output;
    }

    public static function pull($origin = ''){
        $pwd = getcwd();
        $command = 'cd ' . $pwd . '/ && php artisan october:util git pull && git checkout . && git pull ' . $origin . ' 2>&1';
        $output = '';
        $return_val = '';
        exec($command, $output, $return_val);
        trace_log('Webhooks << pull return_val:'.$return_val.' '.json_encode($output));
        return $output;
    }


    public static function reset($hard){
        $pwd = getcwd();
        $command = 'cd ' . $pwd . '/ && git reset --hard ' . $hard . ' 2>&1';
        $output = '';
        $return_val = '';
        exec($command, $output, $return_val);
        trace_log('Webhooks << reset return_val:'.$return_val.' '.json_encode($output));
        return $output;
    }
    public static function exec($command){
        $pwd = getcwd();
        $command = 'cd ' . $pwd . '/ && ' . $command . ' 2>&1';
        $output = '';
        $return_val = '';
        exec($command, $output, $return_val);
        trace_log('Webhooks <<  '.$command.'  return_val:'.$return_val.' '.json_encode($output));
        return $output;
    }
}
