DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_make_an_impression`$$

CREATE PROCEDURE `nstock_stats_make_an_impression`()
BEGIN
-- update Nomstock Ticker Impressions
-- This will increase the impressions by 1 of all those domains currently appearing 
-- on the Nomstock Ticker.
PREPARE STMT FROM "

update nstock_stats_dn_impressions set `count`=`count`+1 where `domain_id` in
(select `id` from nstock_domains where `on_ticker`='y')

";



EXECUTE STMT;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;
