/*
 * Schema Summary:
 * - Basic objects are people, skills, and projects
 * - People have skills (many-to-many) and a creation timestamp
 * - Projects have people (many-to-many), comments (one-to-many), and a creation timestamp
 * - Comments have a creator (one-to-one), a project (one-to-one), and a creation timestamp
 *
 * Arbitrary data limits:
 * - Person Names: 30 chars
 * - Skill Names:  30 chars
 * - Project Title: 255 chars
 * - Project Description: 8192 chars
 * - Comments: 30 chars
 */

DROP DATABASE hackathon;
CREATE DATABASE hackathon;

USE hackathon;

CREATE TABLE people (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   name VARCHAR(30) NOT NULL,
	   created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	   UNIQUE(name)
);

CREATE TABLE skills (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   name VARCHAR(30) NOT NULL,
	   UNIQUE(name)
);

CREATE TABLE person_skill_map (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   fk_person_id INTEGER NOT NULL,
	   fk_skill_id INTEGER NOT NULL,
	   FOREIGN KEY (fk_person_id) REFERENCES people(_id),
	   FOREIGN KEY (fk_skill_id) REFERENCES skills(_id)
);

CREATE TABLE projects (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   title VARCHAR(255) NOT NULL,
	   description VARCHAR(8192) NOT NULL,
	   fk_creator_id INTEGER NOT NULL,
	   created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	   FOREIGN KEY (fk_creator_id) REFERENCES people(_id)
);

CREATE TABLE person_project_map (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   fk_person_id INTEGER NOT NULL,
	   fk_project_id INTEGER NOT NULL,
	   FOREIGN KEY (fk_person_id) REFERENCES people(_id),
	   FOREIGN KEY (fk_project_id) REFERENCES projects(_id)
);

CREATE TABLE comments (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   fk_project_id INTEGER NOT NULL,
	   fk_commenter_id INTEGER NOT NULL,
	   comment VARCHAR(512) NOT NULL,
	   created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	   FOREIGN KEY (fk_project_id) REFERENCES projects(_id),
	   FOREIGN KEY (fk_commenter_id) REFERENCES people(_id)
);
