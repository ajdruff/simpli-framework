DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_clicks_for_today`$$

CREATE PROCEDURE `nstock_stats_get_impressions_by_day`(DOMAIN_NAME VARCHAR(255))
BEGIN
-- Some Comment
PREPARE STMT FROM "
-- to use the paramater, refer to it by using a ?

Select `date`,count from 
nstock_stats_dn_impressions 
where domain_name(`domain_id`)=? group by `date`;

	Select `domain_id`,date(`date`),`hour`, `unique_count` 
	From nstock_stats_dn_clicks_history
	Where date(`date`)='2014-02-07' and `domain`='mydomain.com'

";


SET @DOMAIN_NAME=DOMAIN_NAME;
EXECUTE STMT USING @DOMAIN_NAME;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;

-- call nstock_stats_get_impressions_by_day('mycooldomain.com');