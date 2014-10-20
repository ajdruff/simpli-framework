DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_add_random_inventory_to_ticker`$$

CREATE PROCEDURE `nstock_add_random_inventory_to_ticker`()
-- ----------------------------------------
-- Randomly selects a domain name from inventory that isn't already
-- active on the ticker and inserts it, approved, into the ticker.
-- Intended to run as a job periodically so I always keep my 
-- inventory on the ticker.
-- call `nstock_add_random_inventory_to_ticker`;
-- ----------------------------------------

BEGIN


-- inserts a random name from the inventory that is not yet listed.
INSERT INTO nstock_domains
(
subdomain
, tld
, bin
, bid
, price
, currency
, time_added
, time_approved
, featured
, seller
, approved
, list_status
, `source`
, added_by

) 
SELECT 
b.subdomain
, b.tld
, b.bin
, b.bid
, b.price
, b.currency
, UTC_TIMESTAMP -- time_added
, UTC_TIMESTAMP -- time_approved
,'n'
,1
, 'y'
,'pending'
,'member_inventory'
,1
from 
nstock_domains a
right join 
nstock_inventory b
on concat(a.subdomain,'.',a.tld)=concat(b.subdomain,'.',b.tld)
where a.list_status != 'active' or a.list_status IS NULL
and b.seller=1
order by RAND()
LIMIT 1;



    END$$

DELIMITER ;


-- call `nstock_add_random_inventory_to_ticker`;


