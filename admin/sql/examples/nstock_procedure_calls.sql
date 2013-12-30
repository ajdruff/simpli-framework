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



-- Get Listings
-- @param Maximum Listed Domains
call `simpli_wp_dev`.`nstock_get_listings`(100);


-- reset for testing
update nstock_domains
SET list_status='pending',not_listed_reason=null,time_list_start='0000-00-00 00:00:00',time_list_stop='0000-00-00 00:00:00';
