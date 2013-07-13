<?php

function connect() {
	$con=mysqli_connect("localhost","root","","hackathon");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "\nFailed to connect to MySQL: " . mysqli_connect_error();
	} else {
		return $con;
	}
}

function insert($con, $table, $input) {
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
	mysqli_query($con, "INSERT INTO $table ($keys) VALUES ($values)");
	error_check($con, "error inserting");
}

function select($con, $table, $input, $where = array()) {
	$inputs = "";
	foreach($input as $key) {
		$inputs .= "$key,";
	}
	$inputs = substr($inputs, 0, -1);
	if (empty($where)) {
		$result = mysqli_query($con,"SELECT $inputs FROM $table");
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
	$request = "";
	foreach ($input as $key => $value) {
		if (is_string($value)){
			$request .= "$key='$value',";
		} else {
			$request .= "$key=$value,";
		}
	}
	$request = substr($request, 0, -1);

	mysqli_query($con,"DELETE FROM $table WHERE $request");
	error_check($con, "error removing");
}

function error_check($con, $message) {
	if (mysqli_errno($con)) {
		echo "\n$message:" . mysqli_error($con);
	}
}

function test() {
	$con = connect();
	remove($con, "people", array("name" => "utkarsh"));
	mysqli_close($con);
}

?>
