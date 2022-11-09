create table cv_api_keys
(
    id             int auto_increment
        primary key,
    api_key_id     text not null,
    api_key_secret text not null,
    api_key_salt   text not null,
    owner_uid      int  not null
);

