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
 *	Post:
 *		/project 									- Adds a new project
 *		/project/:projectId/person/:personId		- Associates the person with personId to project with projectId
 *		/comment 									- Adds a comment
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

function get_project_json($con, $projectRow) {
	$peopleResult = select($con, "person_project_map", array('fk_person_id'), array('fk_project_id' => $projectRow['_id']));
	$people = array();
	while ($peopleRow = $peopleResult->fetch_assoc()) {
		$people[] = $peopleRow['fk_person_id'];
	}
	
	$commentResult = select($con, "comments", array('_id'), array('fk_project_id' => $projectRow['_id']));
	$comments = array();
	while ($commentRow = $commentResult->fetch_assoc()) {
		$comments[] = $commentRow['_id'];
	}

	return array('id' => $projectRow['_id'], 'title' => $projectRow['title'], 'creatorId' => $projectRow['fk_creator_id'],
		'description' => $projectRow['description'], 'comments' => $comments, 'people' => $people);
}

$app->get('/project', function() {
	$con = connect();

	$projects = array();
	$result = select($con, 'projects', array('*'));
	while ($projectRow = $result->fetch_assoc()) {
		$projects[] = get_project_json($con, $projectRow);
	}
	
	echo json_encode($projects);

	mysqli_close($con);
});

$app->get('/project/:id', function($id) {
	$con = connect();

	$result = select($con, 'projects', array('name'), array('_id'=>$id));
	assert($result->num_rows == 1);
	$projectRow = $result->fetch_assoc();
	$project = get_project_json($con, $projectRow);

	echo json_encode($project);

	mysqli_close($con);
});

$app->get('/comment/:id', function($id) {
	$con = connect();

	$result = select($con, 'comments', array('*'), array('_id' => $id));
	assert($result->num_rows == 1);
	$commentRow = $result->fetch_assoc();
	$comment = array('id' => $commentRow['_id'], 'projectId' => $commentRow['fk_project_id'], 'commenterId' => $commentRow['fk_commenter_id'], 'text' => $commentRow['comment']);
	echo json_encode($comment);

	mysqli_close($con);
});

$app->post('/project', function() use ($app) {
	$title = $app->request()->post('title');
	$description = $app->request()->post('description');
	$creator_id = $app->request()->post('creator_id');

	$con = connect();

	$project_id = insert($con, "projects", array('title' => $title, 'description' => $description, 'fk_creator_id' => $creator_id), true);
	insert($con, "person_project_map", array('fk_person_id' => $creator_id, 'fk_project_id' => $project_id));

	mysqli_close($con);
});

$app->post('/project/:projectId/person/:personId', function($projectId, $personId) use ($app) {
	$con = connect();

	insert($con, "person_project_map", array('fk_person_id' => $personId, 'fk_project_id' => $projectId));

	mysqli_close($con);
});

$app->post('/comment/:projectId', function($projectId) use ($app) {
	$comment = $app->request()->post('comment');
	$commenter_id = $app->request()->post('commenter_id');

	$con = connect();
	
	insert($con, "comments", array('fk_project_id' => $projectId, 'fk_commenter_id' => $commenter_id, 'comment' => $comment));

	mysqli_close($con);
});

$app->run();

