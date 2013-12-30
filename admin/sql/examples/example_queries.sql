
--- Combine two fields
select  concat(nstock_domains.name,'.',tld) as 'Domain Name' from nstock_domains where approved='y'


-- update
update `simpli_wp_dev`.`nstock_domains` as domains
set bid='y'



-- interval

select  concat(nstock_domains.name,'.',tld) as 'domain_name',bin,bid,price from nstock_domains  WHERE nstock_domains.time_added >= DATE_SUB(NOW(), INTERVAL 24 HOUR) and  approved='y' and featured='n'



-- convert time zones
SELECT CONVERT_TZ(time_added,'+00:00','-08:00') from nstock_domains;
Select CONVERT_TZ(time_added,'+00:00','-08:00') as `time_added_local`, domain_listing.* from domain_listing
order by time_added_local DESC
; 

--- list domains within an interval but outside that interval if you dont have enough 

select  DISTINCT nstock_domains.name as 'Domain Name' ,nstock_domains.time_added,(nstock_domains.time_added >= DATE_SUB(NOW(), INTERVAL 2 HOUR)) as 'interval' from nstock_domains 
ORDER BY (nstock_domains.time_added >= DATE_SUB(NOW(), INTERVAL 24 HOUR)),nstock_domains.time_added DESC Limit 0,100


-- insert
INSERT INTO simpli_wp_dev.nstock_domains
(name, tld, bin, bid, price, currency, time_added, time_expired, featured, seller, approved, time_lastupdated, time_approved, reg_available, time_list_start, time_list_stop, list_status, not_listed_reason, price_note, source, added_by) 
VALUES (
'test',   -- name
'com', -- bin
'y',  -- bin
'n',  -- bid
'999',  -- price
'usd',  -- currency
now(),  -- time added null or now() will work
'NULL()',  -- time_expired
'n',  -- featured
'1',  -- seller
'y',  -- approved
'', -- time_last_updated , leave empty string . This will leave you with 00 which tells you it was never edited. Upon update, use null or now()
now(), -- time approved  If want approved at same timea as insert, use null or now() without quotes
'n',  -- reg_available
now(),  -- time_list_start Set to now() at time of approval or when you want to start the listing. Null wont work because it doesnt have the not null property set.
null,  -- time_list_stop Set to now() when listing stops.
'pending',  -- listed_status
null, -- not_listed_reason 
null,  -- price note
'member_inventory',  -- source
'1' -- added_by
); 



-- delete using a string comparison
DELETE from nstock_event_logs
where log NOT LIKE '%Last Listing Update%';


INSERT INTO nstock_event_logs
(log,time_added)
Values ('log',now());

Update nstock_event_logs
set log='Last Listing Update'
where log='log';