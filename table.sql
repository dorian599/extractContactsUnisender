CREATE TABLE unisender(
email VARCHAR(255),
name VARCHAR(255),
email_status VARCHAR(255),
email_availability VARCHAR(255),
email_add_time VARCHAR(255),
email_confirm_time VARCHAR(255),
email_list_ids VARCHAR(255),
email_subscribe_times VARCHAR(255),
email_unsubscribed_list_ids VARCHAR(255),
tags VARCHAR(255)
);


ALTER TABLE unisender ADD UNIQUE (email,name,email_status,email_availability,email_add_time,email_confirm_time,email_list_ids,email_subscribe_times,email_unsubscribed_list_ids,tags);
