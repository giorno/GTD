<?php

/**
 * @file _uicmp_cpe_cal.php
 * @author giorno
 *
 * Calendar dialog for picking day from 3 month template. Also provides data
 * for Today and Tomorrow shortcuts.
 */

require_once CHASSIS_LIB . 'uicmp/uicmp.php';

class _uicmp_cpe_cal extends \io\creat\chassis\uicmp\uicmp
{
	/**
	 * Timezone instance for calendar data.
	 * 
	 * @var <n7_tz>
	 */
	protected $tz = NULL;

	public function  __construct ( &$parent, $id = NULL, $tz )
	{
		parent::__construct( $parent, $id );
		$this->type		= __CLASS__;
		$this->renderer	= APP_STUFF_UI . 'uicmp/cpe_cal.html';
		$this->jsPrefix	= '_uicmp_stuff_cal_i_';
		$this->tz		= $tz;
	}

	/**
	 * Provide data for next three months calendar.
	 *
	 * @param <int> timestamp of day from first month of three
	 * @return <array> data for Ui builder
	 */
	private function ThreeMonths ( $date )
	{
		$month = (int)date( "n", $date );
		$day = (int)date( "j", $date );
		$year = (int)date( "Y", $date );

		for ( $i = 1; $i <= 3; $i++ )
		{
			$week = 0;
			$first = mktime( 0, 0, 0, $month, 1, $year );
			$months[$i]['year'] = $year;
			$months[$i]['month'] = $month;
			$months[$i]['name'] = strftime( "%B", $first );

			/**
			 * Get starting day-of-week index, this should use i18n parameters.
			 */
			$weekday = (int)date( "w", $first );
			if ( $weekday == 0 ) $weekday = 7;

			/**
			 * Compute empty days before 1st day of processed month.
			 */
			if ( $weekday != 1 )
			{
				for ( $j = 1; $j < $weekday; $j++ )
					$months[$i]['weeks'][$week][] = array( 'day' => '', 'holiday' => false );
			}

			/**
			 * All month days.
			 */
			$days = (int) date( "t", $first );
			for ( $j = 1; $j <= $days; $j++ )
			{
				$stamp = mktime( 0, 0, 0, $month, $j, $year );
				if ( ( $weekday = (int)date( "w", $stamp ) ) == 0 ) $weekday = 7;

				$months[$i]['weeks'][$week][] = array( 'day' => $j, 'holiday' => ( $weekday > 5 ), 'click' => date( "M j, Y", $stamp ) );

				if ( $weekday == 7 ) $week++;
			}

			/**
			 * Compute empty days after last day of processed month.
			 */
			if ( $weekday != 7 )
			{
				for ( $j = $weekday + 1; $j <= 7; $j++ )
					$months[$i]['weeks'][$week][] = array( 'day' => '', 'holiday' => false );
			}

			$month++;
			if ( $month > 12 ) { $year++; $month = 1; }
		}

		/**
		 * Compute last (dummy) week to all months should have same number of
		 * weeks.
		 */
		$maxWeeks = 0;
		for ( $i = 0; $i < 7; $i++ )
			$dummy[] = array( 'day' => '&nbsp;', 'holiday' => false );
		foreach ( $months as $index => $month )
			if ( count( $month['weeks'] ) > $maxWeeks ) $maxWeeks = count( $month['weeks'] );

		foreach ( $months as $index => $month )
		{
			while ( count( $months[$index]['weeks'] ) < $maxWeeks )
				$months[$index]['weeks'][] = $dummy;
		}

		return $months;
	}

	/**
	 * Returns array populated with data for calendar UI.
	 *
	 * @return <array>
	 */
	public function getData ( )
	{
		$this->tz->importTzDateTime( "now" );
		$ret['cal'] = $this->ThreeMonths( $this->tz->exportTzStamp() );

		$ret['today'] = $this->tz->jsToday( );
		$ret['today_day'] = $this->tz->jsTodayDay( );
		$ret['tomorrow'] = $this->tz->jsTomorrow( );

		return $ret;
	}

	/**
	 * Dummy implementation to conform abstract parent.
	 */
	public function  generateReqs ( ) { }
}

?>