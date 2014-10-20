DELIMITER $$

DROP PROCEDURE IF EXISTS `test_parms`$$

CREATE PROCEDURE `test_parms`(REPORT VARCHAR(255),DOMAIN_NAME VARCHAR(255))
BEGIN

SET @sql = "Select @DOMAIN_NAME,@REPORT";

PREPARE stmt FROM @sql;
EXECUTE stmt;

DEALLOCATE PREPARE stmt;

END$$

DELIMITER ;


show grants;

-- -- call test_parms('my report','example.com');
-- without preparing it.

DELIMITER $$
DROP PROCEDURE IF EXISTS `test_parms`$$

CREATE PROCEDURE `test_parms`(REPORT VARCHAR(255),DOMAIN_NAME VARCHAR(255))
BEGIN

execute "Select DOMAIN_NAME,REPORT";

select @result;

END$$

DELIMITER ;



		DELIMITER //
		
DROP PROCEDURE IF EXISTS `test_parms`//
		-- REPORT VARCHAR(255),
		CREATE PROCEDURE `test_parms`(REPORT VARCHAR(255),DOMAIN_NAME VARCHAR(255))
		BEGIN

SET @sql = "Select @DOMAIN_NAME,@REPORT";

		
		
		SET @DOMAIN_NAME=DOMAIN_NAME;
		SET @REPORT=REPORT;
		PREPARE stmt FROM @sql;
		EXECUTE STMT;
		DEALLOCATE PREPARE STMT ;
		    END//
		
		DELIMITER ;
		

-- call nstock_stats_get_report('my report','mycooldomain.com');

