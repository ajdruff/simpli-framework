-- Create a Scheduled Event
-- To Start event processing, SET GLOBAL event_scheduler = On;
-- Dont forget to drop the event before you recreate it
-- DROP EVENT nstock_update_domain_listings
DROP EVENT nstock_update_domain_listings
delimiter |

CREATE EVENT nstock_update_domain_listings
    ON SCHEDULE
      EVERY 15 SECOND
	STARTS '2013-11-08 21:00:00'
    ON COMPLETION PRESERVE
    ENABLE
    COMMENT 'Updates Domain Listings By Adding New, Removing Expired and Late'
    DO
      BEGIN

-- Expire Old Listings
-- @param Listing Duration
call `simpli_wp_dev`.`nstock_expire_old_listings`(24);

-- Activate Approved Listings
-- @param Listing Duration
call `simpli_wp_dev`.`nstock_activate_approved_listings`(24);

-- Archive Duplicate Listings
call `simpli_wp_dev`.`nstock_archive_duplicate_listings`();

-- Archive Late Submissions
-- @param Maximum Listed Domains
call `simpli_wp_dev`.`nstock_archive_late_listings`(1000);

-- Set Listing Start Time
call `simpli_wp_dev`.`nstock_set_listing_start_time`();



INSERT INTO simpli_wp_dev.nstock_event_logs (log, time_added) VALUES ('Updated Listings', null);
	
      END |

delimiter ;


-- insert test to test triggers
INSERT INTO `simpli_wp_dev`.`nstock_domains`(`id`,`domain_name`,`subdomain`,`tld`,`bin`,`bid`,`price`,`currency`,`time_added`,`time_expired`,`featured`,`seller`,`approved`,`time_lastupdated`,`time_approved`,`reg_available`,`time_list_start`,`time_list_stop`,`list_status`,`not_listed_reason`,`price_note`,`source`,`added_by`,`concat_test`) VALUES (null,null,'test5','com','y','n','885','USD',null,null,'n','1','n',null,null,'n',null,null,'pending',null,null,'member_inventory','1',null);


select domain_name(id) as domain_name, nstock_domains.* from nstock_domains;
