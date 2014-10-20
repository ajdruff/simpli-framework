DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_count_domain_name_click`$$

CREATE  PROCEDURE `nstock_stats_count_domain_name_click`(`SESSION_ID` VARCHAR(255),`DOMAIN_NAME` VARCHAR(255))
BEGIN
--  -------------------------------------------------------------------------------
-- nstock_stats_count_domain_name_click
-- Increases the click count for the domain
-- call nstock_stats_count_domain_name_click('my_cool_session5','mycooldomain.com')
-----------------------------------------------------------------------------------
SET @SQL="
-- to use the paramater, refer to it by using a question mark.
-- IGNORE is used because clicks more than one will generate a duplicate entry error because
-- of the unique key constraints. if you want to see these errors, then remove the IGNORE keyword,
-- and you'll it generate errors when debug is on.
Insert into nstock_stats_dn_clicks_session (`session_id`,`domain_id`,`date_added`,`time_added`)
VALUES(@SESSION_ID,(SELECT `id` FROM `nstock_domains` where `nstock_domains`.`on_ticker`='y' and CONCAT(`subdomain`,'.',`tld`)=@DOMAIN_NAME limit 1),date(now()),NULL);
-- VALUES(@SESSION_ID,(SELECT `id` FROM `view_domain_listings` where `domain_name`=@DOMAIN_NAME),date(now()),NULL);

";



-- START TRANSACTION;

SET @DOMAIN_NAME=DOMAIN_NAME;
SET @SESSION_ID=SESSION_ID;

PREPARE STMT From @SQL;
EXECUTE STMT;
-- COMMIT;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;


-- call nstock_stats_count_domain_name_click ('testsession','tshirtt.com')

-- call nstock_stats_count_domain_name_click('i551mptpvo37d993s56vdone44','faqqs.com') 

call nstock_stats_count_domain_name_click(MD5(RAND()),'faqqs.com')