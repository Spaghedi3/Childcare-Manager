<?php

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Drop database if it already exists
$sql = "DROP DATABASE IF EXISTS web";
if ($conn->query($sql) === TRUE) {
    echo "Database dropped successfully" . "<br>";
} else {
    echo "Error dropping database: " . $conn->error;
}

// Create database  
$sql = "CREATE DATABASE web";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully" . "<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select database
$conn->select_db("web");

// Users table
$sql = "CREATE TABLE Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    name VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Users created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Children table
$sql = "CREATE TABLE Children (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    birth_date DATE NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Children created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Users_Children table
$sql = "CREATE TABLE Users_Children (
    user_id INT(6) UNSIGNED,
    child_id INT(6) UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (child_id) REFERENCES Children(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Parents_Children created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Events table
$sql = "CREATE TABLE Events (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    datetime DATETIME
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Events created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Relationships table
$sql = "CREATE TABLE Relationships (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    child_id INT(6) UNSIGNED,
    name VARCHAR(50) NOT NULL,
    relationship_type VARCHAR(50),
    contact_info VARCHAR(255),
    FOREIGN KEY (child_id) REFERENCES Children(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Relationships created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Posts table
$sql = "CREATE TABLE Posts (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    child_id INT(6) UNSIGNED,
    title VARCHAR(50) NOT NULL,
    content VARCHAR(255),
    datetime DATETIME NOT NULL,
    media_link VARCHAR(255)
)";

// Post_tags table
$sql = "CREATE TABLE Post_Tags (
    post_id INT(6) UNSIGNED,
    relationship_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES Posts(id),
    FOREIGN KEY (relationship_id) REFERENCES Relationships(id)
)";

// Medical_Info table
$sql = "CREATE TABLE Medical_Info (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    basic_info TEXT,
    emergency_contact_info TEXT,
    medical_conditions TEXT,
    medication TEXT,
    allergies TEXT,
    immunization_record TEXT,
    insurance_info TEXT,
    medical_history TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Medical_Info created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>