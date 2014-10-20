DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_set_listing_start_time`$$

CREATE PROCEDURE `nstock_set_listing_start_time`()
BEGIN
PREPARE STMT FROM "
Update nstock_domains
set time_list_start=now()
where list_status='active'
and time_list_start ='0000-00-00 00:00:00'
";


EXECUTE STMT;
    END$$

DELIMITER ;