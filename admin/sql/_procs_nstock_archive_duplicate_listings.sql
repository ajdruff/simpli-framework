DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_archive_duplicate_listings`$$

CREATE PROCEDURE `nstock_archive_duplicate_listings`()
BEGIN
PREPARE STMT FROM "
update nstock_domains AS domains
inner join
(
Select *
from nstock_domains 
where nstock_domains.list_status='active'
) active
on concat(domains.subdomain,'.',domains.tld)=concat(active.subdomain,'.',active.tld)
set domains.list_status='dupe'
where domains.list_status='pending'
";
EXECUTE STMT;
    END$$

DELIMITER ;