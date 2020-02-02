use `hush_base`;

ALTER TABLE `test` ADD `tags` varchar(255) NOT NULL DEFAULT '' AFTER `type`;
