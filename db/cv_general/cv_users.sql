create table cv_users
(
    uid       int auto_increment
        primary key,
    username  text not null,
    password  text not null,
    salt      text not null,
    email     text not null,
    login_key text null
);

