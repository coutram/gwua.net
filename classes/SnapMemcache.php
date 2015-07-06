<?php
/**
 * Snap memcache enhances memcache with user specific keys
 * @author Christopher Outram templated from code on Lone Ranger 01/12/2010
 * @name SnapMemcache
 */
class SnapMemcache{
	private $mc_debug = FALSE;
	private $mc_debug_status = FALSE;
	private static $memcache;

	public function __construct($master_pool = 'memcache_server') {
		if(self::$memcache==NULL){
			self::$memcache = new Memcache();
			@self::$memcache->addServer(Config::$CONFIG[$master_pool],11211,false);
		}
	}

	/**
	 *
	 * @param string/array $key the key or array of keys to fetch
	 * @param int $flags fetched along with values
	 * @return string/array associated with key(s) or FALSE if failure or no key found
	 */
	public function get($key = '', $flags = null) {
		$key_is_array = is_array($key);
		$res_placeholder = array();

		if ($key_is_array) {
			foreach ($key as $k=>$v) {
				$key[$k] = $v;
				$res_placeholder[$v] = false;
			}
		}

		if($this->mc_debug) { echo 'GET '.$key . '<br/>'; }

		$result = false;
		$result = self::$memcache->get($key, $flags);

		return $result;
	}

	/**
	 * appends uid to key and saves item $var with $key on memcache server
	 *
	 * @param string $key the key to save $var to
	 * @param mixed $var variable to store
	 * @param int $flags flags for storing data
	 * @param int $expire expiration time of the item
	 * @return boolean TRUE or FALSE depending on if data was successfully stored
	 */
	public function set($key, $var, $flag = null, $expire = 0) {
		if($this->mc_debug){ echo 'SET ' . $key . '<br/>'; }

		//TODO: DEBUGGING
		//		$d_b = ExternalConnLog::debug();
		//		ExternalConnLog::$MEMCACHE["SET"][] = "{$key}";
		//		ExternalConnLog::$MEMCACHE_ORDER[] = "SET: {$key} \t\t\t {$d_b}";

		return self::$memcache->set($key,$var,$flag,$expire);
	}

	/**
	 * appends uid to key deletes deletes item from server
	 *
	 * @param string/array $key the key or array of keys to fetch
	 * @param int $flags fetched along with values
	 * @return boolean TRUE or FALSE depending on if data was successfully deleted
	 */
	public function delete($key,$timeout = null) {
		if($this->mc_debug){ echo 'DELETE '.$key . '<br/>'; }

		//TODO: DEBUGGING
		//		$d_b = ExternalConnLog::debug();
		//		ExternalConnLog::$MEMCACHE["DELETE"][] = "{$key}";
		//		ExternalConnLog::$MEMCACHE_ORDER[] = "DELETE: {$key} \t\t\t {$d_b}";

		return self::$memcache->delete($key, $timeout);
	}

	public function increment($key, $value = 1) {
		if($this->mc_debug){ echo 'INCREMENT '.$key . '<br/>'; }
		return self::$memcache->increment($key, $value);
	}

	public static function killConnections(){
		if(is_a(self::$memcache, 'Memcache')){
			self::$memcache->close();
		}
	}

	public function getExtendedStats($type=null){
		$status = array();
		$status['MASTER'] = self::$memcache->getExtendedStats($type);
		return $status;
	}

	public function getMemUsage($pool='MASTER',$sort_col='item_size',$sort = true,$sort_asc=false){
		$stats = $this->getExtendedStats('items');
		$stats3 = $this->getExtendedStats('slabs');

		//build stats
		foreach($stats3[$pool] as $key=>$values){
			if(is_array($values)){
				foreach($values as $key2=>$values2){
					if(is_int($key2)){
						$key_2 = $values2['chunk_size'];
						$stats2[$key][$key_2]['oldest_item'] = PageHelper::setDefault($stats[$pool][$key]['items'][$key2]['age'],0,'INT');
						$stats2[$key][$key_2]['item_size'] = $values2['chunk_size'];
						$stats2[$key][$key_2]['items'] = $values2['total_chunks'];
						$stats2[$key][$key_2]['megs_allocated'] = $values2['total_pages'];
						$stats2[$key][$key_2]['server'] = $key;
					}
				}
			}
		}

		if($sort){
			$sort_asc = $sort_asc ? 'true':'false';
			$sorter = create_function('$a,$b','return PageHelper::arrayColSort($a,$b,"'.$sort_col.'",'.$sort_asc.');');
			foreach($stats2 as $table_name=>&$table){
				uasort($table,$sorter);
			}
		}

		return $stats2;
	}

	/**
	 * returns how much memory a piece of data will take up in memcache
	 *
	 * @param mixed $data
	 * @return int
	 */
	public function getSlabSize($data){
		// http://dev.mysql.com/doc/refman/5.0/en/ha-memcached-using-memory.html
		$sdata = serialize($data);
		$datasize = strlen($sdata)+48;
		return $datasize;
	}

	/**
	 * Returns information pertaining to memory usage for something of that size
	 *
	 * @param int $datasize
	 * @param string $pool
	 * @return array
	 */
	public function getSlabMemInfo($datasize, $pool = 'MASTER'){
		//get the stats from each server
		$stats = $this->getMemUsage($pool,'item_size');

		$answer = array();
		foreach ($stats as $server => $chunk_sizes){

			$answer[$server]['oldest_item'] = 0;
			$answer[$server]['chunk_size'] = 0;
			$answer[$server]['megs_slab'] = 0;
			$answer[$server]['megs_total'] = 0;
			$answer[$server]['megs_percent'] = 0;

			foreach($chunk_sizes as $chunk_size=>$values){

				if($datasize < $chunk_size){
					$answer[$server]['chunk_size'] = $chunk_size;
					$answer[$server]['megs_slab'] = $values['megs_allocated'];
					$answer[$server]['oldest_item'] = $values['oldest_item'];
				}
				$answer[$server]['megs_total'] += $values['megs_allocated'];
			}

			$answer[$server]['megs_percent'] = round(($answer[$server]['megs_slab'] / $answer[$server]['megs_total'] * 100),2);

		}
		return $answer;
	}
}