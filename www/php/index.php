<?php
/*
 * Person Object:
 * {
 *		id,
 *		name,
 *		skills,
 *		projects,
 * }
 *
 * Project Object:
 * {
 * 		id,
 *		title,
 *		description,
 *      creator_id,
 *		comments,
 *		people,
 * }
 * Comment Object:
 * {
 *		id,
 *		projectId,
 *		commenterId,
 *		text,
 * }
 *
 * URL Endpoints:
 * 	Get:
 *  	/login?code=auth_code&state=secret_state    - Gets the access token for the user with the provided auth code
 *		/person 									- Get all people
 *		/person/:id 								- Gets a person with ID id
 *		/person?skills[]=skill1&skills[]=skill2... 	- Gets people that have all of the provided skills
 *		/project 									- Gets all projects
 *		/project/:id 								- Gets project with ID id
 *		/project?skills[]=skill1&skills[]=skill2...	- Gets projects that have all of the provided skills
 *		/comment/:id								- Get comments with ID id
 */

require("config.php");
require("db_utils.php");
require($_SERVER['DOCUMENT_ROOT'] . "/../lib/Slim/Slim.php");

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

function get_access_token($auth_code, $config) {
	$api_key = $config['api_key'];
	$state 	= $config['state'];
	$domain = $config['server'];
	$secret = $config['secret'];

	$url = "https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&code=$auth_code&redirect_uri=http://$domain/php/login&client_id=$api_key&client_secret=$secret";
	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	    ),
	);
	$context = stream_context_create($options);

	$json = json_decode(file_get_contents($url, false, $context));
	return $json->access_token;
}

function create_person($con, $token) {
	$url = "https://api.linkedin.com/v1/people/~?oauth2_access_token=$token";
	$xml = file_get_contents($url);
	$person = new SimpleXMLElement($xml);

	$person = (Array) $person;
	$name = $person["first-name"] . ' ' . $person["last-name"];

	return insert($con, "people", array("name" => $name), true);
}

function insert_skills($con, $person_id, $token) {
	$url = "https://api.linkedin.com/v1/people/~:(skills)?oauth2_access_token=$token";
	$xml = file_get_contents($url);
	$person = new SimpleXMLElement($xml);

	foreach ($person->skills->skill as $skill) {
		$temp = (Array) $skill->skill;
		$name = $temp["name"];

		$skill_id = insert($con, "skills", array("name" => $name), true);
		if ($skill_id != 0) {
			insert($con, "person_skill_map", array("fk_person_id" => $person_id, "fk_skill_id" => $skill_id), true);
		}
	}
}

$app->get('/login', function() use ($app, $config) {
	$con = connect();

	$code = $app->request()->params('code');
	$state = $app->request()->params('state');

	assert($code != NULL, "Code can not be null");
	assert($state != NULL, "State can not be null");
	assert($state == $config['state'], "State does not match");

	$token = get_access_token($code, $config);
	$person_id = create_person($con, $token);
	if ($person_id != 0) {
		insert_skills($con, $person_id, $token);
	}

	mysqli_close($con);
});

function get_person_json($con, $personRow) {
	$skillResult = select($con, "person_skill_map", array('fk_skill_id'), array('fk_person_id' => $personRow['_id']));
	$skills = array();
	while ($skillRow = $skillResult->fetch_assoc()) {
			$skills[] = $skillRow['fk_skill_id'];
	}

	$projectResult = select($con, "person_project_map", array('fk_project_id'), array('fk_person_id' => $personRow['_id']));
	$projects = array();
	while ($projectRow = $projectResult->fetch_assoc()) {
		$projects[] = $projectRow['fk_project_id'];
	}
	return array('id' => $personRow['_id'], 'name' => $personRow['name'], 'skills' => $skills, 'projects' => $projects);
}

$app->get('/person', function() {
	$con = connect();

	$people = array();
	$result = select($con, 'people', array('_id', 'name'));
	while ($personRow = $result->fetch_assoc()) {
		$people[] = get_person_json($con, $personRow);
	}

	echo json_encode($people);

	mysqli_close($con);
});

$app->get('/person/:id', function($id) {
	$con = connect();

	$result = select($con, 'people', array('_id', 'name'), array('_id'=>$id));
	assert($result->num_rows == 1);
	$personRow = $result->fetch_assoc();
	$person = get_person_json($con, $personRow);

	echo json_encode($person);
	
	mysqli_close($con);
});

$app->get('/project', function() {
	$con = connect();

	$result = select($con, 'projects', array('name'));
	var_dump($result);

	mysqli_close($con);
});

$app->get('/project/:id', function($id) {
	$con = connect();

	$result = select($con, 'projects', array('name'), array('_id'=>$id));
	var_dump($result);

	mysqli_close($con);
});

$app->get('/comment', function() {
	$con = connect();

	//$result = select($con, 'comments', array('comment'), where(?????));
	//var_dump($result);

	mysqli_close($con);
});

$app->run();

?>
