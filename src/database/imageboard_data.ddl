SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Post;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS Post (
   postID int NOT NULL AUTO_INCREMENT,
   sessionID varchar(8),
   postContent varchar(2000),
   nickname varchar(32),
   file longblob,
   postTime timestamp,
   parent int,
   primary key (postID)
);

INSERT INTO Post VALUES(-1,"aaaaaaaa","aaaaaaaaa","",NULL,NOW(),-2);
