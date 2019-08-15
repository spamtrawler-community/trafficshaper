SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
<--QUERY-->
SET time_zone = "+00:00";
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_antiwebspam_urls` (
  `id` char(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_asn` (
  `id` char(32) NOT NULL,
  `asn` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_countries` (
  `id` char(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `continent` varchar(255) NOT NULL,
  `iso` char(2) NOT NULL,
  `comment` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_emails` (
  `id` char(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_hostnames` (
  `id` char(32) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_ipranges` (
  `id` char(32) NOT NULL,
  `range_start` varchar(255) NOT NULL,
  `range_end` varchar(255) NOT NULL,
  `range_start_long` varchar(255) NOT NULL,
  `range_end_long` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_ips` (
  `id` char(32) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_organizations` (
  `id` char(32) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_parameter` (
  `id` char(32) NOT NULL,
  `parameter` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_referrer` (
  `id` char(32) NOT NULL,
  `referrer` text NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_useragents` (
  `id` char(32) NOT NULL,
  `useragent` varchar(767) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_blacklist_usernames` (
  `id` char(32) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_visitors` (
  `id` char(32) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `host_nameID` int(11) DEFAULT NULL,
  `asnID` int(11) NOT NULL,
  `asn_orgID` int(11) NOT NULL,
  `country_codeID` int(11) NOT NULL,
  `country_nameID` int(11) DEFAULT NULL,
  `referrerID` int(11) DEFAULT NULL,
  `user_agentID` int(11) DEFAULT NULL,
  `device_typeID` int(11) DEFAULT NULL,
  `emailID` int(11) DEFAULT NULL,
  `usernameID` int(11) DEFAULT NULL,
  `urlID` varchar(255) DEFAULT NULL,
  `blocked` varchar(3) NOT NULL DEFAULT 'no',
  `block_code` varchar(255) DEFAULT NULL,
  `block_reason` varchar(255) DEFAULT NULL,
  `captcha_solved` char(3) DEFAULT 'no',
  `updated` datetime NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_referrer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer` text COLLATE utf8_bin NOT NULL,
  `referrer_hash` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `referrer_hash` (`referrer_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text COLLATE utf8_bin NOT NULL,
  `url_hash` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_hash` (`url_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_useragents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_agent` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_agent` (`user_agent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device` (`device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_asn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asn` varchar(255) COLLATE utf8_bin NOT NULL,
  `asn_orgID` int(11),
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asn` (`asn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_asnorgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asn_org` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asn_org` (`asn_org`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_countryiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` char(3) COLLATE utf8_bin NOT NULL,
  `country_nameID` int(11),
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_countrynames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_name` (`country_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_usernames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cache_hostnames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `used` int(11) NOT NULL,
  `blocked_count` int(11) NOT NULL,
  `passed_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `host_name` (`host_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_cleantalk_urls` (
  `id` char(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_modules` (
  `id` int(11) NOT NULL,
  `module` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
INSERT INTO `%tblprefix%_modules` (`id`, `module`) VALUES
(1, 'Admin'),
(4, 'API'),
(5, 'Feeds'),
(7, 'Filesystem'),
(2, 'Firewall'),
(6, 'Install'),
(3, 'User');
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_notifications` (
  `id` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_projecthoneypot_urls` (
  `id` char(32) NOT NULL,
  `url` varchar(255) DEFAULT 'exact',
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_settings` (
  `conf_group` varchar(255) NOT NULL,
  `conf_module` varchar(255) NOT NULL,
  `conf_category` varchar(255) DEFAULT NULL,
  `conf_name` varchar(256) NOT NULL,
  `conf_status` tinyint(1) NOT NULL DEFAULT '0',
  `conf_params` text,
  `conf_class_name` varchar(255) DEFAULT NULL,
  `conf_group_order` int(11) NOT NULL DEFAULT '0',
  `conf_order` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
INSERT INTO `%tblprefix%_settings` (`conf_group`, `conf_module`, `conf_category`, `conf_name`, `conf_status`, `conf_params`, `conf_class_name`, `conf_group_order`, `conf_order`) VALUES
('modules', 'firewall', 'remote', 'antiwebspam_blacklist_filter', 0, 'a:3:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:11:"AntiWebSpam";s:12:"filter_types";a:3:{i:0;s:9:"hostnames";i:1;s:3:"ips";i:2;s:6:"emails";}}', 'Firewall_AntiWebSpam_Controller_Filter', 4, 22),
('modules', 'firewall', 'blacklist', 'asn_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:13:"ASN Blacklist";}', 'Firewall_ASNBlacklist_Controller_Filter', 3, 17),
('modules', 'firewall', 'whitelist', 'asn_whitelist_filter', 0, '', 'Firewall_ASNWhitelist_Controller_Filter', 2, 6),
('modules', 'firewall', 'remote', 'cleantalk_blacklist_filter', 0, 'a:3:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:9:"CleanTalk";s:7:"api_key";s:0:"";}', 'Firewall_CleanTalk_Controller_Filter', 4, 25),
('core', 'core', 'core', 'core', 0, 'a:12:{s:8:"timezone";s:3:"UTC";s:17:"admin_auth_method";s:16:"UsernamePassword";s:11:"duosec_akey";s:0:"";s:11:"duosec_ikey";s:0:"";s:11:"duosec_skey";s:0:"";s:11:"duosec_host";s:0:"";s:7:"sysmode";s:1:"0";s:18:"maintenance_status";s:1:"0";s:18:"maintenance_action";s:11:"exitmessage";s:30:"maintenance_redirection_target";s:0:"";s:24:"maintenance_exit_message";s:0:"";s:10:"licensekey";s:0:"";}', NULL, 1, 0),
('modules', 'firewall', 'blacklist', 'country_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:17:"Country Blacklist";}', 'Firewall_CountryBlacklist_Controller_Filter', 3, 13),
('modules', 'firewall', 'contentblacklist', 'email_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:15:"Email Blacklist";}', 'Firewall_EmailBlacklist_Controller_Filter', 3, 12),
('modules', 'firewall', 'whitelist', 'email_whitelist_filter', 0, '', 'Firewall_EmailWhitelist_Controller_Filter', 2, 4),
('modules', 'firewall', 'core', 'firewall_core', 0, 'a:19:{s:4:"mode";s:10:"integrated";s:6:"apikey";s:32:"151195ca6cc1f739a398a9f74df31d21";s:19:"visitorcache_status";s:1:"1";s:16:"filter_post_only";s:1:"1";s:13:"cookie_status";s:1:"0";s:11:"cookie_name";s:19:"SpamTrawlerFirewall";s:20:"cookie_blocked_value";s:7:"Blocked";s:22:"cookie_permitted_value";s:9:"Permitted";s:13:"cookie_expiry";s:2:"12";s:13:"cookie_domain";s:9:"localhost";s:11:"cookie_path";s:1:"/";s:12:"block_action";s:11:"exitmessage";s:18:"redirection_target";s:0:"";s:12:"exit_message";s:1239:"<p><strong>Sorry, you have been blocked!</strong></p>\r\n<p>We are sorry you have reached this page because you have been blocked based on our firewall rules.<strong><br /></strong></p>\r\n<p><strong><span style="text-decoration: underline;">This may have been caused by one or more of the following reasons:</span><br /></strong></p>\r\n<ul>\r\n<li>Your PC may be infected with a virus or botnet software program</li>\r\n<li>Someone in your organization may have a PC infected with a virus or botnet program</li>\r\n<li>You may be utilizing a dynamic IP address which was previously utilized by a known spammer</li>\r\n<li>Your marketing department may be sending out bulk emails that do not comply with the CAN-SPAM Act</li>\r\n<li>You may have an insecure wireless network which is allowing unknown users to use your network to send spam</li>\r\n<li>Content submitted by you may have been classified as "malicious"</li>\r\n<li>Your country has been blacklisted by us</li>\r\n<li>In some rare cases, this may be a "false positive"</li>\r\n</ul>\r\n<p>These measures are in place to increase the safety of this website and to keep it clean for our valued members.</p>\r\n<p><br />If you think you may have been blocked by mistake, please feel free to contact us.</p>";s:17:"recaptcha_sitekey";s:0:"";s:16:"recaptcha_secret";s:0:"";s:18:"recaptcha_language";s:4:"auto";s:14:"usernamefields";s:0:"";s:11:"emailfields";s:0:"";}', 'Firewall_Core_Controller_Filter', 1, 1),
('modules', 'firewall', 'blacklist', 'hostname_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:18:"Hostname Blacklist";}', 'Firewall_HostnameBlacklist_Controller_Filter', 3, 18),
('modules', 'firewall', 'whitelist', 'hostname_whitelist_filter', 0, '', 'Firewall_HostnameWhitelist_Controller_Filter', 2, 9),
('modules', 'firewall', 'blacklist', 'ipranges_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:19:"IP Ranges Blacklist";}', 'Firewall_IPRangesBlacklist_Controller_Filter', 3, 20),
('modules', 'firewall', 'whitelist', 'ipranges_whitelist_filter', 0, '', 'Firewall_IPRangesWhitelist_Controller_Filter', 2, 8),
('modules', 'firewall', 'blacklist', 'ip_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:12:"IP Blacklist";}', 'Firewall_IPBlacklist_Controller_Filter', 3, 19),
('modules', 'firewall', 'whitelist', 'ip_whitelist_filter', 0, NULL, 'Firewall_IPWhitelist_Controller_Filter', 2, 7),
('modules', 'firewall', 'blacklist', 'organization_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:22:"Organization Blacklist";}', 'Firewall_OrganizationBlacklist_Controller_Filter', 3, 16),
('modules', 'firewall', 'whitelist', 'organization_whitelist_filter', 0, '', 'Firewall_OrganizationWhitelist_Controller_Filter', 2, 5),
('modules', 'firewall', 'contentblacklist', 'parameter_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:19:"Parameter Blacklist";}', 'Firewall_ParameterBlacklist_Controller_Filter', 3, 11),
('modules', 'firewall', 'whitelist', 'path_whitelist_filter', 0, '', 'Firewall_PathWhitelist_Controller_Filter', 2, 3),
('modules', 'firewall', 'remote', 'projecthoneypot_blacklist_filter', 0, 'a:6:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:16:"Project Honeypot";s:7:"api_key";s:0:"";s:13:"last_activity";s:2:"30";s:12:"threat_score";s:2:"25";s:11:"block_types";a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";}}', 'Firewall_ProjectHoneypot_Controller_Filter', 4, 23),
('modules', 'firewall', 'blacklist', 'referrer_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:17:"Referer Blacklist";}', 'Firewall_ReferrerBlacklist_Controller_Filter', 3, 15),
('modules', 'firewall', 'remote', 'satellite_client_filter', 0, 'a:3:{s:13:"satellite_url";s:0:"";s:6:"apikey";s:0:"";s:12:"block_reason";s:9:"Satellite";}', 'Firewall_Satellite_Controller_Filter', 4, 21),
('modules', 'firewall', 'remote', 'spamhaus_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:8:"SpamHaus";}', 'Firewall_SpamHaus_Controller_Filter', 4, 26),
('modules', 'firewall', 'remote', 'stopforumspam_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:13:"StopForumSpam";}', 'Firewall_StopForumSpam_Controller_Filter', 4, 24),
('modules', 'firewall', 'whitelist', 'url_whitelist_filter', 0, '', 'Firewall_URLWhitelist_Controller_Filter', 2, 2),
('modules', 'firewall', 'blacklist', 'useragent_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:19:"Useragent Blacklist";}', 'Firewall_UseragentBlacklist_Controller_Filter', 3, 14),
('modules', 'firewall', 'contentblacklist', 'username_blacklist_filter', 0, 'a:2:{s:12:"allowcaptcha";s:1:"1";s:12:"block_reason";s:18:"Username Blacklist";}', 'Firewall_UsernameBlacklist_Controller_Filter', 3, 10);
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_spamhaus_urls` (
  `id` char(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_stopforumspam_urls` (
  `id` char(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_user` (
  `id` int(10) NOT NULL,
  `group_id` smallint(4) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `email` varchar(255) NOT NULL,
  `twofactor` char(50) NOT NULL DEFAULT 'false',
  `comment` text,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `u2f_keyHandle` varchar(255) DEFAULT NULL,
  `u2f_publicKey` varchar(255) DEFAULT NULL,
  `u2f_certificate` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_user_groups` (
  `id` int(10) NOT NULL,
  `group_id` smallint(4) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
INSERT INTO `%tblprefix%_user_groups` (`id`, `group_id`, `group_name`, `comment`) VALUES
(1, 1, 'Administrators', 'Test'),
(2, 2, 'Moderators', 'Test');
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_asn` (
  `id` char(32) NOT NULL,
  `asn` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_emails` (
  `id` char(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_hostnames` (
  `id` char(32) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_ipranges` (
  `id` char(32) NOT NULL,
  `range_start` varchar(255) NOT NULL,
  `range_end` varchar(255) NOT NULL,
  `range_start_long` varchar(255) NOT NULL,
  `range_end_long` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_ips` (
  `id` char(32) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_organizations` (
  `id` char(32) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_paths` (
  `id` char(32) NOT NULL,
  `path` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
CREATE TABLE IF NOT EXISTS `%tblprefix%_whitelist_urls` (
  `id` char(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `filter_mode` varchar(255) DEFAULT 'exact',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
<--QUERY-->
ALTER TABLE `%tblprefix%_antiwebspam_urls`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `url` (`url`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_asn`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `asn` (`asn`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_countries`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `country` (`iso`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_emails`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `email` (`email`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_hostnames`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `hostname` (`hostname`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_ipranges`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `range_start` (`range_start`),
ADD UNIQUE KEY `range_end` (`range_end`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_ips`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `ip` (`ip`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_organizations`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `organization` (`organization`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_parameter`
ADD PRIMARY KEY (`id`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_referrer`
ADD PRIMARY KEY (`id`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_useragents`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `useragent` (`useragent`);
<--QUERY-->
ALTER TABLE `%tblprefix%_blacklist_usernames`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`);
<--QUERY-->
ALTER TABLE `%tblprefix%_cache_visitors`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `ip` (`ip`);
<--QUERY-->
ALTER TABLE `%tblprefix%_cleantalk_urls`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `url` (`url`),
ADD UNIQUE KEY `url_2` (`url`);
<--QUERY-->
ALTER TABLE `%tblprefix%_modules`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `module` (`module`);
<--QUERY-->
ALTER TABLE `%tblprefix%_notifications`
ADD PRIMARY KEY (`id`);
<--QUERY-->
ALTER TABLE `%tblprefix%_projecthoneypot_urls`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `url` (`url`);
<--QUERY-->
ALTER TABLE `%tblprefix%_settings`
ADD PRIMARY KEY (`conf_name`),
ADD UNIQUE KEY `conf_class_name` (`conf_class_name`);
<--QUERY-->
ALTER TABLE `%tblprefix%_spamhaus_urls`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `url` (`url`);
<--QUERY-->
ALTER TABLE `%tblprefix%_stopforumspam_urls`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `url` (`url`);
<--QUERY-->
ALTER TABLE `%tblprefix%_user`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`,`email`);
<--QUERY-->
ALTER TABLE `%tblprefix%_user_groups`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`group_name`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_asn`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `asn` (`asn`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_emails`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `email` (`email`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_hostnames`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `hostname` (`hostname`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_ipranges`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `range_start` (`range_start`),
ADD UNIQUE KEY `range_end` (`range_end`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_ips`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `ip` (`ip`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_organizations`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `organization` (`organization`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_paths`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `path` (`path`);
<--QUERY-->
ALTER TABLE `%tblprefix%_whitelist_urls`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `url` (`url`);
<--QUERY-->
ALTER TABLE `%tblprefix%_modules`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
<--QUERY-->
ALTER TABLE `%tblprefix%_user`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
<--QUERY-->
ALTER TABLE `%tblprefix%_user_groups`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
