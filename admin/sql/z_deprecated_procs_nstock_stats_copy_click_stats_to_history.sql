DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_copy_click_stats_to_history`$$

CREATE PROCEDURE `nstock_stats_copy_click_stats_to_history`()
BEGIN
-- [Job_Copy_Today_Stats_To_History] - Copies stats from the dn_clicks_session table to the history table
-- step 1 - Insert older stats into history table  
-- Job to Run Every 1 Hour. insert into nstock_stats_dn_clicks_history all those records more than 1 days old.
SET @sql="


Insert into nstock_stats_dn_clicks_history (`domain_id`,`unique_count`,`date`,`hour`)  
SELECT `domain_id`,COUNT(nstock_stats_dn_clicks_session.id) AS `unique_count`,date(nstock_stats_dn_clicks_session.time_added) as `date`,hour(time_added) as `hour` 
FROM nstock_stats_dn_clicks_session
where date(time_added)<DATE_SUB(DATE(NOW()) , INTERVAL 1 DAY)
GROUP BY domain_id,hour

";


PREPARE stmt FROM @sql;
EXECUTE STMT;
DEALLOCATE PREPARE STMT ;
    END$$

DELIMITER ;
