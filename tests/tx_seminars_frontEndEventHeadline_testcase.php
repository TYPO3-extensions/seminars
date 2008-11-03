<?php
/***************************************************************
* Copyright notice
*
* (c) 2008 Bernd Schönbach <bernd@oliverklee.de>
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(t3lib_extMgm::extPath('seminars') . 'pi1/class.tx_seminars_frontEndEventHeadline.php');
require_once(t3lib_extMgm::extPath('seminars') . 'class.tx_seminars_configgetter.php');

require_once(t3lib_extMgm::extPath('oelib') . 'class.tx_oelib_testingFramework.php');

/**
 * Testcase for the 'frontEndEventHeadline' class in the 'seminars' extension.
 *
 * @package TYPO3
 * @subpackage tx_seminars
 *
 * @author Bernd Schönbach <bernd@oliverklee.de>
 */
class tx_seminars_frontEndEventHeadline_testcase extends tx_phpunit_testcase {
	/**
	 * @var tx_seminars_frontEndEventHeadline
	 */
	private $fixture;

	/**
	 * @var tx_oelib_testingFramework
	 */
	private $testingFramework;

	/**
	 * @var integer event begin date
	 */
	private $eventDate = 0;

	/**
	 * @var integer UID of the event to create the headline for
	 */
	private $eventId = 0;

	public function setUp() {
		$this->testingFramework	= new tx_oelib_testingFramework('tx_seminars');
		$this->testingFramework->createFakeFrontEnd();

		// just picked some random date (2001-01-01 00:00:00)
		$this->eventDate = 978303600;
		$this->eventId = $this->testingFramework->createRecord(
			SEMINARS_TABLE_SEMINARS,
			array(
				'pid' => 0,
				'title' => 'Test event',
				'begin_date' => $this->eventDate,
			)
		);

		$this->fixture = new tx_seminars_frontEndEventHeadline(
			array(
				'isStaticTemplateLoaded' => 1,
				'templateFile' => 'EXT:seminars/pi1/seminars_pi1.tmpl',
			),
			$GLOBALS['TSFE']->cObj
		);
	}

	public function tearDown() {
		$this->testingFramework->cleanUp();

		$this->fixture->__destruct();
		unset($this->fixture, $this->testingFramework);
	}


	//////////////////////////////////
	// Tests for the render function
	//////////////////////////////////

	public function testRenderWithUidOfExistingEventReturnsTitleOfSelectedEvent() {
		$this->fixture->piVars['uid'] = $this->eventId;

		$this->assertContains(
			'Test event',
			$this->fixture->render()
		);
	}

	public function testRenderWithUidOfExistingEventReturnsDateOfSelectedEvent() {
		$configGetter = new tx_seminars_configgetter();

		$dateFormat = '%d.%m.%Y';
		$configGetter->setConfigurationValue(
			'dateFormatYMD',
			$dateFormat
		);
		$this->fixture->piVars['uid'] = $this->eventId;

		$this->assertContains(
			strftime($dateFormat, $this->eventDate),
			$this->fixture->render()
		);

		$configGetter->__destruct();
		unset($configGetter);
	}

	public function testRenderReturnsEmptyStringIfNoUidIsSetInPiVar() {
		unset($this->fixture->piVars['uid']);

		$this->assertEquals(
			'',
			$this->fixture->render()
		);
	}

	public function testRenderReturnsEmptyStringIfUidOfInexistentEventIsSetInPiVar() {
		$this->fixture->piVars['uid']
			= $this->testingFramework->getAutoIncrement('tx_seminars_seminars');

		$this->assertEquals(
			'',
			$this->fixture->render()
		);
	}

	public function testRenderReturnsEmptyStringIfNonNumericEventUidIsSetInPiVar() {
		$this->fixture->piVars['uid'] = 'foo';

		$this->assertEquals(
			'',
			$this->fixture->render()
		);
	}
}
?>