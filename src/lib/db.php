<?php

class DB {

	public static $CONNECTION = null;

	public static function save(array $reqProducts = null, array $reqPaypalResult = null)
	{

		self::sql('INSERT INTO pp_purchases(products, paypal_result) VALUES (?, ?)', [
			json_encode($products),
			json_encode($reqPaypalResult)
		]);
		
	}
	

	public static function quote(string $string) { return '"' . mysqli_real_escape_string(self::$CONNECTION, $string) . '"'; }


	public static function logError(string $msg, $data = null)
	{
        if (LOG_ERROR_IN_DB == false) return;
		self::sql('INSERT INTO jr_log(type, msg, data) VALUES ("error", ?, ?)', [ $msg, json_encode($data) ], true);
	}

	public static function logInfo(string $msg, $data = null)
	{
        if (LOG_INFO_IN_DB == false) return;
		self::sql('INSERT INTO jr_log(type, msg, data) VALUES ("info", ?, ?)', [ $msg, json_encode($data) ], true);
	}


	public static function sql(string $sql, array $bindings = [], bool $isLog = false){

		// Apply bindings

		if(count($bindings) > 0 && substr_count($sql, '?') === count($bindings))
		{
			$parts = explode("?", $sql);
			$sql = '';
			foreach($parts as $i => &$part)
			{
				$sql .= $part;
				if ($i < count($bindings)) $sql .= self::quote($bindings[$i]);
			}
		}


		// Run SQL
		$queryResult = \mysqli_query(self::$CONNECTION, $sql);

        if ($queryResult === false) 
		{
            $error = mysqli_error(self::$CONNECTION);
			if ($isLog == false) self::logError("SQL error on request: " . $sql);
            
            error($error);
		}

		if (is_bool($queryResult)) return $queryResult;

		$res = mysqli_fetch_all($queryResult, MYSQLI_ASSOC);

		if (is_array($res) == false) return [ $res ];

		return $res;
	}


	public static function initDB()
	{
        // Create connection
        self::$CONNECTION = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
        mysqli_select_db(self::$CONNECTION, DB_DATABASE);

        DB_SCHEMAS::migrate();
	}
}
