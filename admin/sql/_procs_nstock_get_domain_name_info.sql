DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_get_domain_name_info`$$

-- --------------------------------------
-- Get Ticker Page
-- When called with 0, it returns the most recent results
-- When called with any number other than zero, it starts with that as the highest id, and 
-- returns listings in sequential order below it.
-- call nstock_get_ticker_page (0); 
-- --------------------------------------


CREATE PROCEDURE `nstock_get_domain_name_info`(`DOMAIN_NAME` VARCHAR(255))
BEGIN


select * from view_domain_listings where `domain_name`=DOMAIN_NAME;



-- PREPARE STMT FROM @sql;
-- EXECUTE STMT;

-- DEALLOCATE PREPARE STMT ;





END$$


DELIMITER ;

call nstock_get_ticker_page (0);

