<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-my-account-user-info/1.0.0',
	[
		// Identification
		//
		'label' => 'User info for MyAccount module',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'combodo-my-account/3.2.0'
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'vendor/autoload.php',
			'model.combodo-my-account-user-info.php', // Contains the PHP code generated by the "compilation" of datamodel.combodo-my-account-user-info.xml
		],
		'webservice' => [

		],
		'data.struct' => [
			// add your 'structure' definition XML files here,
		],
		'data.sample' => [
			// add your sample data XML files here,
		],
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => [
			// Module specific settings go here, if any
		],
	]
);

