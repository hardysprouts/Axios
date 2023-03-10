<?php
	date_default_timezone_set("America/Mexico_City");
	$_POST=str_replace(chr(0x0A),'<br>',$_POST);
	$_POST=str_replace('
		','<br>',$_POST);
	$_POST=str_replace('\'','&#39;',$_POST);
	$_GET=str_replace(chr(0x0A),'<br>',$_GET);
	$_GET=str_replace('
		','<br>',$_GET);
	$_GET=str_replace('\'','&#39;',$_GET);
	$json = file_get_contents('server_access.json');
	$server_configuracion = json_decode($json);
	$conections = [];
	session_start();
	foreach ($server_configuracion as $key => $val) {
		$conection = mysqli_init();
		mysqli_options($conection,MYSQLI_READ_DEFAULT_GROUP,"wait_timeout=60");
		mysqli_options($conection,MYSQLI_READ_DEFAULT_GROUP,"interactive_timeout=120");
		mysqli_real_connect($conection, $val->server, $val->user, $val->password, $val->data_base) or die('<P>No se puede contectar</P>');
		mysqli_set_charset($conection,"latin1");
		$result = mysqli_query($conection,"SET collation_connection='latin1_spanish_ci';");
		if (!$result) {
			printf("<br />f2 Error: %s\n", mysqli_error($conection));
			exit();
		}
		$conections[$key] = $conection;
	}
	function end_conections(){
		global $conections;
		foreach ($conections as $key => $value) {
			mysqli_commit($value);
			mysqli_close($value);
		}
	};
?>