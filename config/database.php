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
    password VARCHAR(60) NOT NULL,
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
    user_id INT(6) UNSIGNED,
    name VARCHAR(30) NOT NULL,
    birth_date DATE NOT NULL,
    profile_picture_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Children created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Schedule table
$sql = "CREATE TABLE Schedule (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    child_id INT(6) UNSIGNED,
    day VARCHAR(10) NOT NULL,
    hour INT(2) NOT NULL,
    type VARCHAR(5) NOT NULL,
    items VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES Children(id) ON DELETE CASCADE,
    UNIQUE KEY unique_schedule (user_id, child_id, day, hour)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Schedule created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Relationships table
$sql = "CREATE TABLE Relationships (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    child_id INT(6) UNSIGNED,
    name VARCHAR(50) NOT NULL,
    relationship_type VARCHAR(50),
    contact_info VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES Children(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Relationships created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Posts table
$sql = "CREATE TABLE Posts (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    child_id INT(6) UNSIGNED,
    title VARCHAR(50) NOT NULL,
    content VARCHAR(255),
    datetime DATETIME NOT NULL,
    mediaId INT(6) UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES Children(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Posts created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Post_tags table
$sql = "CREATE TABLE Post_Tags (
    post_id INT(6) UNSIGNED,
    relationship_id INT(6) UNSIGNED,
    FOREIGN KEY (post_id) REFERENCES Posts(id) ON DELETE CASCADE,
    FOREIGN KEY (relationship_id) REFERENCES Relationships(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Post_Tags created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Medical_Info table
$sql = "CREATE TABLE Medical_Info (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    child_id INT(6) UNSIGNED,
    basic_info TEXT DEFAULT '',
    emergency_contact_info TEXT DEFAULT '',
    medical_conditions TEXT DEFAULT '',
    medication TEXT DEFAULT '',
    allergies TEXT DEFAULT '',
    immunization_record TEXT DEFAULT '',
    insurance_info TEXT DEFAULT '',
    medical_history TEXT DEFAULT '',
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES Children(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Medical_Info created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Media table
$sql = "CREATE TABLE Media (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    child_id INT(6) UNSIGNED,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    datetime DATETIME NOT NULL,
    type VARCHAR(50),
    media_link VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES Children(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Media created successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>
