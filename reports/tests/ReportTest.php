<?php

/**
 * @package reports
 * @subpackage tests
 */
class ReportTest extends SapphireTest {

	public function testGetReports() {
		$reports = SS_Report::get_reports();
		$this->assertNotNull($reports, "Reports returned");
		$previousSort = 0;
		foreach($reports as $report) {
			$this->assertGreaterThanOrEqual($previousSort, $report->sort, "Reports are in correct sort order");
			$previousSort = $report->sort;
		}
	}

	public function testExcludeReport() {
		$reports = SS_Report::get_reports();
		$reportNames = array();
		foreach($reports as $report) {
			$reportNames[] = $report->class;
		}
		$this->assertContains('ReportTest_FakeTest',$reportNames,'ReportTest_FakeTest is in reports list');

		//exclude one report
		SS_Report::add_excluded_reports('ReportTest_FakeTest');

		$reports = SS_Report::get_reports();
		$reportNames = array();
		foreach($reports as $report) {
			$reportNames[] = $report->class;
		}
		$this->assertNotContains('ReportTest_FakeTest',$reportNames,'ReportTest_FakeTest is NOT in reports list');

		//exclude two reports
		SS_Report::add_excluded_reports(array('ReportTest_FakeTest','ReportTest_FakeTest2'));

		$reports = SS_Report::get_reports();
		$reportNames = array();
		foreach($reports as $report) {
			$reportNames[] = $report->class;
		}
		$this->assertNotContains('ReportTest_FakeTest',$reportNames,'ReportTest_FakeTest is NOT in reports list');
		$this->assertNotContains('ReportTest_FakeTest2',$reportNames,'ReportTest_FakeTest2 is NOT in reports list');
	}

	public function testAbstractClassesAreExcluded() {
		$reports = SS_Report::get_reports();
		$reportNames = array();
		foreach($reports as $report) {
			$reportNames[] = $report->class;
		}
		$this->assertNotContains('ReportTest_FakeTest_Abstract',
			$reportNames,
			'ReportTest_FakeTest_Abstract is NOT in reports list as it is abstract');
	}

	public function testPermissions() {
		$report = new ReportTest_FakeTest2();

		// Visitor cannot view
		Session::clear("loggedInAs");
		$this->assertFalse($report->canView());

		// Logged in user that cannot view reports
		$this->logInWithPermission('SITETREE_REORGANISE');
		$this->assertFalse($report->canView());

		// Logged in with report permissions
		$this->logInWithPermission('CMS_ACCESS_ReportAdmin');
		$this->assertTrue($report->canView());

		// Admin can view
		$this->logInWithPermission('ADMIN');
		$this->assertTrue($report->canView());
	}
}

/**
 * @package reports
 * @subpackage tests
 */
class ReportTest_FakeTest extends SS_Report implements TestOnly {
	public function title() {
		return 'Report title';
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Page Title"
			)
		);
	}
	public function sourceRecords($params, $sort, $limit) {
		return new ArrayList();
	}

	public function sort() {
		return 100;
	}
}

/**
 * @package reports
 * @subpackage tests
 */
class ReportTest_FakeTest2 extends SS_Report implements TestOnly {
	public function title() {
		return 'Report title 2';
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Page Title 2"
			)
		);
	}
	public function sourceRecords($params, $sort, $limit) {
		return new ArrayList();
	}

	public function sort() {
		return 98;
	}
}

/**
 * @package reports
 * @subpackage tests
 */
abstract class ReportTest_FakeTest_Abstract extends SS_Report implements TestOnly {
	
	public function title() {
		return 'Report title Abstract';
	}

	public function columns() {
		return array(
			"Title" => array(
				"title" => "Page Title Abstract"
			)
		);
	}
	public function sourceRecords($params, $sort, $limit) {
		return new ArrayList();
	}

	public function sort() {
		return 5;
	}
}

