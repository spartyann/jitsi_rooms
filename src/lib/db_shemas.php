<?php

class DB_SCHEMAS {

	public static function migrate()
	{
		$endMig = 2;
		$startMig = null;

		$tables = DB::sql("SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM  information_schema.TABLES WHERE TABLE_SCHEMA = '"
			. DB_DATABASE . "'");
		
		// Search migration table
		$tableMigrationExists = false;

		foreach($tables ?? [] as $table)
		{
			if ($table['TABLE_NAME'] == 'jr_migrations') { $tableMigrationExists = true; break; }
		}

		// 
		if ($tableMigrationExists == false)
		{
			$startMig = 1;
		}
		else
		{
			$maxMig = 0;
			$migrations = DB::sql("SELECT * FROM jr_migrations");
			foreach($migrations as $mig) $maxMig = max($maxMig, $mig['id']);
			
			if ($maxMig < $endMig) $startMig = $maxMig + 1;
		}
		

		if ($startMig === null) return;

		// Run Migrations
		for($i = $startMig; $i <= $endMig; $i++) self::runMigration($i);
		
	}

	private static function runMigration($i)
	{
		if ($i == 1)
		{
			DB::sql("CREATE TABLE jr_migrations (
				id int(11) NOT NULL AUTO_INCREMENT,
				name varchar(30) NOT NULL,
				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

			DB::sql("CREATE TABLE jr_log (
				id int(11) NOT NULL AUTO_INCREMENT,
				date datetime NOT NULL DEFAULT now(),
				type varchar(30) NOT NULL,
				msg text NOT NULL,
				data mediumtext NOT NULL,
				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

			DB::sql('INSERT INTO jr_migrations(id, name) VALUES (1, "init Mig and Log")');
		}

		if ($i == 2)
		{
			DB::sql("CREATE TABLE jr_confs (
				code varchar(150) NOT NULL,
				created datetime NOT NULL DEFAULT now(),
				last_request datetime NOT NULL DEFAULT now(),
				admin_password varchar(100) NOT NULL,
				params text NOT NULL,
				PRIMARY KEY (code),
				INDEX idx_conf_cretaed(created),
				INDEX idx_conf_lr(last_request)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

			DB::sql("CREATE TABLE jr_attendees (
				id int(11) NOT NULL AUTO_INCREMENT,
				conf_code varchar(150) NOT NULL,
				last_request datetime NOT NULL DEFAULT now(),
				name varchar(150) NOT NULL,

				PRIMARY KEY (id),
				INDEX idx_attendees_lr(last_request),
				INDEX idx_attendees_cc(conf_code),
				CONSTRAINT `fk_attendees_conf` FOREIGN KEY (conf_code) REFERENCES jr_confs(code) ON DELETE CASCADE ON UPDATE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");


			DB::sql('INSERT INTO jr_migrations(id, name) VALUES (2, "init room models")');
		}

	}
}
