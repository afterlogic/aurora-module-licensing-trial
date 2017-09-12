<?php

namespace Aurora\Modules\LicensingTrial;

class Module extends \Aurora\System\Module\AbstractModule
{
	public function init() 
	{
		$this->subscribeEvent('Licensing::GetLicenseKey::after', array($this, 'onAfterGetLicenseKey'));
	}
	
	public function onAfterGetLicenseKey($Args, &$Result)
	{
		if (empty($Result))
		{
			$sRemoteUrl = $this->getConfig('RemoteHost', '');
			
			$oResponse = empty($sRemoteUrl) ? null : json_decode(\file_get_contents($sRemoteUrl));
			
			if ($oResponse)
			{
				if (isset($oResponse->success) && $oResponse->success === true && !empty($oResponse->key))
				{
					$Result = $oResponse->key;

					$oLicensingDecorator = \Aurora\System\Api::GetModuleDecorator('Licensing');

					if ($oLicensingDecorator)
					{
						$oLicensingDecorator->UpdateSettings($Result);
					}
				}
				else
				{
					\Aurora\System\Api::Log('Bad response from Key Service', \Aurora\System\Enums\LogLevel::Error);
				}
			}
		}
	}
}
