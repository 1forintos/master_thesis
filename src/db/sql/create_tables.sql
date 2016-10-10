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
DROP TABLE IF EXISTS Users CASCADE;

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'task_status') THEN
        CREATE TYPE task_status AS ENUM ('todo', 'doing', 'blocked', 'done');
    END IF;
END
$$;

DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'measurement_type') THEN
    CREATE TYPE measurement_type AS ENUM ('temperature', 'light');
  END IF;
END
$$;

DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'question_type') THEN
    CREATE TYPE question_type AS ENUM ('single_select', 'textual');
  END IF;
END
$$;

DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'environment_control_method') THEN
    CREATE TYPE environment_control_method AS ENUM ('single_select', 'textual');
  END IF;
END
$$;

CREATE TABLE Users (
  id serial PRIMARY KEY,
  email varchar(100) UNIQUE NOT NULL,
  full_name varchar(100) NOT NULL,
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
  user_id integer REFERENCES Users (id) NOT NULL,
  course_id integer REFERENCES Course (id) NOT NULL,
  notes varchar(100),
  timestamp TIMESTAMP DEFAULT now()
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

COMMIT;
