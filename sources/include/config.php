<?PHP
if (!defined( "_CONFIG" ) || $forceUpdate == 1 )
	{

		if (!defined( "_CONFIG" )) define("_CONFIG", 1);


		$db_type = "mysql";
		$hostname = "localhost";
		$login = "root";
		$pass = "";
		$database = "phpMyNewsletter";
		$table_global_config="pmnl_config";
		$pmnl_version ="0.8.6";

	}

?>