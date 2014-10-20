DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_purge_old_click_stats`$$

CREATE PROCEDURE `nstock_stats_purge_old_click_stats`()
BEGIN
-- [Job_Purge_Old_Click_Stats]
-- step 2 - Delete the stats that exist in the history table from the sessions table
PREPARE STMT FROM "

delete from nstock_stats_dn_clicks_session  
where trim(concat(`domain_id`, date(`time_added`)))  in (
select  trim(concat(domain_id, date(`date`))) from nstock_stats_dn_clicks_history 
)

";



EXECUTE STMT;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;

-- call `nstock_stats_purge_old_click_stats`();