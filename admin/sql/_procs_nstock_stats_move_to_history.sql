DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_move_to_history`$$

CREATE PROCEDURE `nstock_stats_move_to_history`()
BEGIN
-- ----------------------------------------
-- Moves daily clicks and impressions to their history tables. 
-- Do not use this as a prepared statement since multiple statements will give you syntax errors.
-- Necessary to run so we have current history
-- call `nstock_stats_move_to_history`;
-- ----------------------------------------

-- ----------------------------------------
-- Copy today's data to history(clicks)
-- ----------------------------------------

Insert IGNORE into `nstock_stats_dn_clicks_history` (`domain_id`,`unique_count`,`date`,`hour`)  
SELECT `domain_id`,COUNT(nstock_stats_dn_clicks_session.id) AS `unique_count`,date(nstock_stats_dn_clicks_session.time_added) as `date`,hour(time_added) as `hour` 
FROM nstock_stats_dn_clicks_session
where date(time_added)<=DATE_SUB(DATE(NOW()) , INTERVAL 1 DAY)
GROUP BY domain_id,hour;




-- ----------------------------------------
-- Copy today's data to history(impressions)
-- ----------------------------------------

Insert IGNORE into `nstock_stats_dn_impress_history` (`domain_id`,`unique_count`,`date`,`hour`)  
SELECT `domain_id`,COUNT(nstock_stats_dn_impress_session.id) AS `unique_count`,date(nstock_stats_dn_impress_session.time_added) as `date`,hour(time_added) as `hour` 
FROM nstock_stats_dn_impress_session
where date(time_added)<=DATE_SUB(DATE(NOW()) , INTERVAL 1 DAY)
GROUP BY domain_id,hour;

-- ----------------------------------------
-- Delete the data we just moved from  today's tables (clicks)
-- ----------------------------------------
delete from nstock_stats_dn_clicks_session  
where trim(concat(`domain_id`, date(`time_added`)))  in (
select  trim(concat(domain_id, date(`date`))) from nstock_stats_dn_clicks_history 
);


-- ----------------------------------------
-- Delete the data we just moved from  today's tables (impressions)
-- ----------------------------------------
delete from nstock_stats_dn_impress_session  
where trim(concat(`domain_id`, date(`time_added`)))  in (
select  trim(concat(domain_id, date(`date`))) from nstock_stats_dn_impress_history 
);


Update nstock_event_logs 
set `time_added`=now()
where`log`='nstock_stats_move_to_history completed';


    END$$

DELIMITER ;

