-- Create a Scheduled Event
-- To Start event processing, SET GLOBAL event_scheduler = On;
-- Dont forget to drop the event before you recreate it
-- DROP EVENT nstock_update_domain_listings
delimiter |

CREATE EVENT nstock_update_domain_listings
    ON SCHEDULE
      EVERY 15 MINUTE
	STARTS '2013-11-08 21:00:00'
    ON COMPLETION PRESERVE
    ENABLE
    COMMENT 'Updates Domain Listings By Adding New, Removing Expired and Late'
    DO
      BEGIN

-- Expire Old Listings
-- @param Listing Duration
call `nstock_expire_old_listings`(24);

-- Activate Approved Listings
-- @param Listing Duration
call `nstock_activate_approved_listings`(24);

-- Archive Duplicate Listings
call `nstock_archive_duplicate_listings`();

-- Archive Late Submissions
-- @param Maximum Listed Domains
call `nstock_archive_late_listings`(1000);
INSERT INTO nstock_event_logs (log, time_added) VALUES ('Updated Listings', null);
	
      END |

delimiter ;
