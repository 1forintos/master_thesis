BEGIN;

DROP TABLE IF EXISTS Attendance CASCADE;
DROP TABLE IF EXISTS Lecture CASCADE;
DROP TABLE IF EXISTS Lecture_Code CASCADE;
DROP TABLE IF EXISTS Feedback CASCADE;
DROP TABLE IF EXISTS Question CASCADE;
DROP TABLE IF EXISTS Comment CASCADE;
DROP TABLE IF EXISTS Environment_Control_Setting CASCADE;
DROP TABLE IF EXISTS Measurement CASCADE;
DROP TABLE IF EXISTS Course CASCADE;
DROP TABLE IF EXISTS Enrollment CASCADE;
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
  IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'environment_control_method') THEN
    DROP TYPE environment_control_method;
  END IF;
  CREATE TYPE environment_control_method AS ENUM ('air_conditioner', 'artificial_lighting', 'heating', 'shutters');
END
$$;

DO $$
BEGIN
  IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'lecture_status') THEN
    DROP TYPE lecture_status;
  END IF;
  CREATE TYPE lecture_status AS ENUM ('before_start', 'in_progress', 'finished');
END
$$;

CREATE TABLE Webuser (
  id serial PRIMARY KEY,
  email varchar(100) UNIQUE NOT NULL,
  password varchar(100) NOT NULL,
  full_name varchar(100) NOT NULL,
  user_type user_type NOT NULL,
  notes varchar(100),
  last_modification TIMESTAMP DEFAULT now()
);

CREATE TABLE Course (
  id serial PRIMARY KEY,
  course_code varchar(50) UNIQUE NOT NULL,
  title varchar(100) NOT NULL,
  notes varchar(100),
  last_modification TIMESTAMP DEFAULT now()
);

CREATE TABLE Lecturer (
  id serial PRIMARY KEY,
  user_id integer REFERENCES Webuser (id) NOT NULL,
  course_id integer REFERENCES Course (id) NOT NULL,
  notes varchar(100),
  last_modification TIMESTAMP DEFAULT now(),
  CONSTRAINT u_constraint UNIQUE (user_id, course_id)
);

CREATE TABLE Enrollment (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id) NOT NULL,
  student_id varchar(100) UNIQUE NOT NULL,
  last_modification TIMESTAMP DEFAULT now()
);

CREATE TABLE Environment_Control_Setting (
  id serial PRIMARY KEY,
  type measurement_type NOT NULL,
  threshold real NOT NULL,
  control_method environment_control_method NOT NULL,
  control_value VARCHAR(20) NOT NULL,
  notes varchar(100) NOT NULL,
  CONSTRAINT ec_u_constraint UNIQUE (type, control_method),
  last_modification TIMESTAMP DEFAULT now()
);

CREATE TABLE Question  (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id),
  question_text VARCHAR(100) NOT NULL,
  CONSTRAINT q_u_constraint UNIQUE (course_id, question_text),
  last_modification TIMESTAMP DEFAULT now()
);

CREATE TABLE Feedback (
  id serial PRIMARY KEY,
  question_id integer REFERENCES Question (id),
  feedback integer NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);


CREATE TABLE Lecture (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id),
  title VARCHAR(100),
  status lecture_status NOT NULL DEFAULT 'in_progress',
  start_date TIMESTAMP,
  end_date TIMESTAMP
);

CREATE TABLE Comment (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id),
  comment VARCHAR(300) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Lecture_Code (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id),
  code VARCHAR(20) UNIQUE NOT NULL,
  student_id VARCHAR(100) REFERENCES Enrollment (student_id),
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Attendance (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id),
  student_id VARCHAR(100) REFERENCES Enrollment (student_id),
  attended BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE Measurement (
  id serial PRIMARY KEY,
  type measurement_type NOT NULL,
  value real NOT NULL,
  lecture_id integer REFERENCES Lecture (id) NOT NULL,
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


/* Enroll students to courses */
INSERT INTO Enrollment (course_id, student_id)
VALUES (1, 'H9S38H');

INSERT INTO Enrollment (course_id, student_id)
VALUES (1, 'GJ0RZR');

INSERT INTO Enrollment (course_id, student_id)
VALUES (2, 'YN4RIN');

INSERT INTO Enrollment (course_id, student_id)
VALUES (2, 'VJZX0K');

INSERT INTO Enrollment (course_id, student_id)
VALUES (2, '39GN8V');

INSERT INTO Enrollment (course_id, student_id)
VALUES (2, 'CFUXYO');

INSERT INTO Enrollment (course_id, student_id)
VALUES (4, 'HTIVON');

INSERT INTO Enrollment (course_id, student_id)
VALUES (4, 'LZ3I2O');

/* Questions for courses */
INSERT INTO Question (course_id, question_text)
VALUES (1, 'Question 1');

INSERT INTO Question (course_id, question_text)
VALUES (2, 'Question 2');

INSERT INTO Question (course_id, question_text)
VALUES (2, 'Question 3');

INSERT INTO Question (course_id, question_text)
VALUES (2, 'Question 4');

INSERT INTO Question (course_id, question_text)
VALUES (4, 'Question 5');

INSERT INTO Question (course_id, question_text)
VALUES (4, 'Question 6');

COMMIT;
