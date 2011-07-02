<?php
/**
 * @file class.StuffBpAlg.php
 *
 * Routines for computing average box priority color to be shown on the box tab.
 * This color is to be used for background of number of items in the box.
 *
 * Bp stands for "Box Priority".
 *
 * @author giorno
 */

class StuffBpAlg
{

	/**
	 * Pick proper algorithm, run it over data and return result.
	 *
	 * @param <type> $avg average priorities per each box
	 * @param <type> $alg setting value for algorithm [hostadter|simpleMath]
	 */
	public static function Proxy ( $avg, $alg )
	{
		switch ( $alg )
		{
			case 'static':
				if ( is_array( $avg ) )
					foreach ( $avg as $box => $val )
						$ret[$box] = 99;

				return $ret;
			break;

			case 'simpleMath': return self::SimpleMath( $avg ); break;
				
			case 'hofstadter':
			default:
				return self::Hofstadter( $avg );
			break;
		}
	}

	/**
	 * Very simple algorithm to compute colors for boxes.
	 *
	 * @param <array> $avg average priority of items per box
	 */
	public static function SimpleMath ( $avg )
	{
		$ret = $avg;
		if ( is_array( $avg ) )
		{
			foreach ( $avg as $box => $val )
			{
				$ret[$box] = round( $val );
			}
		}

		return $ret;
	}

	/**
	 * So-called Hofstadter algorithm to compute colors for boxes. Algorithm is
	 * based upon using lowest, highest and average of averages. This is
	 * original way how to achieve having in almost all cases at least small
	 * diversity in boxes item count colors.
	 *
	 * Priorities 0 and 4 are computed only in half-interval:
	 * 0 => 0 - 0,5
	 * 4 => 3,5 - 4
	 *
	 * Average priorities near to 2 are colored as priority 2. Rule is defined
	 * by fraction of interval.
	 *
	 * Named after Leonard Hofstadter, character from The Big Bang Theory.
	 *
	 * @param <array> average priority of items per box
	 * @param <array> lowest priority per box
	 * @param <array> highest priority per box
	 */
	public static function Hofstadter ( $avg )
	{
		$ret = $avg;
		/*echo "<pre>";
		var_dump($ret);*/
		if ( is_array( $avg ) )
		{
			if ( asort( $avg/*, SORT_NUMERIC*/ ) )
			{
				$interval = end( $avg ) - reset( $avg );
				$step = $interval / 4;

				/*if ( $step != 0 )
				{*/
					foreach ( $avg as $box => $val )
					{
						if ( ( $step == 0 ) || ( ( $val >= ( 2 - $step/4 ) ) && ( $val <= ( 2 + $step/4 ) ) ) )
							$ret[$box] = 2;
						else
							$ret[$box] = (int)round( ( $val - reset( $avg ) ) * 4 / $interval );//( /*floor*/ * 4 ) / $interval;
						/*
						 * 
						 * Normalize. Should not ever be needed.
						 */
						if ( $ret[$box] > 4 )
							$ret[$box] = 4;
						if ( $ret[$box] < 0 )
							$ret[$box] = 0;
					}
				//}
			}
		}
		return $ret;
	}
}

?>