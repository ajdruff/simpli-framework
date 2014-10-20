DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_impress_report`$$
-- REPORT VARCHAR(255),
CREATE PROCEDURE `nstock_stats_get_impress_report`(REPORT VARCHAR(255),DOMAIN_NAME VARCHAR(255), ON_DATE DATE)
BEGIN


SELECT domain_name(`domain_id`) as `domain_name`,`count`
FROM (SELECT *
FROM nstock_stats_dn_impressions
ORDER BY count DESC) AS s
GROUP BY `domain_id` -- dont try to group on domain_name, it wont work when in stored procedure
Order By count DESC;



END$$

DELIMITER ;


call nstock_stats_get_impress_report('daily-max',NULL,NULL); 