-- FOLLOWER-Tabelle
-- ----------------

create table FOLLOWER
(
    UID        int auto_increment            primary key,
    User       int                                  null,
    Following  int                                  null,
    FollowDate datetime default current_timestamp() null
);

