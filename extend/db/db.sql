/*Table structure for table `admin_log` */

DROP TABLE IF EXISTS `admin_log`;

CREATE TABLE `admin_log` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `controller_id` VARCHAR(20) DEFAULT NULL COMMENT '控制器ID',
  `action_id` VARCHAR(20) DEFAULT NULL COMMENT '方法ID',
  `url` VARCHAR(200) DEFAULT NULL COMMENT '访问地址',
  `module_name` VARCHAR(50) DEFAULT NULL COMMENT '模块',
  `func_name` VARCHAR(50) DEFAULT NULL COMMENT '功能',
  `right_name` VARCHAR(50) DEFAULT NULL COMMENT '方法',
  `client_ip` VARCHAR(15) DEFAULT NULL COMMENT '客户端IP',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '用户',
  `create_date` DATETIME DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `index_create_date` (`create_date`),
  KEY `index_create_index` (`create_user`),
  KEY `index_url` (`url`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

/*Data for the table `admin_log` */

/*Table structure for table `admin_module` */

DROP TABLE IF EXISTS `admin_module`;

CREATE TABLE `admin_module` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` VARCHAR(50) NOT NULL COMMENT 'code',
  `display_label` VARCHAR(200) NOT NULL COMMENT '显示名称',
  `has_lef` VARCHAR(1) NOT NULL DEFAULT 'n' COMMENT '是否有子',
  `des` VARCHAR(400) DEFAULT NULL COMMENT '描述',
  `entry_url` VARCHAR(100) DEFAULT NULL COMMENT '入口地址',
  `display_order` INT(5) DEFAULT NULL COMMENT '顺序',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '修改人',
  `update_date` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `admin_module` */

INSERT  INTO `admin_module`(`id`,`code`,`display_label`,`has_lef`,`des`,`entry_url`,`display_order`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (1,'menu_manage','菜单用户权限','n','菜单管理','',1,'admin','2016-08-11 15:26:21','admin','2016-08-11 16:31:08'),(2,'rizhimaanage','日志管理','n','日志管理','',2,'test','2016-08-14 06:53:13','test','2016-08-14 06:53:13');


/*Table structure for table `admin_menu` */

DROP TABLE IF EXISTS `admin_menu`;

CREATE TABLE `admin_menu` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` VARCHAR(50) NOT NULL COMMENT 'code',
  `menu_name` VARCHAR(200) NOT NULL COMMENT '名称',
  `module_id` INT(11) NOT NULL COMMENT '模块id',
  `display_label` VARCHAR(200) DEFAULT NULL COMMENT '显示名',
  `des` VARCHAR(400) DEFAULT NULL COMMENT '描述',
  `display_order` INT(5) DEFAULT NULL COMMENT '显示顺序',
  `entry_right_name` VARCHAR(50) DEFAULT NULL COMMENT '入口地址名称',
  `entry_url` VARCHAR(200) NOT NULL COMMENT '入口地址',
  `action` VARCHAR(50) NOT NULL COMMENT '操作ID',
  `controller` VARCHAR(100) NOT NULL COMMENT '控制器ID',
  `has_lef` VARCHAR(1) NOT NULL DEFAULT 'n' COMMENT '是否有子',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '修改人',
  `update_date` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_code` (`code`),
  KEY `fk_module_id` (`module_id`),
  CONSTRAINT `fk_module_id` FOREIGN KEY (`module_id`) REFERENCES `admin_module` (`id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `admin_menu` */

INSERT  INTO `admin_menu`(`id`,`code`,`menu_name`,`module_id`,`display_label`,`des`,`display_order`,`entry_right_name`,`entry_url`,`action`,`controller`,`has_lef`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (1,'menu_manger','菜单管理',1,'菜单管理','菜单管理',1,'菜单管理','admin-module/index','index','backend\\controllers\\AdminMenuController','n','admin','2016-08-11 16:44:11','admin','2016-08-11 16:44:11'),(2,'menu_role','角色管理',1,'角色管理','角色管理',2,'角色管理','admin-role/index','index','backend\\controllers\\AdminRoleController','n','admin','2016-08-11 16:51:56','admin','2016-08-11 16:51:56'),(3,'menu_user','用户管理',1,'用户管理','用户管理',3,'用户管理','admin-user/index','index','backend\\controllers\\AdminUserController','n','admin','2016-08-11 16:58:43','admin','2016-08-11 16:58:43'),(4,'coazaorizhi','操作日志',2,'操作日志','操作日志',1,'','admin-log/index','index','backend\\controllers\\AdminLogController','n','test','2016-08-14 06:54:17','test','2016-08-14 06:54:17');

/*Table structure for table `admin_message` */

DROP TABLE IF EXISTS `admin_message`;

CREATE TABLE `admin_message` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `msg` VARCHAR(1000) DEFAULT NULL COMMENT '留言内容',
  `expiry_days` INT(5) UNSIGNED DEFAULT NULL COMMENT '有效天数',
  `create_user` VARCHAR(50) DEFAULT NULL,
  `create_date` DATETIME DEFAULT NULL,
  `update_user` VARCHAR(50) DEFAULT NULL,
  `update_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `admin_message` */

INSERT  INTO `admin_message`(`id`,`msg`,`expiry_days`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (1,'测试文本',1,'admin','2014-11-21 18:47:20','admin','2014-11-21 18:47:27');


/*Table structure for table `admin_right` */

DROP TABLE IF EXISTS `admin_right`;

CREATE TABLE `admin_right` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `menu_id` INT(11) NOT NULL COMMENT '功能主键',
  `right_name` VARCHAR(200) NOT NULL COMMENT '名称',
  `display_label` VARCHAR(200) DEFAULT NULL COMMENT '显示名',
  `des` VARCHAR(200) DEFAULT NULL COMMENT '描述',
  `display_order` INT(5) DEFAULT NULL COMMENT '显示顺序',
  `has_lef` VARCHAR(1) NOT NULL DEFAULT 'n' COMMENT '是否有子',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '修改人',
  `update_date` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `FK_admin_right` (`menu_id`),
  KEY `index_menu_id` (`menu_id`),
  CONSTRAINT `FK_admin_right` FOREIGN KEY (`menu_id`) REFERENCES `admin_menu` (`id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `admin_right` */

INSERT  INTO `admin_right`(`id`,`menu_id`,`right_name`,`display_label`,`des`,`display_order`,`has_lef`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (4,2,'角色操作','角色操作','角色操作',1,'n','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(5,2,'分配用户','分配用户','分配用户',2,'n','admin','2016-08-13 17:05:04','test','2016-08-14 08:22:13'),(6,2,'分配权限','分配权限','分配权限',3,'n','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(7,3,'用户操作','用户操作','用户操作',1,'n','admin','2016-08-13 17:05:57','admin','2016-08-13 17:05:57'),(8,4,'操作','操作','操作',1,'n','test','2016-08-14 06:54:38','test','2016-08-14 06:54:38'),(13,1,'一级菜单查看','一级菜单查看','一级菜单查看',1,'n','test','2016-08-16 15:52:45','test','2016-08-16 15:52:45'),(14,1,'一级菜单添加','一级菜单添加','一级菜单添加',2,'n','test','2016-08-16 15:53:10','test','2016-08-16 15:58:30'),(15,1,'一级菜单删除','一级菜单删除','一级菜单删除',3,'n','test','2016-08-16 15:53:44','test','2016-08-16 15:53:44'),(16,1,'二级菜单查看','二级菜单查看','二级菜单查看',4,'n','test','2016-08-16 15:55:02','test','2016-08-16 15:55:02'),(17,1,'二级菜单添加','二级菜单修改','二级菜单添加',5,'n','test','2016-08-16 15:55:21','test','2016-08-16 15:58:50'),(18,1,'二级菜单删除','二级菜单删除','二级菜单删除',6,'n','test','2016-08-16 15:55:58','test','2016-08-16 15:55:58'),(19,1,'路由查看','路由查看','路由查看',7,'n','test','2016-08-16 15:56:32','test','2016-08-16 15:57:14'),(20,1,'路由添加','路由添加','路由添加',8,'n','test','2016-08-16 15:57:46','test','2016-08-16 15:57:46'),(21,1,'路由删除','路由删除','路由删除',9,'n','test','2016-08-16 15:58:05','test','2016-08-16 15:58:05');

/*Table structure for table `admin_right_url` */

DROP TABLE IF EXISTS `admin_right_url`;

CREATE TABLE `admin_right_url` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `right_id` INT(11) NOT NULL COMMENT 'right主键',
  `url` VARCHAR(200) DEFAULT NULL COMMENT 'url',
  `para_name` VARCHAR(40) DEFAULT NULL COMMENT '参数名',
  `para_value` VARCHAR(40) DEFAULT NULL COMMENT '参数值',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '修改人',
  `update_date` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `FK_admin_right_url` (`right_id`),
  KEY `index_right_id` (`right_id`),
  CONSTRAINT `FK_admin_right_url` FOREIGN KEY (`right_id`) REFERENCES `admin_right` (`id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

/*Data for the table `admin_right_url` */

INSERT  INTO `admin_right_url`(`id`,`right_id`,`url`,`para_name`,`para_value`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (16,4,'admin-role/index','admin-role','index','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(17,4,'admin-role/view','admin-role','view','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(18,4,'admin-role/create','admin-role','create','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(19,4,'admin-role/update','admin-role','update','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(20,4,'admin-role/delete','admin-role','delete','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(21,4,'admin-role/get-all-rights','admin-role','get-all-rights','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(22,4,'admin-role/save-rights','admin-role','save-rights','admin','2016-08-13 17:04:40','admin','2016-08-13 17:04:40'),(30,6,'admin-role/index','admin-role','index','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(31,6,'admin-role/view','admin-role','view','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(32,6,'admin-role/create','admin-role','create','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(33,6,'admin-role/update','admin-role','update','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(34,6,'admin-role/delete','admin-role','delete','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(35,6,'admin-role/get-all-rights','admin-role','get-all-rights','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(36,6,'admin-role/save-rights','admin-role','save-rights','admin','2016-08-13 17:05:24','admin','2016-08-13 17:05:24'),(37,7,'admin-user/index','admin-user','index','admin','2016-08-13 17:05:57','admin','2016-08-13 17:05:57'),(38,7,'admin-user/view','admin-user','view','admin','2016-08-13 17:05:57','admin','2016-08-13 17:05:57'),(39,7,'admin-user/create','admin-user','create','admin','2016-08-13 17:05:57','admin','2016-08-13 17:05:57'),(40,7,'admin-user/update','admin-user','update','admin','2016-08-13 17:05:57','admin','2016-08-13 17:05:57'),(41,7,'admin-user/delete','admin-user','delete','admin','2016-08-13 17:05:57','admin','2016-08-13 17:05:57'),(42,8,'admin-log/index','admin-log','index','test','2016-08-14 06:54:38','test','2016-08-14 06:54:38'),(43,8,'admin-log/view','admin-log','view','test','2016-08-14 06:54:38','test','2016-08-14 06:54:38'),(44,8,'admin-log/create','admin-log','create','test','2016-08-14 06:54:38','test','2016-08-14 06:54:38'),(45,8,'admin-log/update','admin-log','update','test','2016-08-14 06:54:38','test','2016-08-14 06:54:38'),(46,8,'admin-log/delete','admin-log','delete','test','2016-08-14 06:54:38','test','2016-08-14 06:54:38'),(81,5,'admin-user-role/index','admin-user-role','index','test','2016-08-14 08:22:13','test','2016-08-14 08:22:13'),(82,5,'admin-user-role/view','admin-user-role','view','test','2016-08-14 08:22:13','test','2016-08-14 08:22:13'),(83,5,'admin-user-role/create','admin-user-role','create','test','2016-08-14 08:22:13','test','2016-08-14 08:22:13'),(84,5,'admin-user-role/update','admin-user-role','update','test','2016-08-14 08:22:13','test','2016-08-14 08:22:13'),(85,5,'admin-user-role/delete','admin-user-role','delete','test','2016-08-14 08:22:13','test','2016-08-14 08:22:13'),(112,13,'admin-module/index','admin-module','index','test','2016-08-16 15:52:45','test','2016-08-16 15:52:45'),(113,13,'admin-module/view','admin-module','view','test','2016-08-16 15:52:45','test','2016-08-16 15:52:45'),(115,15,'admin-module/delete','admin-module','delete','test','2016-08-16 15:53:44','test','2016-08-16 15:53:44'),(118,16,'admin-menu/index','admin-menu','index','test','2016-08-16 15:55:02','test','2016-08-16 15:55:02'),(119,16,'admin-menu/view','admin-menu','view','test','2016-08-16 15:55:02','test','2016-08-16 15:55:02'),(122,18,'admin-menu/delete','admin-menu','delete','test','2016-08-16 15:55:58','test','2016-08-16 15:55:58'),(125,19,'admin-right/index','admin-right','index','test','2016-08-16 15:57:14','test','2016-08-16 15:57:14'),(126,19,'admin-right/view','admin-right','view','test','2016-08-16 15:57:14','test','2016-08-16 15:57:14'),(127,19,'admin-right/right-action','admin-right','right-action','test','2016-08-16 15:57:14','test','2016-08-16 15:57:14'),(128,20,'admin-right/create','admin-right','create','test','2016-08-16 15:57:46','test','2016-08-16 15:57:46'),(129,20,'admin-right/update','admin-right','update','test','2016-08-16 15:57:46','test','2016-08-16 15:57:46'),(130,21,'admin-right/delete','admin-right','delete','test','2016-08-16 15:58:05','test','2016-08-16 15:58:05'),(131,14,'admin-module/create','admin-module','create','test','2016-08-16 15:58:30','test','2016-08-16 15:58:30'),(132,14,'admin-module/update','admin-module','update','test','2016-08-16 15:58:30','test','2016-08-16 15:58:30'),(133,17,'admin-menu/create','admin-menu','create','test','2016-08-16 15:58:51','test','2016-08-16 15:58:51'),(134,17,'admin-menu/update','admin-menu','update','test','2016-08-16 15:58:51','test','2016-08-16 15:58:51');

/*Table structure for table `admin_role` */

DROP TABLE IF EXISTS `admin_role`;

CREATE TABLE `admin_role` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` VARCHAR(50) NOT NULL COMMENT '角色编号',
  `name` VARCHAR(50) NOT NULL COMMENT '角色名称',
  `des` VARCHAR(400) DEFAULT NULL COMMENT '角色描述',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '更新人',
  `update_date` DATETIME DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_code` (`code`)
) ENGINE=INNODB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `admin_role` */

INSERT  INTO `admin_role`(`id`,`code`,`name`,`des`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (1,'superadmin','超级管理员','拥有所有权限','test','2016-08-12 15:33:01','test','2016-08-12 15:33:01'),(2,'testuser','测试人员','测试人员','test','2016-08-12 15:33:45','test','2016-08-12 15:33:45');

/*Table structure for table `admin_role_right` */

DROP TABLE IF EXISTS `admin_role_right`;

CREATE TABLE `admin_role_right` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` INT(11) NOT NULL COMMENT '角色主键',
  `right_id` INT(11) NOT NULL COMMENT '权限主键',
  `full_path` VARCHAR(250) DEFAULT NULL COMMENT '全路径',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '修改人',
  `update_date` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `index_role_id` (`role_id`),
  KEY `index_right_id` (`right_id`),
  CONSTRAINT `admin_role_right_ibfk_1` FOREIGN KEY (`right_id`) REFERENCES `admin_right` (`id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

/*Data for the table `admin_role_right` */

INSERT  INTO `admin_role_right`(`id`,`role_id`,`right_id`,`full_path`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (95,1,13,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(96,1,14,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(97,1,15,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(98,1,16,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(99,1,17,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(100,1,18,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(101,1,19,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(102,1,20,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(103,1,21,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(104,1,4,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(105,1,5,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(106,1,6,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(107,1,7,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(108,1,8,NULL,'test','2016-08-16 15:59:31','test','2016-08-16 15:59:31'),(112,2,13,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(113,2,14,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(114,2,15,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(115,2,16,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(116,2,17,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(117,2,18,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(118,2,19,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(119,2,20,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(120,2,21,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(121,2,4,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(122,2,5,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(123,2,6,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(124,2,7,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57'),(125,2,8,NULL,'test','2016-08-16 16:02:57','test','2016-08-16 16:02:57');

/*Table structure for table `admin_user` */

DROP TABLE IF EXISTS `admin_user`;

CREATE TABLE `admin_user` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uname` VARCHAR(100) NOT NULL COMMENT '用户名',
  `password` VARCHAR(200) NOT NULL COMMENT '密码',
  `auth_key` VARCHAR(50) DEFAULT NULL COMMENT '自动登录key',
  `last_ip` VARCHAR(50) DEFAULT NULL COMMENT '最近一次登录ip',
  `is_online` CHAR(1) DEFAULT 'n' COMMENT '是否在线',
  `domain_account` VARCHAR(100) DEFAULT NULL COMMENT '域账号',
  `status` SMALLINT(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `create_user` VARCHAR(100) NOT NULL COMMENT '创建人',
  `create_date` DATETIME NOT NULL COMMENT '创建时间',
  `update_user` VARCHAR(101) NOT NULL COMMENT '更新人',
  `update_date` DATETIME NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8;

/*Data for the table `admin_user` */

INSERT  INTO `admin_user`(`id`,`uname`,`password`,`auth_key`,`last_ip`,`is_online`,`domain_account`,`status`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (156,'admin','$2y$13$9O6bKJieocg//oSax9fZOOuljAKarBXknqD8.RyYg60FfNjS7SoqK',NULL,'127.0.0.1','n',NULL,10,'admin','2014-07-07 00:05:47','admin','2014-09-03 12:19:12'),(158,'test','$2y$13$9O6bKJieocg//oSax9fZOOuljAKarBXknqD8.RyYg60FfNjS7SoqK',NULL,'','n',NULL,10,'admin','2014-09-03 12:19:52','admin','2014-11-21 19:19:22');

/*Table structure for table `admin_user_role` */

DROP TABLE IF EXISTS `admin_user_role`;

CREATE TABLE `admin_user_role` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` BIGINT(20) UNSIGNED NOT NULL COMMENT '用户id',
  `role_id` INT(11) NOT NULL COMMENT '角色',
  `create_user` VARCHAR(50) DEFAULT NULL COMMENT '创建人',
  `create_date` DATETIME DEFAULT NULL COMMENT '创建时间',
  `update_user` VARCHAR(50) DEFAULT NULL COMMENT '修改人',
  `update_date` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `index_user_id` (`user_id`),
  KEY `index_role_id` (`role_id`),
  CONSTRAINT `admin_user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `admin_role` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admin_user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_user` (`id`) ON DELETE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `admin_user_role` */

INSERT  INTO `admin_user_role`(`id`,`user_id`,`role_id`,`create_user`,`create_date`,`update_user`,`update_date`) VALUES (1,156,1,'admin','2016-08-12 17:03:13','admin','2016-08-12 17:03:13'),(2,158,2,'test','2016-08-13 16:34:20','test','2016-08-13 16:34:20');


