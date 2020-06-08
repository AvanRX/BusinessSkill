CREATE DATABASE access;
USE access;

CREATE TABLE access_log(
    id integer AUTO_INCREMENT,
    accesstime datetime,
    PRIMARY KEY(id)
);