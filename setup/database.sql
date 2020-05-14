-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50),
    lastName VARCHAR(80),
    username VARCHAR(50),
    password VARCHAR(255)
);