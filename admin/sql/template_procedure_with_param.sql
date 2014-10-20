DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_name_of_procedure`$$

CREATE PROCEDURE `nstock_name_of_procedure`(SOMEVAR INT)
BEGIN
-- Some Comment
PREPARE STMT FROM "
-- to use the paramater, refer to it by using a ?
";


SET @SOMEVAR=SOMEVAR;
EXECUTE STMT USING @SOMEVAR;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;
