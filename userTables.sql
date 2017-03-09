RENAME TABLE catch TO draught;
ALTER TABLE draught CHANGE draught_id draught_id NOT NULL AUTO_INCREMENT;

ALTER TABLE draught ADD COLUMN user_id INT NOT NULL REFERENCES user(user_id);
ALTER TABLE draught ADD COLUMN deletable SMALLINT DEFAULT 0;

CREATE TABLE user(
	USER_ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  	USERNAME VARCHAR(150) NOT NULL,
    PASSWORD VARCHAR(500) NOT NULL,
    SALT VARCHAR(100) NOT NULL,
    REGISTERED TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ACTIVE SMALLINT DEFAULT 1
);

CREATE TABLE userlogin(
	USER_ID INT NOT NULL REFERENCES user(user_id) ON DELETE CASCADE,
    LOGIN_IP VARCHAR(250) NOT NULL,
    TRIED TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    USERAGENT VARCHAR(250) NOT NULL,
    SUCCESS SMALLINT DEFAULT 0
);

CREATE TABLE usertoken(
	USER_ID INT NOT NULL REFERENCES user(user_id) ON DELETE CASCADE,
    TOKEN VARCHAR(255) NOT NULL,
    SERVICE VARCHAR(50)
);


ALTER TABLE draught ADD FOREIGN KEY fisher_id REFERENCES fisher(fisher_id);
ALTER TABLE draught ADD FOREIGN KEY fish_id REFERENCES fish(fish_id);
ALTER TABLE draught ADD FOREIGN KEY gear_id REFERENCES gear(gear_id);
ALTER TABLE draught ADD FOREIGN KEY place_id REFERENCES place(place_id);
ALTER TABLE draught ADD FOREIGN KEY user_id REFERENCES user(user_id);



ALTER TABLE user ADD COLUMN HASH VARCHAR(255) NOT NULL UNIQUE;
ALTER TABLE user ADD COLUMN SESSIONEXPIRE TIMESTAMP NOT NULL;

ALTER TABLE usertoken ADD PRIMARY KEY (USER_ID, SERVICE);

