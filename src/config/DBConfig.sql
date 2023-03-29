create schema meme_game_bot_db;

use meme_game_bot_db;

create table Chats
(
    id BIGINT not null primary key unique
);

create table Themes
(
    id      BIGINT       not null primary key unique auto_increment,
    chat_id BIGINT       not null,
    name    varchar(255) not null,
    foreign key theme2chat (chat_id) references Chats (id)
);

create table Games
(
    id          BIGINT          not null primary key unique auto_increment,
    chat_id     BIGINT          not null,
    player_name varchar(255)    not null,
    foreign key game2chat (chat_id) references Chats(id)
);

alter table Themes
    add unique chatIdName (chat_id, name);

# SET FOREIGN_KEY_CHECKS = 0;
# truncate table chats;
# truncate table texts;
# SET FOREIGN_KEY_CHECKS = 1;

SET collation_connection = 'utf8_general_ci';
ALTER DATABASE meme_game_bot_db CHARACTER SET utf8 COLLATE utf8_general_ci;


ALTER TABLE Themes
    CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE Chats
    CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE Games
    CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
