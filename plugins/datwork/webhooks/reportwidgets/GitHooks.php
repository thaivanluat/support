<?php


namespace Datwork\Webhooks\ReportWidgets;


use Backend\Classes\ReportWidgetBase;
use Backend\Facades\BackendAuth;
use Datwork\Webhooks\Classes\GitUtils;

class GitHooks extends ReportWidgetBase
{

    private function check()
    {
        if (BackendAuth::getUser()) {
            if (BackendAuth::getUser()->hasAccess(['datwork.webhooks.access_settings'])) {
                return true;

            }
        }
        return false;
    }

    public function render()
    {


            return $this->makePartial('widget');


    }

    public function onPull()
    {
        if ($this->check()) {
            $messages = GitUtils::pull();
        } else {
            $messages = ['messages' => ['no authority'], 'class' => 'error'];
        }

        return [
            '^#myDiv' => $this->makePartial('message', ['messages' => $messages, 'class' => 'success'])
        ];
    }

    public function onReset()
    {
        if ($this->check()) {
            $hash = post('hash');
            if (strlen($hash) < 4) {
                $messages = ['messages' => ['hash is required'], 'class' => 'error'];
            } else {
                $messages = ['messages' => GitUtils::reset($hash), 'class' => 'success'];
            }
        } else {
            $messages = ['messages' => ['no authority'], 'class' => 'error'];
        }
        return [
            '^#myDiv' => $this->makePartial('message', $messages)
        ];
    }

    public function onCommand()
    {
        if ($this->check()) {
            $command = post('command');
            if (strlen($command) < 2) {
                $messages = ['messages' => ['command is required'], 'class' => 'error'];
            } else {
                $messages = ['messages' => GitUtils::exec($command), 'class' => 'success'];
            }
        } else {
            $messages = ['messages' => ['no authority'], 'class' => 'error'];
        }

        return [
            '^#myDiv' => $this->makePartial('message', $messages)
        ];
    }

}