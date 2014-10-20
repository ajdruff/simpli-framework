

DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_activate_approved_listings`$$

CREATE PROCEDURE `nstock_activate_approved_listings`(LIST_TIME_IN_HOURS INT)
BEGIN
-- Activate Newly Approved
PREPARE STMT FROM "
UPDATE nstock_domains AS domains
JOIN (SELECT  mo.`Domain Name`,mo.`Approved Within Interval`,mi.*
FROM    (
SELECT  id, nstock_domains.domain_name AS 'Domain Name' ,
nstock_domains.time_added,
(nstock_domains.time_approved >= DATE_SUB(NOW(), INTERVAL ? HOUR)) AS 'Approved Within Interval' ,
MAX(id) AS mid
        FROM    nstock_domains
WHERE list_status='pending' AND approved='y'
        GROUP BY `Domain Name` 
        ) mo
JOIN    nstock_domains mi
ON      mi.id = mo.mid
)
as unapproved
ON domains.id=unapproved.id
set domains.list_status='active';";


SET @LIST_TIME_IN_HOURS=LIST_TIME_IN_HOURS;
EXECUTE STMT USING @LIST_TIME_IN_HOURS;
DEALLOCATE PREPARE STMT;
    END$$

DELIMITER ;