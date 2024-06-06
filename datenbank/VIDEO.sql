-- VIDEO-Tabelle
-- -------------

create table VIDEO
(
    UID         int auto_increment            primary key,
    Owner       int                                  null,
    `Key`       varchar(32)                          null,
    Title       varchar(64)                          null,
    Description varchar(1024)                        null,
    File        varchar(64)                          null,
    Thumbnail   varchar(64)                          null,
    UploadDate  datetime default current_timestamp() null
);

