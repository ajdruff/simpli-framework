DROP PROCEDURE IF EXISTS nstock_stats_make_a_viewable_impression;
CREATE PROCEDURE `nstock_stats_make_a_viewable_impression`(`DOMAIN_NAME` VARCHAR(255))
BEGIN

SET @sql = "update nstock_stats_dn_impressions 
set `viewport_count`=`viewport_count`+1
where domain_name(`domain_id`)=@DOMAIN_NAME
and `date`=date(now())";




SET @DOMAIN_NAME=DOMAIN_NAME;
PREPARE stmt FROM @sql;
EXECUTE STMT;
DEALLOCATE PREPARE STMT ;

    END;
