DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_update_active_listings`$$

CREATE PROCEDURE `nstock_update_active_listings`()
BEGIN


-------------------
--- . 5A1U
--- Selects All Pending Dupes, does not include the latest one

/*
select * from nstock_domains all_records
LEFT JOIN
(
    SELECT MAX(id) AS id, domain_name(id) as domain_name
    FROM nstock_domains
    GROUP BY domain_name
) latest_record ON all_records.id = latest_record.id AND domain_name(all_records.id) = latest_record.domain_name
WHERE latest_record.id IS NULL
and all_records.list_status='pending';
*/

---------------------------------------------------
--- . 5A1U
-- Remove Pending Duplicates, keeping only the latest submission
---------------------------------------------------


/*removes duplicates from pending */
update nstock_domains all_records
LEFT JOIN
(
    SELECT MAX(id) AS id, domain_name(id) as domain_name
    FROM nstock_domains
    GROUP BY domain_name
) latest_record ON all_records.id = latest_record.id AND domain_name(all_records.id) = latest_record.domain_name
set all_records.list_status='archived'
WHERE latest_record.id IS NULL
and all_records.list_status='pending';





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
);


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
where nstock_domains.list_status='pending_update';


    END$$

DELIMITER ;