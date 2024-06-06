-- USER-Tabelle
-- ------------

create table USER
(
    UID         int auto_increment primary key,
    LoginName   varchar(32) null,
    DisplayName varchar(64) null,
    Class       int         null,
    LastLogin   datetime    null
);

