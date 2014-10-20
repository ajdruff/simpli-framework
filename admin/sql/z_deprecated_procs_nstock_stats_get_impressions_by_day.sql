DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_impressions_by_day`$$

CREATE PROCEDURE `nstock_stats_get_impressions_by_day`(DOMAIN_NAME VARCHAR(255))
-- ---------------------------------
-- DEPRECATED - Use nstock_stats_get_report instead.
-- Given a domain, returns the number of impressions per day
-- call nstock_stats_get_impressions_by_day('mycooldomain.com');
-- ---------------------------------

BEGIN
-- Some Comment
PREPARE STMT FROM "
-- to use the paramater, refer to it by using a ?

Select `date`,count from 
nstock_stats_dn_impressions 
where domain_name(`domain_id`)=? group by `date`;



";


SET @DOMAIN_NAME=DOMAIN_NAME;
EXECUTE STMT USING @DOMAIN_NAME;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;

-- call nstock_stats_get_impressions_by_day('mycooldomain.com');