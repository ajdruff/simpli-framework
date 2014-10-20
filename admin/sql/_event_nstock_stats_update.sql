-- ----------------------------------------
-- Event `nstock_stats_update`
-- Moves old click and impression stats to the history tables
-- call `nstock_update_ticker_status`;
-- To Start event processing, SET GLOBAL event_scheduler = On;
-- Dont forget to drop the event before you recreate it
-- ----------------------------------------
delimiter |
DROP EVENT IF EXISTS nstock_stats_update|


CREATE EVENT nstock_stats_update
    ON SCHEDULE
      EVERY 4 HOUR
	STARTS '2013-11-08 21:15:00'
    ON COMPLETION PRESERVE
    ENABLE
    COMMENT 'Moves Older Click Stats to History'
    DO
      BEGIN

-- Move old click and impression stats to the history tables
call `nstock_stats_move_to_history`;

-- update nstock_domains with latest stats
update nstock_domains
set `total_clicks` = get_total_clicks(CONCAT(`nstock_domains`.`subdomain`, '.',`nstock_domains`.`tld`)),
`total_impressions` = get_total_impress(CONCAT(`nstock_domains`.`subdomain`, '.',`nstock_domains`.`tld`));


      END |


delimiter ;

--call `nstock_stats_copy_click_stats_to_history`();
-- call `nstock_stats_purge_old_click_stats`();
