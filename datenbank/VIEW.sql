-- VIEW-Tabelle
-- ------------

create table VIEW
(
    UID      int auto_increment            primary key,
    Video    int                                  null,
    User     int                                  null,
    ViewDate datetime default current_timestamp() null,

    -- Fremdschlüsselbeziehung VIEW.Video -> VIDEO.UID (mit Löschweitergabe)
    constraint VIEW_VIDEO_UID_fk
        foreign key (Video) references VIDEO (UID)
            on delete cascade
);

