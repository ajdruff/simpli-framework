DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_update_ticker_status`$$

CREATE PROCEDURE `nstock_update_ticker_status`()
BEGIN
-- ----------------------------------------
-- Flags domains with their status as to whether they are currently being listed on the front page (ticker_
-- as determined by the view_domain_listings
-- Do not use this as a prepared statement since multiple statements will give you syntax errors.
-- Necessary to run so we can keep stats up to date.
-- call `nstock_update_ticker_status`;
-- ----------------------------------------

-- first, update domains that are not appearing on ticker 
update nstock_domains 
set `on_ticker`='n'
where `id` NOT in
(select `id` from view_domain_listings);


-- then update those that are
update nstock_domains 
set `on_ticker`='y'
where `id` in
(select `id` from view_domain_listings);


    END$$

DELIMITER ;
