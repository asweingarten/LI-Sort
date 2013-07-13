<?php

require("config.php");

function connect() {
	global $config;
	$con = mysqli_connect($config['server'], $config['db_user'], $config['db_password'], $config['database']);

	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	} else {
		return $con;
	}
}

function insert($con, $table, $input, $ignore_dups = false) {
	assert(is_array($input), "Input must be an array");
	
	$keys = "";
	$values = "";
	foreach ($input as $key => $value) {
		$keys .= " $key,";
		if (is_string($value)){
			$values .= "'$value',";
		} else {
			$values .= "$value,";
		}
	}
	$keys = substr($keys, 0, -1);
	$values = substr($values, 0, -1);

	if ($ignore_dups) {
		mysqli_query($con, "INSERT INTO $table ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE _id=_id");
		echo "INSERT INTO $table ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE _id=_id";
	} else {
		mysqli_query($con, "INSERT INTO $table ($keys) VALUES ($values)");
	}
	error_check($con, "error inserting");

	$ret_val = mysqli_insert_id($con);
	if (!$ignore_dups) {
		assert($ret_val != 0);
	}
	if ($ret_val == 0) {
		$return = select($con, $table, array('_id'), $input);
		$retRow = $return->fetch_assoc();
		$ret_val = $retRow['_id'];
	}
	return $ret_val;
}

function select($con, $table, $input, $where = array()) {
	assert(is_array($input), "Input must be an array");
	assert(is_array($where), "Where must be an array");

	$inputs = "";
	foreach($input as $key) {
		$inputs .= "$key,";
	}
	$inputs = substr($inputs, 0, -1);
	
	if (empty($where)) {
		$result = mysqli_query($con, "SELECT $inputs FROM $table");
	} else {
		$request = "";
   		foreach ($where as $key => $value) {
        	if (is_string($value)){
            	$request .= "$key='$value',";
        	} else {
            	$request .= "$key=$value,";
        	}
    	}
    	$request = substr($request, 0, -1);
		$result = mysqli_query($con, "SELECT $inputs FROM $table WHERE $request");
	}
	error_check($con, "error selecting");

	return $result;
}

function remove($con, $table, $input) {
	assert(is_array($input), "Input must be an array");

	$request = "";
	foreach ($input as $key => $value) {
		if (is_string($value)) {
			$request .= "$key='$value',";
		} else {
			$request .= "$key=$value,";
		}
	}
	$request = substr($request, 0, -1);

	mysqli_query($con, "DELETE FROM $table WHERE $request");
	error_check($con, "error removing");
}

function error_check($con, $message) {
	if (mysqli_errno($con)) {
		die("$message: " . mysqli_error($con));
	}
}

function test() {
	$con = connect();
	remove($con, "people", array("name" => "utkarsh"));
	mysqli_close($con);
}

?>
