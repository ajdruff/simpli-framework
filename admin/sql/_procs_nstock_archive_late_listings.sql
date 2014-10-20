DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_archive_late_listings`$$

CREATE PROCEDURE `nstock_archive_late_listings`(MAX_LISTINGS INT)
BEGIN
PREPARE STMT FROM "
Update nstock_domains domains
LEFT JOIN
(
Select * 
from nstock_domains
where nstock_domains.list_status='active'
ORDER By time_added ASC LIMIT ?
) active
on domains.id=active.id
set domains.not_listed_reason='queue'
where active.id is NULL
";

SET @MAX_LISTINGS=MAX_LISTINGS;
EXECUTE STMT USING @MAX_LISTINGS;
    END$$

DELIMITER ;