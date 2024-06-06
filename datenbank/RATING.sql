-- RATING-Tabelle
-- --------------

create table RATING
(
    UID        int auto_increment            primary key,
    Video      int                                  null,
    User       int                                  null,
    Rating     int                                  null,
    RatingDate datetime default current_timestamp() null,

    -- Fremdschlüsselbeziehung RATING.Video -> VIDEO.UID (mit Löschweitergabe)
    constraint RATING_VIDEO_UID_fk
        foreign key (Video) references VIDEO (UID)
            on delete cascade
);

