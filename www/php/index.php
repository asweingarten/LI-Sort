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
 *
 * Comment Object:
 * {
 *		id,
 *		projectId,
 *		commenterId,
 *		text,
 * }
 *
 * URL Endpoints:
 *  /login?code=auth_code&state=secret_state    - Gets the access token for the user with the provided auth code
 *	/person 									- Get all people
 *	/person/:id 								- Gets a person with ID id
 *	/person?skills[]=skill1&skills[]=skill2... 	- Gets people that have all of the provided skills
 *	/project 									- Gets all projects
 *	/project/:id 								- Gets project with ID id
 *	/project?skills[]=skill1&skills[]=skill2...	- Gets projects that have all of the provided skills
 *	/comment/:id								- Get comments with ID id
 */
?>