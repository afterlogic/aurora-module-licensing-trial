<?php

namespace Aurora\Modules\LicensingTiral;

//include_once __DIR__.'/classes/KI.php';

class Module extends \Aurora\System\Module\AbstractModule
{
	public function init() 
	{
//		$this->oApiAccountsManager = new Managers\Accounts\Manager($this);

		$this->subscribeEvent('Licensing::GetLicenseKey::after', array($this, 'onAfterGetLicenseKey'));
	}
	
	public function onAfterGetLicenseKey($Args, &$Result)
	{
		var_dump($Result);
	}
	
	public function GetSettings()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);
		
		return array(
			'LicenseKey' => $this->GetLicenseKey(),
		);
	}
	
	/**
	 * @return bool
	 */
	public function UpdateSettings($LicenseKey)
	{
		$bResult = false;
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);
		
		if ($LicenseKey !== null)
		{
			\Aurora\System\Api::GetSettings()->SetConf('LicenseKey', $LicenseKey);
			$bResult = \Aurora\System\Api::GetSettings()->Save();
		}
		
		return $bResult;
	}
}
