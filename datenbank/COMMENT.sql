-- COMMENT-Tabelle
-- ---------------

create table COMMENT
(
    UID         int auto_increment            primary key,
    Video       int                                  null,
    User        int                                  null,
    Comment     varchar(500)                         null,
    CommentDate datetime default current_timestamp() null,

    -- Fremdschlüsselbeziehung COMMENT.Video -> VIDEO.UID (mit Löschweitergabe)
    constraint COMMENT_VIDEO_UID_fk
        foreign key (Video) references VIDEO (UID)
            on delete cascade
);

