BEGIN;

DROP TABLE IF EXISTS Environment_Control_Setting CASCADE;
DROP TABLE IF EXISTS Question_Of_Form CASCADE;
DROP TABLE IF EXISTS Answer_Option CASCADE;
DROP TABLE IF EXISTS Question CASCADE;
DROP TABLE IF EXISTS Form CASCADE;
DROP TABLE IF EXISTS Measurement CASCADE;
DROP TABLE IF EXISTS Room CASCADE;
DROP TABLE IF EXISTS Lecture CASCADE;
DROP TABLE IF EXISTS Course CASCADE;
DROP TABLE IF EXISTS Student CASCADE;
DROP TABLE IF EXISTS Lecturer CASCADE;
DROP TABLE IF EXISTS Webuser CASCADE;

DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_type') THEN
    DROP TYPE user_type;
  END IF;
  CREATE TYPE user_type AS ENUM ('dev', 'user_admin', 'course_admin', 'lecturer');
END
$$;

DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'measurement_type') THEN
    DROP TYPE measurement_type;
  END IF;
  CREATE TYPE measurement_type AS ENUM ('temperature', 'light');
END
$$;

DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'question_type') THEN
    DROP TYPE question_type;
  END IF;
  CREATE TYPE question_type AS ENUM ('single_select', 'textual');
END
$$;

DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'environment_control_method') THEN
    DROP TYPE environment_control_method;
  END IF;
  CREATE TYPE environment_control_method AS ENUM ('single_select', 'textual');
END
$$;

CREATE TABLE Webuser (
  id serial PRIMARY KEY,
  email varchar(100) UNIQUE NOT NULL,
  password varchar(100) NOT NULL,
  full_name varchar(100) NOT NULL,
  user_type user_type NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Course (
  id serial PRIMARY KEY,
  course_code varchar(50) UNIQUE NOT NULL,
  title varchar(100) NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Lecturer (
  id serial PRIMARY KEY,
  user_id integer REFERENCES Webuser (id) NOT NULL,
  course_id integer REFERENCES Course (id) NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now(),
  CONSTRAINT u_constraint UNIQUE (user_id, course_id)
);

CREATE TABLE Student (
  id serial PRIMARY KEY,
  name varchar(100) NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Room (
  id serial PRIMARY KEY,
  name varchar(100) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Lecture (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id) NOT NULL,
  lecture_date TIMESTAMP NOT NULL,
  rooom_id integer REFERENCES Room (id) NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Measurement (
  id serial PRIMARY KEY,
  type measurement_type NOT NULL,
  room_id integer REFERENCES Room (id) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Form (
  id serial PRIMARY KEY,
  title varchar(100) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Question (
  id serial PRIMARY KEY,
  text varchar(200) UNIQUE NOT NULL,
  type question_type NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Answer_Option (
  id serial PRIMARY KEY,
  question_id integer REFERENCES Question (id) NOT NULL,
  text varchar(50) NOT NULL,
  position integer NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Question_Of_Form (
  id serial PRIMARY KEY,
  form_id integer REFERENCES Form (id) NOT NULL,
  question_id integer REFERENCES Question (id) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Environment_Control_Setting (
  id serial PRIMARY KEY,
  threshold real NOT NULL,
  control_method environment_control_method NOT NULL,
  notes varchar(100) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);


/* add sample users */

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('dev@example.com', 'dev', 'Mr Developer', 'dev', 'notes 1');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('user_admin1@example.com', 'pass', 'Mr User Admin 1', 'user_admin', 'notes 2');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('user_admin2@example.com', 'pass', 'Mr User Admin 2', 'user_admin', 'notes3');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('course_admin1@example.com', 'pass', 'Mr Course Admin 1', 'course_admin', 'notes 4');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('course_admin2@example.com', 'pass', 'Mr Course Admin 2', 'course_admin', 'notes 5');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('lecturer1@example.com', 'pass', 'Prof Lecturer 1', 'lecturer', 'really clever');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('lecturer2@example.com', 'pass', 'Prof Lecturer 2', 'lecturer', 'really clever 2');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('lecturer3@example.com', 'pass', 'Prof Lecturer 3', 'lecturer', 'really clever 3');


/* add sample courses */

INSERT INTO Course (course_code, title, notes)
VALUES ('C1', 'Course Title 1', 'decent course');

INSERT INTO Course (course_code, title, notes)
VALUES ('C2', 'Course Title 2', 'notes 2');

INSERT INTO Course (course_code, title, notes)
VALUES ('C3', 'Course Title 3', '');

INSERT INTO Course (course_code, title, notes)
VALUES ('C4', 'Course Title 4', 'course notes 4');

/* assign lecturers to courses */
INSERT INTO Lecturer (user_id, course_id)
VALUES (6, 1);

INSERT INTO Lecturer (user_id, course_id)
VALUES (6, 2);

INSERT INTO Lecturer (user_id, course_id)
VALUES (8, 4);

INSERT INTO Lecturer (user_id, course_id)
VALUES (8, 3);

INSERT INTO Lecturer (user_id, course_id)
VALUES (6, 4);

COMMIT;
