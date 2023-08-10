<?php

class Data {

	public static function refreshConf(string $code)
	{
		DB::sql('UPDATE jr_confs SET last_request = now() WHERE code = ?', [ $code]);
	}

	public static function getConf(string $code)
	{
		$res = DB::sql('SELECT * FROM jr_confs WHERE code = ?', [ $code]);

		if (count($res) == 0) return null;

		$conf = $res[0];
		$conf['params'] = json_decode($conf['params'], true);

		return $conf;
	}

	public static function createOrUpdateConf(string $code, string $adminPassword = null, array $params = null)
	{
		if ($params === null) $params= [
			'rooms' => []
		];
		
		if ($adminPassword === null) $adminPassword = '';

		DB::sql('INSERT INTO jr_confs(code, admin_password, params) VALUES (?,?,?)
			ON DUPLICATE KEY UPDATE last_request = now(), admin_password = ?, params = ? ', [ $code, $adminPassword, json_encode($params), $adminPassword, json_encode($params)]);
		return self::getConf($code);
	}

	public static function getOrCreateConf(string $code, string $adminPassword = null, array $params = null)
	{
		$conf = self::getConf($code);
		if ($conf == null) $conf = self::createOrUpdateConf($code, $adminPassword, $params);

		return $conf;
	}
}
