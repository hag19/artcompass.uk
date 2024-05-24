create DATABASE IF NOT EXISTS site;
USE site;
create USER IF NOT EXISTS hag@'%' IDENTIFIED BY 'hag19';
GRANT ALL PRIVILEGES ON site.* TO hag@'%';

CREATE TABLE IF NOT EXISTS blog (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(40),
    description TEXT,
    image VARCHAR(40),
    date DATE,
    likes INT(11)
);

-- Create table `CAPTHCA`
CREATE TABLE IF NOT EXISTS CAPTHCA (
    id_captcha INT(11) AUTO_INCREMENT PRIMARY KEY,
    questions VARCHAR(255),
    goodAnswer VARCHAR(255)
);

-- Create table `comments_b`
CREATE TABLE IF NOT EXISTS comments_b (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_blog INT(11),
    id_user INT(11),
    comment TEXT,
    created_at TIMESTAMP,
    likes INT(11),
    FOREIGN KEY (id_blog) REFERENCES blog(id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);
create table if not exists comments_p (id INT AUTO_INCREMENT PRIMARY KEY,
    id_post INT NOT NULL,
    id_user INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_post) REFERENCES posts(id),
    FOREIGN KEY (id_user) REFERENCES users(id)) ;
-- Create table `dm`
CREATE TABLE IF NOT EXISTS dm (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user1 INT(11),
    id_user2 INT(11),
    created_at TIMESTAMP,
    FOREIGN KEY (id_user1) REFERENCES users(id),
    FOREIGN KEY (id_user2) REFERENCES users(id)
);


-- Create table `flowers`
CREATE TABLE IF NOT EXISTS friends (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user_1 INT(11),
    id_user_2 INT(11),
    FOREIGN KEY (id_user_m) REFERENCES users(id),
    FOREIGN KEY (id_user_f) REFERENCES users(id)
);

-- Create table `news`
create table if not exists likes_b (id INT AUTO_INCREMENT PRIMARY KEY,
    id_blog INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_blog) REFERENCES blog(id),
    FOREIGN KEY (id_user) REFERENCES users(id)) ;
-- create table `likes_p`
create table if not exists likes_p (id INT AUTO_INCREMENT PRIMARY KEY,
    id_post INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_post) REFERENCES posts(id),
    FOREIGN KEY (id_user) REFERENCES users(id)) ;
    --create table likes_b_c
create table if not exists likes_b_c (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comment INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_comment) REFERENCES comments_b(id),
    FOREIGN KEY (id_user) REFERENCES users(id)) ;
    --create table likes_p_c
create table if not exists likes_p_c (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comment INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_comment) REFERENCES comments_p(id),
    FOREIGN KEY (id_user) REFERENCES users(id)) ;   
    --create table messages
create table if not exists messages (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    id_dm INT NOT NULL,
    id_user INT NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_dm) REFERENCES dm(id),
    FOREIGN KEY (id_user) REFERENCES users(id)) ;
    --create table posts
-- Create table `news`

CREATE TABLE IF NOT EXISTS news (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150)
);

-- Create table `order`
CREATE TABLE IF NOT EXISTS order (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20),
    lastname VARCHAR(20),
    email VARCHAR(140),
    total_price DECIMAL(20,2),
    id_product INT(11),
    id_visits INT(11),
    id_user INT(11),
    phone CHAR(15),
    token VARCHAR(255),
    review INT(11),
    FOREIGN KEY (id_product) REFERENCES tours(id_product),
    FOREIGN KEY (id_visits) REFERENCES visits(id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);
--create table posts
create table if not exists posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    id_user INT NOT NULL,
    image VARCHAR(255),
    date DATE,
    likes INT(11)
    FOREIGN KEY (id_user) REFERENCES users(id));
-- Create table `reviews`
CREATE TABLE IF NOT EXISTS reviews (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_order INT(11),
    stars INT(11),
    comment TEXT,
    created_at TIMESTAMP,
    name VARCHAR(20),
    FOREIGN KEY (id_order) REFERENCES order(id)
);

-- Create table `tours`
CREATE TABLE IF NOT EXISTS tours (
    id_product INT(11) AUTO_INCREMENT PRIMARY KEY,
    tourname VARCHAR(30),
    description TEXT,
    title TEXT,
    image VARCHAR(50),
    price DECIMAL(20,2),
    number INT(11),
    id_guide INT(11),
    FOREIGN KEY (id_guide) REFERENCES users(id)
);

-- Create table `users`
--create table'transferts
CREATE TABLE IF NOT EXISTS transferts (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name varchar(20),
    description TEXT,
    price DECIMAL(20,2)
);
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150),
    password VARCHAR(255),
    fname varhcar(100),
    lname VARCHAR(100),
    username VARCHAR(40),
    role VARCHAR(10),
    image VARCHAR(255),
    reset_token_hash VARCHAR(255),
    reset_token_expires_at DATETIME,
    code_email VARCHAR(255),
    code_email_valid TINYINT(1),
    ban INT(11)
);
-- Create table `visits`
CREATE TABLE IF NOT EXISTS visits (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    places INT(11),
    date DATE,
    time TIME,
    username VARCHAR(40),
    id_product INT(11),
    places_b INT(11),
    FOREIGN KEY (id_product) REFERENCES tours(id_product),
    FOREIGN KEY (username) REFERENCES users(username)
);

