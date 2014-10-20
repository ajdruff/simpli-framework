DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_expire_old_listings`$$

CREATE PROCEDURE `nstock_expire_old_listings`(LIST_TIME_IN_HOURS INT)
BEGIN
PREPARE STMT FROM "
Update nstock_domains
set nstock_domains.time_list_stop=now()
,nstock_domains.list_status='archived'
where nstock_domains.time_list_start < (DATE_SUB(NOW(), INTERVAL ? HOUR)) and nstock_domains.list_status='active'
";

SET @LIST_TIME_IN_HOURS=LIST_TIME_IN_HOURS;
EXECUTE STMT USING @LIST_TIME_IN_HOURS;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;