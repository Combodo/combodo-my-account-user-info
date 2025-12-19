<?php

namespace Combodo\iTop\MyAccount\Test\Integration;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Dict;
use User;

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

	public function testMyAccountUserInfoMenu()
	{
		$aPost = [
			'auth_user' => $this->oUser->Get('login'), 
			'auth_pwd' => $this->sPassword
		];

		$sOutput = $this->CallItopUri('pages/exec.php?exec_module=combodo-my-account&exec_page=index.php&exec_env=production#TwigBaseTabContainer=tab_MyAccountUserInfoTabTitle',
			$aPost);

		// Assert
		$this->AssertStringContains(Dict::S('MyAccount:UserInfo:Tab:Title'), $sOutput, 'The page should display MyAccount user info tab');
	}

	protected function AssertStringContains($sNeedle, $sHaystack, $sMessage): void
	{
		$this->assertNotNull($sNeedle, $sMessage);
		$this->assertNotNull($sHaystack, $sMessage);

		$this->assertTrue(false !== strpos($sHaystack, $sNeedle), $sMessage . PHP_EOL . "needle: '$sNeedle' not found in content below:" . PHP_EOL . PHP_EOL . $sHaystack);
	}
}
