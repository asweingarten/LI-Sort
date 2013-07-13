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

function get_skills($token) {
}

$app->get('/login', function() use ($app, $config) {
	$code = $app->request()->params('code');
	$state = $app->request()->params('state');

	assert($code != NULL, "Code can not be null");
	assert($state != NULL, "State can not be null");
	assert($state == $config['state'], "State does not match");

	$token = get_access_token($code, $config);
	get_skills($token);
	echo $token;
});

$app->get('/person', function() {
});

$app->get('/person/:id', function() {
});

$app->get('/project', function() {
});

$app->get('/project/:id', function() {
});

$app->get('/comment', function() {
});

$app->run();

?>
