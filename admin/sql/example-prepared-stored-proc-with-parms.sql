DELIMITER //

DROP PROCEDURE IF EXISTS `test_parms`//

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

-- this is the final solution I posted to stack overflow