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
  CREATE TYPE measurement_type AS ENUM ('temperature', 'brightness');
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
  user_id integer REFERENCES Webuser (id) ON DELETE CASCADE NOT NULL,
  course_id integer REFERENCES Course (id) ON DELETE CASCADE NOT NULL,
  notes varchar(100),
  last_modification TIMESTAMP DEFAULT now(),
  CONSTRAINT u_constraint UNIQUE (user_id, course_id)
);

CREATE TABLE Enrollment (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id) ON DELETE CASCADE NOT NULL,
  student_id varchar(100) NOT NULL,
  last_modification TIMESTAMP DEFAULT now(),
  CONSTRAINT u_constraint_student UNIQUE (course_id, student_id)
);

CREATE TABLE Environment_Control_Setting (
  id serial PRIMARY KEY,
  type measurement_type NOT NULL,
  threshold real NOT NULL,
  control_method environment_control_method NOT NULL,
  control_value VARCHAR(20) NOT NULL,
  notes varchar(100),
  CONSTRAINT ec_u_constraint UNIQUE (type, control_method),
  last_modification TIMESTAMP DEFAULT now()
);

CREATE TABLE Question  (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id) ON DELETE CASCADE,
  question_text VARCHAR(100) NOT NULL,
  CONSTRAINT q_u_constraint UNIQUE (course_id, question_text),
  last_modification TIMESTAMP DEFAULT now()
);


CREATE TABLE Lecture (
  id serial PRIMARY KEY,
  course_id integer REFERENCES Course (id) ON DELETE CASCADE,
  title VARCHAR(100),
  status lecture_status NOT NULL DEFAULT 'in_progress',
  start_date TIMESTAMP,
  end_date TIMESTAMP
);

CREATE TABLE Feedback (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id) ON DELETE CASCADE,
  question_id integer REFERENCES Question (id) ON DELETE CASCADE,
  feedback integer NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Comment (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id) ON DELETE CASCADE,
  comment_text VARCHAR(300) NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Lecture_Code (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id) ON DELETE CASCADE,
  code VARCHAR(20) UNIQUE NOT NULL,
  student_id VARCHAR(100) REFERENCES Enrollment (student_id) ON DELETE CASCADE,
  timestamp TIMESTAMP DEFAULT now()
);

CREATE TABLE Attendance (
  id serial PRIMARY KEY,
  lecture_id integer REFERENCES Lecture (id) ON DELETE CASCADE,
  student_id VARCHAR(100) REFERENCES Enrollment (student_id) ON DELETE CASCADE,
  attended BOOLEAN NOT NULL DEFAULT FALSE,
  CONSTRAINT attendance_constraint UNIQUE (lecture_id, student_id)
);

CREATE TABLE Measurement (
  id serial PRIMARY KEY,
  type measurement_type NOT NULL,
  value real NOT NULL,
  lecture_id integer REFERENCES Lecture (id) ON DELETE CASCADE NOT NULL,
  timestamp TIMESTAMP DEFAULT now()
);

/* add sample users */
INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('dev@example.com', 'dev', 'Mr Developer', 'dev', '');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('user_admin1@example.com', 'pass', 'User Administrator 1', 'user_admin', '');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('user_admin2@example.com', 'pass', 'User Administrator 2', 'user_admin', 'some notes');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('course_admin1@example.com', 'pass', 'Course Administrator 1', 'course_admin', 'notes');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('course_admin2@example.com', 'pass', 'Course Administrator 2', 'course_admin', 'more notes');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('lecturer1@example.com', 'pass', 'Lecturer 1', 'lecturer', '');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('lecturer2@example.com', 'pass', 'Lecturer 2', 'lecturer', '');

INSERT INTO Webuser (email, password, full_name, user_type, notes)
VALUES ('lecturer3@example.com', 'pass', 'Lecturer 3', 'lecturer', '');


/* add sample courses */
INSERT INTO Course (course_code, title, notes)
VALUES ('C1', 'Course 1', 'some note');

INSERT INTO Course (course_code, title, notes)
VALUES ('C2', 'Course 2', '');

INSERT INTO Course (course_code, title, notes)
VALUES ('C3', 'Course 3', '');

INSERT INTO Course (course_code, title, notes)
VALUES ('C4', 'Course 4', '');

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
