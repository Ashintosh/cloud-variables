create table cv_users
(
    uid           int auto_increment
        primary key,
    username      text                                                     not null,
    password      text                                                     not null,
    salt          text                                                     not null,
    email         text                                                     not null,
    variables     longtext collate utf8mb4_bin default '{}'                not null,
    login_key     text                                                     null,
    register_date timestamp                    default current_timestamp() not null
);

