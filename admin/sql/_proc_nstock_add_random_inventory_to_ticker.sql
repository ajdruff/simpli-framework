
-- inserts all the inventory into nstock_domains
INSERT INTO nstock_domains
(
subdomain
, `tld`
, `bin`
, `bid`
, `price`
, `currency`
, `time_added`
, `time_approved`
, `featured`
, `seller`
, `approved`
, `list_status`
, `source`
, `added_by`

) 
SELECT 
`subdomain`
, `tld`
, `bin`
, `bid`
, `price`
, `currency`
,  UTC_TIMESTAMP   -- time_added a Random time from now until 24 hours from now.
, from_unixtime(UNIX_TIMESTAMP(UTC_TIMESTAMP) + FLOOR(0 + (RAND() * 86400))) -- time_approved
,'n'
,1
, 'y'
,'pending'
,'member_inventory'
,1
from 
nstock_inventory
where `seller`=1
order by RAND();

-- notes
-- from_unixtime is necessary to convert seconds to a datetime format.
-- 86400 is the number of seconds in 24 hours

