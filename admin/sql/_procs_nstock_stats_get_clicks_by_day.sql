DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_clicks_by_day`$$

CREATE PROCEDURE `nstock_stats_get_clicks_by_day`(DOMAIN_NAME VARCHAR(255))
BEGIN
-- Some Comment
PREPARE STMT FROM "
-- to use the paramater, refer to it by using a ?

	Select date(`date`) as date, sum(`unique_count`) as `total` 
	From nstock_stats_dn_clicks_history
	where `domain_id` in (select `id` from nstock_domains where domain_name(`id`)=? ) group by date(`date`);


";


SET @DOMAIN_NAME=DOMAIN_NAME;
EXECUTE STMT USING @DOMAIN_NAME;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;

-- call nstock_stats_get_clicks_by_day('mycooldomain.com');