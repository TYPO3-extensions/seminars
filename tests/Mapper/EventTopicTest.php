<?php
/***************************************************************
* Copyright notice
*
* (c) 2009-2011 Niels Pardon (mail@niels-pardon.de)
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

require_once(t3lib_extMgm::extPath('oelib') . 'class.tx_oelib_Autoloader.php');

/**
 * Testcase for the 'event mapper' class in the 'seminars' extension.
 *
 * This testcase holds all tests specific to event topics.
 *
 * @package TYPO3
 * @subpackage tx_seminars
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 */
class tx_seminars_Mapper_EventTopicTest extends tx_phpunit_testcase {
	/**
	 * @var tx_oelib_testingFramework
	 */
	private $testingFramework;

	/**
	 * @var tx_seminars_Mapper_Event
	 */
	private $fixture;

	public function setUp() {
		$this->testingFramework = new tx_oelib_testingFramework('tx_seminars');

		$this->fixture = tx_oelib_MapperRegistry::get('tx_seminars_Mapper_Event');
	}

	public function tearDown() {
		$this->testingFramework->cleanUp();

		unset($this->fixture, $this->testingFramework);
	}


	/////////////////////////////////
	// Tests regarding getTopic().
	/////////////////////////////////

	/**
	 * @test
	 */
	public function getTopicForTopicRecordThrowsException() {
		$this->setExpectedException(
			'Exception', 'This function may only be called for date records.'
		);

		$this->fixture->getLoadedTestingModel(
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		)->getTopic();
	}


	//////////////////////////////////////
	// Tests regarding getCategories().
	//////////////////////////////////////

	/**
	 * @test
	 */
	public function getCategoriesForEventTopicReturnsListInstance() {
		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getCategories() instanceof tx_oelib_List
		);
	}

	/**
	 * @test
	 */
	public function getCategoriesForEventTopicWithOneCategoryReturnsListOfCategories() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$category = tx_oelib_MapperRegistry::get('tx_seminars_Mapper_Category')
			->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $category->getUid(), 'categories'
		);

		$this->assertTrue(
			$this->fixture->find($uid)->getCategories()->first() instanceof
				tx_seminars_Model_Category
		);
	}

	/**
	 * @test
	 */
	public function getCategoriesForEventTopicWithOneCategoryReturnsOneCategory() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$category = tx_oelib_MapperRegistry::get('tx_seminars_Mapper_Category')
			->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $category->getUid(), 'categories'
		);

		$this->assertEquals(
			$category->getUid(),
			$this->fixture->find($uid)->getCategories()->getUids()
		);
	}


	////////////////////////////////////
	// Tests regarding getEventType().
	////////////////////////////////////

	/**
	 * @test
	 */
	public function getEventTypeForEventTopicWithoutEventTypeReturnsNull() {
		$this->assertNull(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getEventType()
		);
	}

	/**
	 * @test
	 */
	public function getEventTypeForEventTopicWithEventTypeReturnsEventTypeInstance() {
		$eventType = tx_oelib_MapperRegistry::get('tx_seminars_Mapper_EventType')
			->getLoadedTestingModel(array());

		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array(
					'object_type' => tx_seminars_Model_Event::TYPE_TOPIC,
					'event_type' => $eventType->getUid(),
				)
			)->getEventType() instanceof tx_seminars_Model_EventType
		);
	}


	/////////////////////////////////////////
	// Tests regarding getPaymentMethods().
	/////////////////////////////////////////

	/**
	 * @test
	 */
	public function getPaymentMethodsForEventTopicReturnsListInstance() {
		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getPaymentMethods() instanceof tx_oelib_List
		);
	}

	/**
	 * @test
	 */
	public function getPaymentMethodsForEventTopicWithOnePaymentMethodReturnsListOfPaymentMethods() {
		$paymentMethod = tx_oelib_MapperRegistry::
			get('tx_seminars_Mapper_PaymentMethod')->getNewGhost();
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array(
				'object_type' => tx_seminars_Model_Event::TYPE_TOPIC,
				'payment_methods' => 1,
			)
		);
		$this->testingFramework->createRelation(
			'tx_seminars_seminars_payment_methods_mm', $uid, $paymentMethod->getUid()
		);

		$this->assertTrue(
			$this->fixture->find($uid)->getPaymentMethods()->first() instanceof
				tx_seminars_Model_PaymentMethod
		);
	}

	/**
	 * @test
	 */
	public function getPaymentMethodsForEventTopicWithOnePaymentMethodReturnsOnePaymentMethod() {
		$paymentMethod = tx_oelib_MapperRegistry::
			get('tx_seminars_Mapper_PaymentMethod')->getNewGhost();
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array(
				'object_type' => tx_seminars_Model_Event::TYPE_TOPIC,
				'payment_methods' => 1,
			)
		);
		$this->testingFramework->createRelation(
			'tx_seminars_seminars_payment_methods_mm', $uid, $paymentMethod->getUid()
		);

		$this->assertEquals(
			$paymentMethod->getUid(),
			$this->fixture->find($uid)->getPaymentMethods()->getUids()
		);
	}


	///////////////////////////////////////
	// Tests regarding getTargetGroups().
	///////////////////////////////////////

	/**
	 * @test
	 */
	public function getTargetGroupsForEventTopicReturnsListInstance() {
		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getTargetGroups() instanceof tx_oelib_List
		);
	}

	/**
	 * @test
	 */
	public function getTargetGroupsForEventTopicWithOneTargetGroupReturnsListOfTargetGroups() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$targetGroup = tx_oelib_MapperRegistry::
			get('tx_seminars_Mapper_TargetGroup')->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $targetGroup->getUid(), 'target_groups'
		);

		$this->assertTrue(
			$this->fixture->find($uid)->getTargetGroups()->first() instanceof
				tx_seminars_Model_TargetGroup
		);
	}

	/**
	 * @test
	 */
	public function getTargetGroupsForEventTopicWithOneTargetGroupReturnsOneTargetGroup() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$targetGroup = tx_oelib_MapperRegistry::
			get('tx_seminars_Mapper_TargetGroup')->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $targetGroup->getUid(), 'target_groups'
		);

		$this->assertEquals(
			$targetGroup->getUid(),
			$this->fixture->find($uid)->getTargetGroups()->getUids()
		);
	}


	/////////////////////////////////////
	// Tests regarding getCheckboxes().
	/////////////////////////////////////

	/**
	 * @test
	 */
	public function getCheckboxesForEventTopicReturnsListInstance() {
		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getCheckboxes() instanceof tx_oelib_List
		);
	}

	/**
	 * @test
	 */
	public function getCheckboxesForEventTopicWithOneCheckboxReturnsListOfCheckboxes() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$checkbox = tx_oelib_MapperRegistry::get('tx_seminars_Mapper_Checkbox')
			->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $checkbox->getUid(), 'checkboxes'
		);

		$this->assertTrue(
			$this->fixture->find($uid)->getCheckboxes()->first() instanceof
				tx_seminars_Model_Checkbox
		);
	}

	/**
	 * @test
	 */
	public function getCheckboxesForEventTopicWithOneCheckboxReturnsOneCheckbox() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$checkbox = tx_oelib_MapperRegistry::get('tx_seminars_Mapper_Checkbox')
			->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $checkbox->getUid(), 'checkboxes'
		);

		$this->assertEquals(
			$checkbox->getUid(),
			$this->fixture->find($uid)->getCheckboxes()->getUids()
		);
	}


	///////////////////////////////////////
	// Tests regarding getRequirements().
	///////////////////////////////////////

	/**
	 * @test
	 */
	public function getRequirementsForEventTopicReturnsListInstance() {
		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getRequirements() instanceof tx_oelib_List
		);
	}

	/**
	 * @test
	 */
	public function getRequirementsForEventTopicWithOneRequirementReturnsListOfEvents() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$event = $this->fixture->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $event->getUid(), 'requirements'
		);

		$this->assertTrue(
			$this->fixture->find($uid)->getRequirements()->first() instanceof
				tx_seminars_Model_Event
		);
	}

	/**
	 * @test
	 */
	public function getRequirementsForEventTopicWithOneRequirementsReturnsOneRequirement() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$event = $this->fixture->getNewGhost();
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $uid, $event->getUid(), 'requirements'
		);

		$this->assertEquals(
			$event->getUid(),
			$this->fixture->find($uid)->getRequirements()->getUids()
		);
	}


	///////////////////////////////////////
	// Tests regarding getDependencies().
	///////////////////////////////////////

	/**
	 * @test
	 */
	public function getDependenciesForEventTopicReturnsListInstance() {
		$this->assertTrue(
			$this->fixture->getLoadedTestingModel(
				array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
			)->getDependencies() instanceof tx_oelib_List
		);
	}

	/**
	 * @test
	 */
	public function getDependenciesForEventTopicWithOneDependencyReturnsListOfEvents() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$relatedUid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $relatedUid, $uid, 'dependencies'
		);

		$this->assertTrue(
			$this->fixture->find($relatedUid)->getDependencies()->first() instanceof
				tx_seminars_Model_Event
		);
	}

	/**
	 * @test
	 */
	public function getDependenciesForEventTopicWithOneDependencyReturnsOneDependency() {
		$uid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$relatedUid = $this->testingFramework->createRecord(
			'tx_seminars_seminars',
			array('object_type' => tx_seminars_Model_Event::TYPE_TOPIC)
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_seminars_seminars', $relatedUid, $uid, 'dependencies'
		);

		$this->assertEquals(
			$uid,
			$this->fixture->find($relatedUid)->getDependencies()->getUids()
		);
	}
}
?>