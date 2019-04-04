<?php
// I certify that this submission is my own original work
//Joseph Mineo
//R01581419
    require_once 'functions.php';

queryMysql("create table users(username v archar(128), password varchar(128), email varchar(128), salt1 varchar(10), salt2 varchar(10) engine MyISAM");

    queryMysql("create table video_game(title varchar(256), system varchar(64), company varchar(256), genre varchar(32), year char(4)) ENGINE myISAM");
queryMysql("ALTER TABLE video_game ADD CONSTRAINT video_game_pk PRIMARY KEY(title, system)");

queryMysql("INSERT INTO video_game(title, system, company, genre, year) VALUES ('Super Mario Bros.', 'Nintendo Entertainment System', 'Nintendo', 'Platformer', '1985')");
queryMysql("INSERT INTO video_game(title, system, company, genre, year) VALUES ('Spyro the Dragon', 'PlayStation', 'Insomniac Games', 'Platformer', '1998')");
queryMysql("INSERT INTO video_game(title, system, company, genre, year) VALUES ('Metal Gear Soild', 'PlayStation', 'Konami', 'Action-Adventure Stealth', '1998')");


?>