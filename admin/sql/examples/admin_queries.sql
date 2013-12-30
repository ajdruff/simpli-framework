-- Admin Queries
-- Queries Useful for showing Status and Managing the Listings
-- Try to place only read only queries here so you can just hit query all to get all the panels updated
-- without worrying that you are deleting or changing anything

-- Show All Active
Select 'Active Report',
domain_name,
TIMEDIFF(now(),
time_list_start) as 'Time Listed' ,
time_list_start,time_list_stop
from domain_listing
where list_status='active';

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