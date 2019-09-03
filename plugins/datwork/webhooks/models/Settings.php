<?php


namespace Datwork\Webhooks\Models;

use Model;
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'datwork_webhooks_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public $link;
    public function __construct()
    {
        $this->link=url('/git/webhooks');
        return parent::__construct();
    }


}