<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\LicensingTrial;

use Aurora\Api;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2023, Afterlogic Corp.
 *
 * @property Settings $oModuleSettings
 *
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
    public function init()
    {
        $this->subscribeEvent('Licensing::GetLicenseKey::after', array($this, 'onAfterGetLicenseKey'));
    }

    /**
     * @return Module
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return Module
     */
    public static function Decorator()
    {
        return parent::Decorator();
    }

    /**
     * @return Settings
     */
    public function getModuleSettings()
    {
        return $this->oModuleSettings;
    }

    public function onAfterGetLicenseKey($Args, &$Result)
    {
        try {
            \Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

            if (empty($Result)) {
                $sRemoteUrl = $this->oModuleSettings->RemoteHost;
                $oResponse = empty($sRemoteUrl) ? null : json_decode(\file_get_contents($sRemoteUrl));

                if ($oResponse) {
                    if (isset($oResponse->success) && $oResponse->success === true && !empty($oResponse->key)) {
                        $Result = $oResponse->key;

                        /** @var \Aurora\Modules\Licensing\Module $oLicensingDecorator */
                        $oLicensingDecorator = Api::GetModuleDecorator('Licensing');

                        if ($oLicensingDecorator) {
                            $oLicensingDecorator->UpdateSettings($Result);
                        }
                    } else {
                        \Aurora\System\Api::Log('Bad response from Key Service', \Aurora\System\Enums\LogLevel::Error);
                    }
                }
            }
        } catch (\Aurora\System\Exceptions\ApiException $oException) {
        }
    }
}
