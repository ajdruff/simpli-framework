-- Dashboard - Frequently Used Queries when administering the Nomstock Ticker
-- Queries Useful for showing Status and Managing the Listings
-- Try to place only read only queries here so you can just hit query all to get all the panels updated
-- without worrying that you are deleting or changing anything


-- ---------------------------------------------------------------------- 
-- MySQL Job Scheduler
-- ----------------------------------------------------------------------
-- ----------------------------------------------------
-- Troubleshoot - run all these three so you get all the results in tabs
-- ----------------------------------------------------

-- show the logs as created by the Nomstock stored procedures
Select * from nstock_event_logs;

-- show whether event scheduler is turned on
SHOW VARIABLES LIKE 'event_scheduler';

-- show the events that are active
show events;


-- ----------------------------------------------------


-- Show Logs Showing When Listings Were Last Updated
Select * from nstock_event_logs;


-- Show MySQL Jobs
show events;

-- Turn Jobs on or off
SET GLOBAL event_scheduler = On;

-- SHOW SCHEDULER STATUS
SHOW VARIABLES LIKE 'event_scheduler';
or
select @@event_scheduler;




-- Show All Active
Select 'Active Report',
concat(`subdomain`,'.',`tld`) as domain_name,
TIMEDIFF(now(),
time_list_start) as 'Time Listed' ,
time_list_start,time_list_stop
from nstock_domains
where list_status='active';


-- Show Nomstock Ticker 
-- This shows those domains currently listed on the front page.
select * from view_domain_listings;


-- Show All Archived
Select 'Archived Report',
domain_name,time_added,time_list_start,time_list_stop,TIMEDIFF(time_list_start,time_list_stop) as 'Time Listed'
from domain_listing
where list_status='archived';



-- status
-- Status Summary
select 'Status Summary Report',nstock_domains.list_status,count(nstock_domains.list_status) as 'Count' from nstock_domains
group by nstock_domains.list_status;


-- pending
select 'Pending Report', nstock_domains.* from nstock_domains
where nstock_domains.list_status='pending';


-- Most Recent Logs
Select 'Most Recent Logs', 
nstock_event_logs.id,
nstock_event_logs.log,
CONVERT_TZ(time_added,'+00:00','-08:00') as 'Time'
from nstock_event_logs
order by id DESC LIMIT 500;

call nstock_get_listings(100);




-- watch current impressions



select * from nstock_stats_dn_impress_session 
order by `id` DESC limit 5 



--  remove a listing by disapproving it and archiving it. You must run both queries together since you dont want to disturb the values of the listings that are not active except to disapprove them.

set @`DOMAIN_TO_REMOVE`='fontmall.com';
	update nstock_domains  set `time_list_stop`=now(),`on_ticker`='n',`list_status`='archived' where CONCAT(`subdomain`,'.',`tld`) =@`DOMAIN_TO_REMOVE` and `list_status`='active';
	update nstock_domains  set `approved`='n' where CONCAT(`subdomain`,'.',`tld`) =@`DOMAIN_TO_REMOVE`;

-- remove a listing by deleting it entirely from the database
set @`DOMAIN_TO_REMOVE`='fontmall.com';
DELETE From nstock_domains  where CONCAT(`subdomain`,'.',`tld`) =@`DOMAIN_TO_REMOVE`;

