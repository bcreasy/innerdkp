-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 11, 2007 at 08:48 AM
-- Server version: 5.0.27
-- PHP Version: 5.1.6
-- 
-- Database: `inner-focus`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_attendance`
-- 

CREATE TABLE `innerdkp_attendance` (
  `player_id` int(11) NOT NULL,
  `raid_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_class`
-- 

CREATE TABLE `innerdkp_class` (
  `class_id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  PRIMARY KEY  (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_event`
-- 

CREATE TABLE `innerdkp_event` (
  `event_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_instance`
-- 

CREATE TABLE `innerdkp_instance` (
  `instance_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `size` smallint(6) NOT NULL,
  PRIMARY KEY  (`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_item`
-- 

CREATE TABLE `innerdkp_item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dkp` float NOT NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_loot`
-- 

CREATE TABLE `innerdkp_loot` (
  `item_id` int(11) NOT NULL,
  `raid_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_player`
-- 

CREATE TABLE `innerdkp_player` (
  `player_id` int(11) NOT NULL auto_increment,
  `class_id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  PRIMARY KEY  (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `innerdkp_raid`
-- 

CREATE TABLE `innerdkp_raid` (
  `raid_id` int(11) NOT NULL auto_increment,
  `event_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`raid_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

