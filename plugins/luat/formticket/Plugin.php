<?php namespace Luat\FormTicket;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
            'Luat\FormTicket\Components\Form'    => 'Form',
        ];
    }

    public function registerSettings()
    {
    }
}
