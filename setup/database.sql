-- Create users table if it doesn't exist
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(80) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create members table if it doesn't exist
DROP TABLE IF EXISTS members;
CREATE TABLE members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(80) NOT NULL,
    membership DATETIME
);

-- Create teams table if it doesn't exist
DROP TABLE IF EXISTS teams;
CREATE TABLE teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    activity_id INT NOT NULL
);

-- Create activities table if it doesn't exist
DROP TABLE IF EXISTS activities;
CREATE TABLE activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Create pivot table for teams and members
DROP TABLE IF EXISTS teams_members;
CREATE TABLE teams_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    member_id INT NOT NULL
);