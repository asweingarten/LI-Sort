DROP DATABASE hackathon;
CREATE DATABASE hackathon;

USE hackathon;

CREATE TABLE people (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   name VARCHAR(30) NOT NULL
);

CREATE TABLE skills (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   name VARCHAR(30) NOT NULL
);

CREATE TABLE skill_map (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   fk_person_id INTEGER NOT NULL,
	   fk_skill_id INTEGER NOT NULL,
	   FOREIGN KEY (fk_person_id) REFERENCES people(_id),
	   FOREIGN KEY (fk_skill_id) REFERENCES skills(_id)
);

-- TODO 
CREATE TABLE projects (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   title VARCHAR(255) NOT NULL,
	   fk_creator_id INTEGER NOT NULL,
	   FOREIGN KEY (fk_creator_id) REFERENCES people(_id)
);

CREATE TABLE comments (
	   _id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   fk_project_id INTEGER NOT NULL,
	   fk_commenter_id INTEGER NOT NULL,
	   comment VARCHAR(512) NOT NULL,
	   FOREIGN KEY (fk_project_id) REFERENCES projects(_id),
	   FOREIGN KEY (fk_commenter_id) REFERENCES people(_id)
);
