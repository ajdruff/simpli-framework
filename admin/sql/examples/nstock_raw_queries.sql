-- these are the base queries that I built the procedures from.
-- they may not be up to date, so always use the proc_sql files in production











---------------------------------------------------
-- .5AS
-- Lists updates that have been submitted for listings that are already active

---------------------------------------------------

  SELECT 'Updated',domain_name(active.id) AS domain_name,active.*
FROM  nstock_domains active
 JOIN  (
       -- select the latest of each record that is pending and approved
       SELECT MAX(ID) as max_id ,domain_name(id) AS domain_name,added_by,`source`
        FROM   nstock_domains 
        WHERE  list_status = 'pending'
group by domain_name
        ) update_candidate
 ON  `update_candidate`.`domain_name`=domain_name(active.id) 
where active.list_status='active' 
and domain_name(active.id)=update_candidate.domain_name
and active.added_by=update_candidate.added_by
and ( (active.`source`='member_inventory'
and `update_candidate`.`source` ='member_inventory')
or (
 active.`source`!='member_inventory'
)
)



---------------------------------------------------
-- .5AU
-- Changes status to 'pending_update' for those listings that are active
-- but have a change submitted.
-- This query joins new 'pending' listings with the active listings
-- to find matches, and when it does, it changes the setatus of the active listing
-- to 'pending_update'
-- it will only work if the update was submitted by the owner or if the 
-- original source was not member_inventory
---------------------------------------------------


update nstock_domains active
 JOIN  (
       -- select the latest of each record that is pending and approved
       SELECT MAX(ID) as max_id ,domain_name(id) AS domain_name,added_by,`source`
        FROM   nstock_domains 
        WHERE  list_status = 'pending'
group by domain_name
        ) update_candidate
 ON  `update_candidate`.`domain_name`=domain_name(active.id) 
set active.list_status='pending_update'
where active.list_status='active' 
and domain_name(active.id)=update_candidate.domain_name
and active.added_by=update_candidate.added_by
and ( (active.`source`='member_inventory'
and `update_candidate`.`source` ='member_inventory')
or (
 active.`source`!='member_inventory'
)
)


---------------------------------------------------
-- .5BU
-- Activates a change submitted for an active record.
--  Makes the changes immediately active and approved, with the same start
-- time as the previously active (old) record.
---------------------------------------------------
-- Activate the new record and archive the old. Update the new record's start time with the value from the one it replaced
update nstock_domains new_record
 JOIN  (
       -- select all domains that are pending updates
       SELECT id, domain_name(id) AS domain_name,added_by,`source`,`time_list_start`
        FROM   nstock_domains 
        WHERE  list_status = 'pending_update'
        ) old_record
 ON  `old_record`.`domain_name`=domain_name(new_record.id)
set `new_record`.`list_status`='active'
,`new_record`.`approved`='y'
,`new_record`.`time_list_start`=`old_record`.`time_list_start`;

---------------------------------------------------
-- .5CU
-- Archive Old Records that have been replaced by changes
---------------------------------------------------
update nstock_domains
set nstock_domains.list_status='archived'
where nstock_domains.list_status='pending_update'



----------------------
-- .6U
-- Archive any remaining pending changes that match active listings.
-- This should come immediately after the valid changes have been activated

update nstock_domains invalid_updates
 JOIN  (
       -- select all domains that are pending updates
       SELECT id, domain_name(id) AS domain_name,added_by,`source`,`time_list_start`
        FROM   nstock_domains 
        WHERE  list_status = 'active'
        ) active
 ON  `active`.`domain_name`=domain_name(invalid_updates.id)
set `invalid_updates`.`list_status`='archived'
,`invalid_updates`.`approved`='n'

-------------

---------------------------------------------------
-- .9S
-- List All Due to Expire

-- Selects all those that are active but should now be expired
---------------------------------------------------
Select *
from nstock_domains
where nstock_domains.time_list_start < DATE_SUB(NOW(), INTERVAL 24 HOUR) and nstock_domains.list_status='active'


---------------------------------------------------
-- .9U
-- Expire Active

-- Expires all those that are active but should now be expired
---------------------------------------------------
Update nstock_domains
set nstock_domains.time_list_stop=now()
,nstock_domains.list_status='archived'
where nstock_domains.time_list_start < (DATE_SUB(NOW(), INTERVAL 24 HOUR)) and nstock_domains.list_status='active'

---------------------------------------------------
-- 1.0S
-- Selects Approved But Not Yet Active

-- Duplicates are grouped and joined so that only the latest record from a group of duplicates is used. http://stackoverflow.com/a/1299598
---------------------------------------------------

SELECT  mo.`Domain Name`,mo.`Approved Within Interval`,mi.*
FROM    (
select  id, concat(nstock_domains.subdomain,'.',tld) as 'Domain Name' ,
nstock_domains.time_added,
(nstock_domains.time_approved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) as 'Approved Within Interval' , 
MAX(id) AS mid
        FROM    nstock_domains
where list_status='pending' and approved='y'
        GROUP BY `Domain Name`
ORDER BY (`Approved Within Interval`) DESC ,nstock_domains.time_added DESC Limit 100 ) mo
JOIN    nstock_domains mi
ON      mi.id = mo.mid

---------------------------------------------------
-- 1.0S.1
-- Selects Approved But Not Yet Active
-- modified to be simpler
-- Duplicates are grouped and joined so that only the latest record from a group of duplicates is used. http://stackoverflow.com/a/1299598
---------------------------------------------------

SELECT  all_listings.*
FROM    (
-- select the latest of each record that is pending and approved
select  id, concat(nstock_domains.subdomain,'.',tld) as 'Domain Name' ,
nstock_domains.time_added,
(nstock_domains.time_approved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) as 'Approved Within Interval' , 
MAX(id) AS max_id
        FROM    nstock_domains
where list_status='pending' and approved='y'
        GROUP BY `Domain Name`
 ) pending_and_approved
JOIN    nstock_domains all_listings -- now join it with the main table to get the other fields
ON      all_listings.id = pending_and_approved.max_id








---------------------------------------------------
-- 1.0U
-- Activate Newly Approved

-- same query as 1.0S but as an update
-- Select all domains not yet approved 
-- Duplicates are grouped and joined so that only the latest record from a group of duplicates is used. http://stackoverflow.com/a/1299598
-- Changes pending approved list_status from pending to 'active' 

---------------------------------------------------

UPDATE nstock_domains AS domains
JOIN (SELECT  mo.`Domain Name`,mo.`Approved Within Interval`,mi.*
FROM    (
SELECT  id, concat(nstock_domains.subdomain,'.',tld) AS 'Domain Name' ,
nstock_domains.time_added,
(nstock_domains.time_approved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) AS 'Approved Within Interval' ,
MAX(id) AS mid
        FROM    nstock_domains
WHERE list_status='pending' AND approved='y'
        GROUP BY `Domain Name` 
-- not needed ORDER BY (`Approved Within Interval`) DESC ,nstock_domains.time_added DESC
        ) mo
JOIN    nstock_domains mi
ON      mi.id = mo.mid
)
as unapproved
ON domains.id=unapproved.id
set domains.list_status='active';


---------------------------------------------------
-- Query 1.1S
-- List Obsolete Duplicates 

-- Remove all those domain names where someone added 
-- multiple times but only the latest was accepted.
-- It does this by selecting 
--  those domain names that appear both in
-- 'pending' and 'active' and archives those that are pending
-- since the same name is active. 
-- We use a sql join to do this
---------------------------------------------------
Select *
from nstock_domains  as domains
inner join
(
Select *
from nstock_domains 
where list_status='active'
) active
on concat(domains.subdomain,'.',domains.tld)=concat(active.subdomain,'.',active.tld)
where domains.list_status='pending'
ORDER BY domains.ID DESC


---------------------------------------------------
-- Query 1.1U 
-- Flag Dupes So They will be ignored

-- same result set as 1.1S 
-- Does an update of list_status='dupe'

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



------

-- 1.01S 
-- Archive Late Submissions

-- Now archive all those that do not make the limit, based on when they were added.
Select domains.id as 'domains.id',active.id as 'active.id', domains.*
from nstock_domains domains
LEFT JOIN
(
Select * 
from nstock_domains
where nstock_domains.list_status='active'
ORDER By time_added ASC LIMIT 100) active
on domains.id=active.id
where active.id is NULL
---------------------------------------------------
-- 1.01U
-- Archive Late Submissions

-- same as 1.01S but now update them to not_listed
---------------------------------------------------
Update nstock_domains domains
LEFT JOIN
(
Select * 
from nstock_domains
where nstock_domains.list_status='active'
ORDER By time_added ASC LIMIT 1000
) active
on domains.id=active.id
set domains.not_listed_reason='queue'
where active.id is NULL


---------------------------------------------------
-- 1.02U
-- Record Listing Start Time

-- for all those new active records
---------------------------------------------------
Update nstock_domains
set time_list_start=now()
where list_status='active'
and time_list_start ='0000-00-00 00:00:00'







-- -----------------------------------
-- the listing query  
--  Query 1.2S
-- notes:
-- explicitly ordered active, then archived
-- within active and archive, ordered most recently to least recently added. This is to push the most recent domains up front, as well as to ensure that they start moving down the list with each sweep.
-- if we go with the earliest submitted first, we would see the same domains hang out at the top until their listing period is over.
-- the 24 hour interval is an FYI only, and can be removed from the final query as we dont use it for sorting, and the period is enforced by the expiration procedure
-- As far as I can tell, creating a stored procedure with MySQL does not support named paramaters, which means paramaters are used in the order you pass them, and they are only used once.
-- the join in the query is to screen out duplicates which can occur since we are including archived. Dupes should never occur in active because we screen
-- for dupes prior to activating
-- 
-- -------------------------------------

SELECT  domain_name(active.id) as `domain_name`,all_domains.*
FROM  nstock_domains all_domains
JOIN  (
select  domain_name(nstock_domains.id) as 'domain_name' ,nstock_domains.*,
MAX(nstock_domains.id) AS max_id
        FROM    nstock_domains
where nstock_domains.list_status='active' or nstock_domains.list_status='archived'
        GROUP BY `domain_name`) active
ON      all_domains.id = active.max_id
ORDER BY field(all_domains.list_status,'active','archived' ),all_domains.time_added DESC Limit 0,1000;

SELECT  domain_name(active.id) as `domain_name`,all_domains.*
FROM  nstock_domains all_domains
JOIN  `simpli_wp_dev`.`_view_domain_listings_with_dupes` active
ON      all_domains.id = active.max_id
ORDER BY field(all_domains.list_status,'active','archived' ),all_domains.time_added DESC Limit 0,1000;

select * from `simpli_wp_dev`.`_view_domain_listings_with_dupes`;


CREATE TABLE nstock_domain_listings AS (SELECT  domain_name(active.id) as `domain_name`,all_domains.*
FROM  nstock_domains all_domains
JOIN  (
select  domain_name(nstock_domains.id) as 'domain_name' ,nstock_domains.*,
MAX(nstock_domains.id) AS max_id
        FROM    nstock_domains
where nstock_domains.list_status='active' or nstock_domains.list_status='archived'
        GROUP BY `domain_name`) active
ON      all_domains.id = active.max_id
ORDER BY field(all_domains.list_status,'active','archived' ),all_domains.time_added DESC Limit 0,1000);

DROP VIEW IF EXISTS `simpli_wp_dev`.`domain_listings`;

CREATE
    /*[ALGORITHM = {UNDEFINED | MERGE | TEMPTABLE}]
    [DEFINER = { user | CURRENT_USER }]
    [SQL SECURITY { DEFINER | INVOKER }]*/
    VIEW `simpli_wp_dev`.`domain_listings` 
	AS 
		(SELECT * from table_temp2)

drop table table_temp2

-- Status Summary
select nstock_domains.list_status,count(nstock_domains.list_status) as 'Count' from nstock_domains
group by nstock_domains.list_status

