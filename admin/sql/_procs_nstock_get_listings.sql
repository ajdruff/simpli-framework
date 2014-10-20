DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_get_listings`$$

CREATE PROCEDURE `nstock_get_listings`(MAX_LISTINGS INT)
BEGIN
PREPARE STMT FROM "
Select * from view_domain_listings Limit 0,?
";
SET @LIST_MAX=MAX_LISTINGS;
EXECUTE STMT USING @LIST_MAX;
    END$$

DELIMITER ;