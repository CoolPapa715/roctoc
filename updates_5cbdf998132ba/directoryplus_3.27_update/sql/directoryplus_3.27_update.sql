ALTER TABLE `places` CHANGE `short_desc` `short_desc` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

UPDATE `language` SET template = 'global' WHERE var_name = 'txt_please_wait';

INSERT INTO `language` (`lang`, `section`, `template`, `var_name`, `translated`) VALUES
('en', 'admin', 'language', 'txt_create_string', 'Create String'),
('en', 'admin', 'language', 'txt_var_name', 'Variable Name (starts with txt_ e.g. txt_var_name)'),
('en', 'admin', 'language', 'txt_string_value', 'String Value'),
('en', 'admin', 'language', 'txt_string_created', 'String Created'),
('en', 'admin', 'admin-global', 'txt_maps', 'Maps'),
('en', 'admin', 'settings', 'txt_permalink_struct', 'Permalink Structure (*regenerate sitemap after change)'),
('en', 'admin', 'settings', 'txt_permalink_struct_explain', 'Available tags(use / as separator): %category%/%region%/%city%/%title%');

UPDATE `email_templates` SET `available_vars` = '%site_name%\n%site_url%\n%listing_link%' WHERE `type` IN('subscr_signup','web_accept','subscr_eot');
