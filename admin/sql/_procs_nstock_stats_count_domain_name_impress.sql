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




-- what seems to work best is no transaction and deallocate or no transaction and no . but even then its very slow at times.
START TRANSACTION;
-- SET @DOMAIN_NAME=DOMAIN_NAME;
-- SET @SESSION_ID=SESSION_ID;
-- SELECT `view_domain_listings`.`domain_name`,`view_domain_listings`.`id` FROM `view_domain_listings` where `view_domain_listings`.`domain_name`=DOMAIN_NAME;
Insert  into nstock_stats_dn_impress_session (`session_id`,`domain_id`,`date_added`,`time_added`)
VALUES(SESSION_ID,(SELECT `id` FROM `nstock_domains` where `nstock_domains`.`on_ticker`='y' and CONCAT(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)=DOMAIN_NAME),date(now()),NULL);
-- VALUES(SESSION_ID,(SELECT `view_domain_listings`.`id` FROM `view_domain_listings` where `view_domain_listings`.`domain_name`=DOMAIN_NAME),date(now()),NULL);

COMMIT;

    END$$

DELIMITER ;

-- call nstock_stats_count_domain_name_impress('my_cool_session4','ugamr.com')
call nstock_stats_count_domain_name_impress(MD5(RAND()),'faqqs.com')

