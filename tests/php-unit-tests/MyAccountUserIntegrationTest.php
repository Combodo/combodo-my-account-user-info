<?php

namespace Combodo\iTop\MyAccount\Test\Integration;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use User;
use Dict;
use UserRights;

/**
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 */
class MyAccountUserIntegrationTest extends ItopDataTestCase  {
	//iTop called from outside
	//users need to be persisted in DB
	const USE_TRANSACTION = false;

	protected string $sPassword;
	protected User $oUser;
	protected string $sUniqId;

	protected function setUp(): void {
		parent::setUp();

		$this->sUniqId = "MyAccountUser" . uniqid();
		$this->sPassword = "abCDEF12345@";
		$aData = array(
			'org_id' => $this->CreateOrganization($this->sUniqId),
			'first_name' => 'Jesus',
			'name' => 'Deus',
			'email' => 'guru@combodo.com',
		);
		$iPerson = $this->CreateObject('Person', $aData);
		$this->oUser = $this->CreateUser('login' . uniqid(),
			ItopDataTestCase::$aURP_Profiles['Service Desk Agent'],
			$this->sPassword,
			$iPerson
		);
	}

	protected function tearDown(): void {
		UserRights::Logoff();
		parent::tearDown();
		$_SESSION = [];
	}

	public function testMyAccountUserInfoMenu()
	{
		$sOutput = $this->CallItopUrl('/pages/exec.php?exec_module=combodo-my-account&exec_page=index.php&exec_env=production#TwigBaseTabContainer=tab_MyAccountUserInfoTabTitle', ['auth_user' => $this->oUser->Get('login'), 'auth_pwd' => $this->sPassword]);

		// Assert
		$this->AssertStringContains(Dict::S('MyAccount:UserInfo:Tab:Title'), $sOutput, 'The page should display MyAccount user info tab');
	}

	protected function AssertStringContains($sNeedle, $sHaystack, $sMessage): void
	{
		$this->assertNotNull($sNeedle, $sMessage);
		$this->assertNotNull($sHaystack, $sMessage);

		$this->assertTrue(false !== strpos($sHaystack, $sNeedle), $sMessage . PHP_EOL . "needle: '$sNeedle' not found in content below:" . PHP_EOL . PHP_EOL . $sHaystack);
	}

	protected function CallItopUrl($sUri, ?array $aPostFields = null, $bIsPost=true)
	{
		$ch = curl_init();

		$sUrl = MetaModel::GetConfig()->Get('app_root_url')."/$sUri";
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_POST, $bIsPost ? 1 : 0);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$sOutput = curl_exec($ch);
		//echo "$sUrl error code:".curl_error($ch);
		curl_close($ch);

		return $sOutput;
	}
}
