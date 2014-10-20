DELIMITER $$
-- Counts a Viewport Impression by adding a record to dn_impress_session
DROP PROCEDURE IF EXISTS `nstock_stats_count_domain_name_impress`$$

CREATE  PROCEDURE `nstock_stats_count_domain_name_impress`(`SESSION_ID` VARCHAR(255),`DOMAIN_NAME` VARCHAR(255))
BEGIN
--  -------------------------------------------------------------------------------
-- nstock_stats_count_domain_name_impress
-- Increases the Impression count for the domain
-- call nstock_stats_count_domain_name_impress('my_cool_session5','mycooldomain.com')
-----------------------------------------------------------------------------------
SET @SQL="
-- to use the paramater, refer to it by using a question mark.
-- IGNORE is used because impressions more than one will generate a duplicate entry error due to the unique key constraints. if you want to see these errors, then remove the IGNORE keyword,
-- and you'll see it generate errors when debug is on.
Insert IGNORE into nstock_stats_dn_impress_session (`session_id`,`domain_id`,`date_added`,`time_added`)
VALUES(@SESSION_ID,(SELECT `id` FROM `nstock_domains` where `nstock_domains`.`on_ticker`='y' and CONCAT(`subdomain`,'.',`tld`)=@DOMAIN_NAME),date(now()),NULL);


";



-- what seems to work best is no transaction and deallocate or no transaction and no . but even then its very slow at times.
-- START TRANSACTION;
SET @DOMAIN_NAME=DOMAIN_NAME;
SET @SESSION_ID=SESSION_ID;
PREPARE STMT From @SQL;
EXECUTE STMT;
-- COMMIT;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;

-- call nstock_stats_count_domain_name_impress('my_cool_session4','ugamr.com')
call nstock_stats_count_domain_name_impress(MD5(RAND()),'ugamr.com')