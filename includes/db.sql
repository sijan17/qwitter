CREATE TABLE users(
user_id int not null auto_increment,
user_fullname  varchar(50) not null,
user_email varchar(50) not null,
user_username varchar(20) not null,
user_password varchar(50) not null,
session varchar(50),
PRIMARY KEY(user_id)
);

CREATE TABLE profile_info(
info_id int not null auto_increment,
user_id int not null,
location varchar(100),
bio varchar(255),
birthday datetime,
join_date datetime,
picture varchar(50) DEFAULT 'user-default.png',
verified int DEFAULT 0,
primary key(info_id)
);

CREATE TABLE tweets(
tweet_id int  not null auto_increment,
tweet_by int not null,
tweet_content varchar(400) not null,
tweet_time datetime not null,
primary key(tweet_id)
);

CREATE TABLE chat(
chat_id int not null auto_increment,
chat_from int not null,
chat_to int not null,
chat_content varchar(250) not null,
chat_time datetime not null,
primary key(chat_id)
);

CREATE TABLE messages (
  msg_id int NOT NULL AUTO_INCREMENT,
  incoming_msg_id int NOT NULL,
  outgoing_msg_id int NOT NULL,
  msg varchar(1000) NOT NULL,
  msg_time datetime NOT NULL,
  primary key(msg_id)
) ;

CREATE TABLE follow(
  id int not null auto_increment,
  user_id int not null,
  follows int not null,
  primary key(id)
);

CREATE TABLE likes(
  id int not null auto_increment,
  tweet_id int not null,
  liked_by int not null,
  primary key(id)
);