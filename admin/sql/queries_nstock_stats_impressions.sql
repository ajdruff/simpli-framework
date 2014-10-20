-- use this time for testing: 2014-02-7 07:27:20
-- Queries Used For Stats


-- [View_Query_Stats_Today] - Shows Click Stats for Today
SELECT domain,time_added,date(time_added) AS date,hour(time_added) AS hour ,COUNT(id) AS `unique_count`
FROM nstock_stats_dn_clicks_session
where date(time_added)=date(now())
GROUP BY domain,hour;
-- [Front_End_Query_Stats_Today] - Shows Click Stats for Today
select * from view_nstock_stats_dn_clicks_today;
-----------------------------------------------------------------------------------
-- Job_Move Older Stats to History (Older than 1 day)
---------------------------------------------------------------------------------
-- [Job_Copy_Click_Stats_to_History] - Copies stats from the dn_clicks_session table to the history table
-- step 1 - Insert older stats into history table  
-- Job to Run Every 1 Hour. insert into nstock_stats_dn_clicks_history all those records more than 1 days old.
Insert into nstock_stats_dn_clicks_history (`domain`,`unique_count`,`date`,`hour`)  
SELECT `domain`,COUNT(nstock_stats_dn_clicks_session.id) AS `unique_count`,date(nstock_stats_dn_clicks_session.time_added) as `date`,hour(time_added) as `hour` 
FROM nstock_stats_dn_clicks_session
where date(time_added)<DATE_SUB(DATE(NOW()) , INTERVAL 1 DAY)
GROUP BY domain,hour
;

-- [Dashboard_Query_Show_Click_Stats_To_Be_Deleted_From_Sessions]
-- Informational Query - View all the stats that will be deleted in step 2
-- Find all all the ids of records which have already been moved to history
-- In English: Get all ids whose combined date and domain fields have records within the history table. 
-- Once you know the ids, you can delete the records from the recent 
select `nstock_stats_dn_clicks_session`.`id` from nstock_stats_dn_clicks_session 
where trim(concat(`domain`, date(`time_added`)))  in (
select  trim(concat(domain, date(`date`))) from nstock_stats_dn_clicks_history 
)

-- [Job_Delete_Older_Click_Stats_From_History]
-- step 2 - Delete the stats that exist in the history table from the sessions table
delete from nstock_stats_dn_clicks_session  
where trim(concat(`domain`, date(`time_added`)))  in (
select  trim(concat(domain, date(`date`))) from nstock_stats_dn_clicks_history 
)



-- ==============================
-- Domains appearing on front page (this must match any limitations used by code)
-- ==============================
Select id,date(now() ) from view_domain_listings

    

-- ==============================
-- Impressions
-- ==============================
-- Get a list of all domains being shown on the ticker and insert their ids into the impressions table
-- Need to join the list with the impressions table and look for domains that are active today. any new 
-- insertions will use today's date


--- Update nstock tracking as to which domains are actually on the ticker

-- reset the nstock_domains table 

-- ========================
-- Queries Used in _procs_nstock_update_ticker_status.sql
-- For most up to date query, please see the procedure
--# ========================
--#
--# -- first, update domains that are not appearing on ticker 
update nstock_domains                                   -- #
set `on_ticker`='n'                                     -- #                                   
where `id` NOT in                                       -- #
(select `id` from view_domain_listings);                -- #


-- then update those that are
update nstock_domains 
set `on_ticker`='y'
where `id` in
(select `id` from view_domain_listings);

-- ========================
-- Queries Used in _procs_nstock_update_impressions.sql
-- For most up to date query, please see the procedure
-- ========================
-- finally, insert ticker domains onto impressions table with today's date.
-- the IGNORE keyword allows us to observe the constraint placed with the domain_date Unique Key,
-- but still continue with the rest of the inserts. in other words, if the 
-- group of inserts included adding a record that violated the unique constraint, 
-- normally, the entire batch insert would fail. By using the IGNORE keyword, we allow
-- the inserts that don't violate the constraint to proceed.
insert IGNORE into nstock_stats_dn_impressions(`domain_id`,`date`) 
select `id`,date(now()) from nstock_domains where `on_ticker`='y';

-- ========================
-- _procs_nstock_make_an_impression.sql
-- For most up to date query, please see the procedure
-- ========================

-- update Nomstock Ticker Impressions
update nstock_stats_dn_impressions set `count`=`count`+1 where `domain_id` in
(select `id` from nstock_domains where `on_ticker`='y')



-- Unique Indexes
-- We also create a unique index on domain and date
CREATE UNIQUE INDEX `domain_date` ON `nstock_stats_dn_impressions`(`domain_id`, `date`);

CREATE UNIQUE INDEX `session_domain` ON `nstock_stats_dn_impress_session`(`session_id`, `domain_id`);
CREATE UNIQUE INDEX `session_domain` ON `nstock_stats_dn_clicks_session`(`session_id`, `domain`);





