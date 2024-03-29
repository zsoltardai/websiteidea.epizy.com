CREATE TABLE users(
    id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    profile VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    hometown VARCHAR(255),
    education VARCHAR(255)
);

CREATE TABLE posts (
   id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
   userid INT(11) NOT NULL,
   content LONGTEXT NOT NULL,
   date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reactions(
  id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  postid INT(11) NOT NULL,
  userid INT(11) NOT NULL,
  reaction BOOLEAN NULL
);
