<?php
/**
 * Snap base class that manages database & memcached connections
 * @author Christopher Outramtemplated from code on Lone Ranger 01/12/2010
 * @global CONFIG
 * @name SnapBase
 */
class SnapBase{

	/**
	 * memcache connection
	 *
	 * @var SnapMemcache
	 */
	public $memcache;
	public $db;

	/**
	 * Constructor function, setting up database & memcached connections
	 * @return void
	 */
	public function __construct(){
		$this->memcache = new SnapMemcache();
		$this->db = new SnapDatabase();
	}

	public static function killConnections(){
		session_write_close();
		SnapMemcache::killConnections();
		SnapDatabase::killConnections();
	}

}