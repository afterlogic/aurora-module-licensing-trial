<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\LicensingTrial;

use Aurora\System\SettingsProperty;

/**
 * @property bool $Disabled
 * @property string $RemoteHost
 */

class Settings extends \Aurora\System\Module\Settings
{
    protected function initDefaults()
    {
        $this->aContainer = [
            "Disabled" => new SettingsProperty(
                false,
                "bool",
                null,
                "",
            ),
            "RemoteHost" => new SettingsProperty(
                "",
                "string",
                null,
                "",
            ),
        ];
    }
}
