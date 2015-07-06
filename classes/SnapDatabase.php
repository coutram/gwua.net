<?php
/**
 * Snap database class that manages database connections
 * @author Christopher Outram templated from code on Lone Ranger 01/12/2010
 * @name SnapDatabase
 */
class SnapDatabase {

	private static $dbs = array();
	private $chosenDbs = array();
	private $active_conn;

	public function __construct() {
	}

	public function prepare($sql){
		if ($this->active_conn == NULL || !$Query = $this->active_conn->prepare($sql)){
			throw new SnapDatabaseException($this->active_conn->error);
		}
		return $Query;
	}

	/**
	 * Execute an UPDATE, INSERT or DELETE sql statement on the database
	 */
	public function execute_put($object = '', $uid = 0, $sql = '', $paramtypes = null, $params=array(), $getInsertId = FALSE){
		try {
			$this->active_conn = &$this->determine_connection('put', $object, $uid);
			$query = $this->prepare($sql);
			if($paramtypes != NULL){
				//if additional arguments, need to bind them
				$params_final = array(0 => $paramtypes);
				foreach($params as $param){
					$params_final[] = $param;
				}

				if(!call_user_func_array(array($query, 'bind_param'), $params_final)){
					die("Bind parameters failed");
				}

			}

			$query->execute();

			if ($getInsertId === TRUE) {
				$result = $query->insert_id;
			} else {
				$result = ($query->affected_rows > 0)?$query->affected_rows:null;
			}
			$query->close();
			$this->active_conn = null;
			return $result;
		} catch (SnapDatabaseException $e) {
			return $e->getMessage();
		}

	}

	public function str_replace_once($search, $replace, $subject) {
		$firstChar = strpos($subject, $search);
		if($firstChar !== false) {
			$beforeStr = substr($subject,0,$firstChar);
			$afterStr = substr($subject, $firstChar + strlen($search));
			return $beforeStr.$replace.$afterStr;
		} else {
			return $subject;
		}
	}

	public function prepare_fake($sql,$params,$paramtypes){
		$str_count=0;
		foreach($params as $param){
			$paramtype = $paramtypes[$str_count++];
			if($paramtype=='s'){
				$param='"'.$this->active_conn->real_escape_string($param).'"';
			}
			$sql = $this->str_replace_once('?',$param,$sql);
		}
		return $sql;
	}

	/**
	 * Executes a SELECT statement on the database.
	 * Returns the resultset in array form.
	 *
	 * @param string $sql query to be prepared and executed
	 * @param string $paramtypes string of types mapped to $params
	 * @param array $params attributes to be bound to query
	 * @param array $columns name of columns needed to bind results
	 * @return array $resultset associated array of array(array(column => value,...),...)
	 */
	public function execute_get($object = '', $uid = 0, $sql = '', $paramtypes = null, $params=array(), $columns=array()){
		try {
			$resultset = array();
			$this->active_conn = &$this->determine_connection('get', $object, $uid);

			$sql = $this->prepare_fake($sql,$params,$paramtypes);

			$query = $this->active_conn->query($sql);

			$row_count=0;
			if ($query->num_rows > 0) {
				while($row = $query->fetch_row()) {
					$column_count=0;
					foreach($row as $column){
						$resultset[$row_count][$columns[$column_count++]] = $column;
					}
					$row_count++;
				}
			}

			if(!is_object($query)){
				error_log($sql);
			}else{
				$query->close();
			}

			$this->active_conn = null;

			return $resultset;
		} catch (SnapDatabaseException $e) {
			return $e->getMessage();
		}
	}

	/**
	 * this determines the parameters of a connection:
	 * what "name" of the connection type to be referenced later for username/password/server
	 * whether it's a slave or master query
	 *
	 * @param string $type the type of query: get, put, or drop
	 * @return array/boolean
	 */
	public function connection_details($type){
		$answer = array();
		if ($type == 'get') {
			$answer = 'master';
		} elseif ($type == 'put' || $type = 'truncate') {
			$answer = 'master';
		}else{
			$answer = FALSE;
		}
		return $answer;
	}

	/**
	 * Establish or use pre-existing db connection and execute SELECT
	 *
	 * @param string $type is query going to put or get
	 * @param array $object what type of connection we want
	 * @return mysqli $this->db[$conn] connection determined for user
	 */
	public function determine_connection($type = '', $object = '') {
		$conn = $this->connection_details($type);

		//if we dont have an established connection
		if (!array_key_exists($object, self::$dbs)) {
			$temporary_conn = new MySQLi(
				Config::$CONFIG[$object][$conn],
				Config::$CONFIG[$object]['user'],
				Config::$CONFIG[$object]['pass'],
				Config::$CONFIG[$object]['db_name']
			);
			self::$dbs[$object] = $temporary_conn;
		}

		if(mysqli_connect_error()){
			throw new SnapDatabaseException(mysqli_connect_error());
		}

		return self::$dbs[$object];
	}

	/**
	 * Removes all table names from an associative array
	 * @param array $row return set from database
	 * @return array $newRow column name => value
	 */
	public static function sanitizeTableName($row = array()) {
		$newRow = array();
		if (!empty($row)) {
			foreach ($row as $k=>$v) {
				$spot = strpos($k,'.');
				if ($spot !== FALSE) {
					$colname = substr($k,$spot+1);
					$newRow[$colname] = $v;
				} elseif ($k != 'password') {
					$newRow[$k] = $v;
				}
			}
		}
		return $newRow;
	}

	public static function sanitizeTableNames($set = array()) {
		$newSet = array();
		if (!empty($set)) {
			foreach ($set as $k=>$v) {
				$newSet[$k] = SnapDatabase::sanitizeTableName($v);
			}
		}
		return $newSet;
	}

	public static function killConnections(){
		foreach(self::$dbs as $conn_name => $conn){
			//assumes $conn is a mysqli connection
			$conn->close();
			unset(self::$dbs[$conn_name]);
		}
	}
}

class SnapDatabaseException extends Exception {};
