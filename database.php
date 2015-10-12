<?php

/*CREATE USER TABLE FOR THE SAME. */

CREATE TABLE IF NOT EXISTS `advertisement_click_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertisement_id` int(11) NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `click_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `advertisement_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `banner_size` varchar(50) NOT NULL,
  `banner_image` varchar(100) NOT NULL,
  `destination_url` varchar(100) NOT NULL,
  `impression` int(11) NOT NULL,
  `display_counter` int(11) NOT NULL,
  `active_status` int(1) NOT NULL DEFAULT '1' COMMENT '0:inactive, 1:active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



?>