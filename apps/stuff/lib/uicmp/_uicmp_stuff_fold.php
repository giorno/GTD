<?php

/**
 * @file _uicmp_stuff_fold.php
 * @author giorno
 *
 * Visual component. Specialization of UICMP Fold to allow extra content in the
 * fold and Ajax updating of its content (size and color).
 */

require_once CHASSIS_LIB . 'uicmp/fold.php';

class _uicmp_stuff_fold extends \io\creat\chassis\uicmp\fold
{
	/**
	 * Identifier of the box.
	 * 
	 * @var string
	 */
	protected $boxId = NULL;

	/**
	 * Indicated size of the box.
	 * 
	 * @var int
	 */
	protected $size = NULL;

	/**
	 * Average priority of the box. Computed by StuffBpAlg class.
	 * 
	 * @var int
	 */
	protected $priority = NULL;

	/**
	 * Name of Javascript instance.
	 *
	 * @var string
	 */
	private static $jsName = NULL;

	/**
	 * Indicates if Javascript code for client side variable initialization has
	 * been already executed or not.
	 *
	 * @var bool
	 */
	private static $initialized = FALSE;

	/**
	 * Ajax request URL. Globally shared among all instances.
	 *
	 * @var string
	 */
	private static $g_url = NULL;

	/**
	 * Ajax request parameters. Associative array. Globally shared among all
	 * instances.
	 *
	 * @var array
	 */
	private static $g_params = NULL;

	/**
	 * ID of text element in application icon.
	 *
	 * @var string
	 */
	private static $icoId = NULL;

	/**
	 * Constructor. Has 2 extra parameters.
	 *
	 * @param uicmp $parent reference to parent component
	 * @param string $id component id
	 * @param string $title text to display, name of the box
	 * @param string $box identifier of the box
	 * @param int $size size of the box
	 * @param int $priority average priority of the box
	 */
	public function __construct( &$parent, $id, $title, $box, $size = 0, $priority = 0 )
	{
		parent::__construct( $parent, $id, $title );
		$this->size		= $size;
		$this->boxId	= $box;
		$this->priority	= $priority;
		$this->renderer	= APP_STUFF_UI . 'uicmp/fold.html';
		
		$requirer = $this->getRequirer( );
		if ( !is_null( $requirer ) )
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_CSS, Array( 'inc/stuff/_uicmp_fold.css', $this->id ) );
	}

	/**
	 * Components Javascript variable requires explicit initialization before
	 * variable is used through requirer call in another component(s).
	 *
	 * @param _requirer $requirer
	 */
	public static function initializeJs ( $requirer )
	{
		if ( !self::$initialized )
		{
			$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, 'var ' . self::getJsName( )  . ' = new _uicmp_stuff_folds( \'' . self::$g_url . '\', ' . self::toJsArray( self::$g_params ) . ', \'' . self::$icoId . '\' );' );
			self::$initialized = TRUE;
		}
	}

	/**
	 * Configure Ajax request parameters for update method.
	 *
	 * @param string $url
	 * @param array $params
	 */
	public static function setParams ( $url, $params, $icoId )
	{
		self::$g_url		= $url;
		self::$g_params	= $params;
		self::$icoId	= $icoId;
	}

	/**
	 * Overriding generic method as we need single Javascript variable to mamange
	 */
	public function  generateReqs ( )
	{
		$requirer = $this->getRequirer( );

		/**
		 * Implicit initialization of Javascript instance.
		 */
		self::initializeJs( $requirer );

		$requirer->call( \io\creat\chassis\uicmp\vlayout::RES_JSPLAIN, self::$jsName  . '.register( \'' . $this->boxId . '\', \'' . $this->getHtmlId( ) . '\' );' );
	}

	/**
	 * Instance independent access to Javascript variable name. Implemented to
	 * be used before any class instance is created.
	 *
	 * @return string
	 */
	public static function getJsName ( $requirer = NULL )
	{
		if ( is_null( self::$jsName ) )
			self::$jsName = '_uicmp_stuff_folds_i';

		if ( !is_null( $requirer ) )
			self::initializeJs ( $requirer );

		return self::$jsName;
	}

	/**
	 * Overriding generic method as we need single instance for all special
	 * folds.
	 *
	 * @return string
	 */
	public function getJsVar ( ) { return self::getJsName( $this->getRequirer( ) ); }

	/**
	 * Read interface for size of the box.
	 *
	 * @return int
	 */
	public function getSize ( ) { return $this->size; }

	/**
	 * Read interface for average priority of the box.
	 * 
	 * @return nt
	 */
	public function getPriority ( ) { return $this->priority; }
}

?>