-- ----------------------------------------
-- Event `nstock_update_domain_listings`
-- Updates Domain Listings By Adding New, Removing Expired and Late
-- To Start event processing, SET GLOBAL event_scheduler = On;
-- Dont forget to drop the event before you recreate it
-- ----------------------------------------
delimiter |
DROP EVENT IF EXISTS nstock_update_domain_listings|



CREATE EVENT nstock_update_domain_listings
    ON SCHEDULE
      EVERY 1 HOUR
	STARTS '2013-11-08 21:30:00'
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

-- Set Listing Start Time
call `nstock_set_listing_start_time`();

-- Update Ticker Status
call `nstock_update_ticker_status`();


--INSERT INTO nstock_event_logs (log, time_added) VALUES ('nstock_update_domain_listings completed', null);
Update nstock_event_logs 
set time_added=UTC_TIMESTAMP
where`log`='nstock_update_domain_listings completed';
	
      END |

delimiter ;
