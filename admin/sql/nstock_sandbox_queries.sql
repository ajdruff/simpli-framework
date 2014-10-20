-- WIP Queries
-- Work In Progress. Use this as a sandbox

---------------------------------------------------
-- 1.0S.1
-- Selects Approved But Not Yet Active
-- modified to be simpler
-- Duplicates are grouped and joined so that only the latest record from a group of duplicates is used. http://stackoverflow.com/a/1299598
---------------------------------------------------

SELECT  'Approved Not Active',all_listings.*
FROM    (
-- select the latest of each record that is pending and approved
select  
MAX(id) AS max_id
        FROM    nstock_domains
where list_status='pending' and approved='y'
        GROUP BY domain_name(id)
 ) pending_and_approved
JOIN    nstock_domains all_listings -- now join it with the main table to get the other fields
ON      all_listings.id = pending_and_approved.max_id;




--insert test to test triggers
-- INSERT INTO `nstock_domains`(`id`,`domain_name_tg`,`subdomain`,`tld`,`bin`,`bid`,`price`,`currency`,`time_added`,`featured`,`seller`,`approved`,`time_lastupdated`,`time_approved`,`reg_available`,`time_list_start`,`time_list_stop`,`list_status`,`not_listed_reason`,`price_note`,`source`,`added_by`,`concat_test`) VALUES (null,null,'test6','com','y','n','885','USD',null,null,'n','1','n',null,null,'n',null,null,'pending',null,null,'member_inventory','1',null);










select 'PENDING',domain_name(id) as domain_name from nstock_domains where nstock_domains.list_status='pending'
ORDER BY ID DESC;

Select 'ACTIVE' , nstock_domains.* from nstock_domains where list_status='active' order by id DESC;




-- unknown column: check that the column is within the select statement
-- syntax error: check that there is only one semicolon and that its at the end. make sure there is a semicolon for each complete statement on the page.
-- syntax error on update: make sure the where clause is after the set clause and the semicolon is at the end.
-- unknown column 'table_name' - if you use an alias, make sure you are using the alias consistently (dont also use the table name)
-- alias should come after the From statement or after the Join grouping
-- syntax error on update: make sure no group by clause
-- syntax error on update with multiple set statements. update can only have one set statement - use a comma instead of the word 'set'



UPDATE nstock_domains
SET nstock_domains.price_note = t.price_note
WHERE nstock_domains.id IN
(
select id from (
  SELECT * from nstock_domains
) as t
)