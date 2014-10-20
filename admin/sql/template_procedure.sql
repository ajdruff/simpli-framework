DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_name_of_procedure`$$

CREATE PROCEDURE `nstock_name_of_procedure`()
BEGIN
-- Some Comment
PREPARE STMT FROM "

-- add SQL here

";



EXECUTE STMT;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;
