-----------------------------------
-- Front End Queries
-- These are the queries that should be used in php code.
-- If possible push as much as you can into stored procedures,
-- so as to minimize errors when updating code.
-----------------------------------


-----------------------------------
-- Front End Query
-- Total Clicks for Today's date by domain, Ranked from highest to lowest
-- 
-----------------------------------
SELECT   `domain`, sum(`unique_count`) as `total`
FROM    view_nstock_stats_dn_clicks_today
GROUP BY `date`,`domain`
having `date`=date(now())


-----------------------------------
-- Front End Query
-- Total Clicks for Today's date For a Given domain
-- 
-----------------------------------

SELECT   `domain`, `date`,sum(`unique_count`) as `total`
FROM    view_nstock_stats_dn_clicks_today
GROUP BY `date`,`domain`
having `date`=date(now())
and `domain`='my-really-cool-domain.com'


//alternate using the raw sessions table
SELECT   `domain`, count(*) as `total`,date(`time_added`) as `date`
FROM    nstock_stats_dn_clicks_session
GROUP BY `date`,`domain`
having `date`=date(now())
and `domain`='my-really-cool-domain.com'




-----------------------------------
-- Front End Query
-- Clicks Per Hour for today's date for a given domain
-----------------------------------

	Select domain,date,hour, unique_count 
	From view_nstock_stats_dn_clicks_today 
	where date=date(now()) 
        and domain='my-really-cool-domain.com'

-----------------------------------
-- Front End Query
-- Clicks Per Hour for dates in the past
-----------------------------------
	Select `hour`, `unique_count` 
	From nstock_stats_dn_clicks_history
	Where date(`date`)='2014-02-07' and domain_name(`domain_id`)='mycooldomain.com'


-----------------------------------
-- Front End Query
-- Historical Clicks by Day for a Given Domain
-----------------------------------
	Select date(`date`) as date, `unique_count` 
	From nstock_stats_dn_clicks_history
	where domain_name(`domain_id`)='mycooldomain.com'
group by `date`;
-----------------------------------
-- Impression Reports
-----------------------------------
-----------------------------------
-- REPORT='total-for-today-all-domains'
-- Total Impressions for Today, for all domains
-----------------------------------



-----------------------------------
-- REPORT='total-for-today'
-- Total Impressions for Today for a Given Domain Name
-----------------------------------

Select domain_name(`domain_id`) as `domain_name`,`date`,count from nstock_stats_dn_impressions 
where domain_name(`domain_id`)='mycooldomain.com'
and date=date(now());

-----------------------------------
-- REPORT='total-for-date' 
-- Total Impressions for a Given Date For a Given Domain
-----------------------------------


Select domain_name(`domain_id`) as `domain_name`,`date`,count from nstock_stats_dn_impressions 
where domain_name(`domain_id`)='mycooldomain.com'
and date='2014-02-11';
-----------------------------------
-- Front End Query
-- Historical Impressions by Day for a Given Domain
-----------------------------------
Select domain_name(`domain_id`) as `domain_name`,`date`,count from 
nstock_stats_dn_impressions 
order by `domain_name`,`date`
where domain_name(`domain_id`)='mycooldomain.com';
-----------------------------------
-- Front End Query
-- Domain with Most Impressions on a single day
-----------------------------------

-- max impressions on a single day
SELECT `date`,domain_name(`domain_id`) as `domain_name`,`count` FROM nstock_stats_dn_impressions WHERE `count` IN (SELECT max(`count`) From nstock_stats_dn_impressions);



-- impressions per day ( with duplicate domain names ) ranked highest to lowest
-- or without the limit , max impressions on a single day by any domain name
--alternately  
SELECT `date`,domain_name(`domain_id`) as domain_name,MAX(count) AS count
    FROM nstock_stats_dn_impressions
    GROUP BY date,domain_name
Order By Count DESC
limit 1


- another way of getting the domain that got the most impressions on a single day
SELECT `domain_id`,domain_name(`domain_id`),`date`,`count`
FROM   nstock_stats_dn_impressions
WHERE  count=(SELECT MAX(count) FROM nstock_stats_dn_impressions);

-----------------------------------
-- Front End Query
-- Single Day Maximums for Each Domain, Ranked Highest to Lowest
-- Shows the highest number of daily impressions received for each domain
-----------------------------------
-- this is a  'groupwise maximum' type query, and is not as easy as it would seem
-- check out stackoverflow for more solutions
-- this one worked best for me 
-- ref: user comment Kasey Speakman https://dev.mysql.com/doc/refman/5.0/en/example-maximum-column-group-row.html
SELECT domain_name(`domain_id`) as `domain_name`,s.*
FROM (SELECT *
FROM nstock_stats_dn_impressions
-- [WHERE conditions]
ORDER BY count DESC) AS s
GROUP BY domain_name
Order By count DESC



-----------------------------------
-- 'grand-total-all-domains' 
-- Cumulative Daily Totals For Each Domain, Ranked from highest to lowest
-- It takes each domain, totals all the impressions it ever recieved, and sorts them against the others
-----------------------------------
SELECT   domain_name(`domain_id`) as `domain_name`, sum(`count`) as `total`
FROM     nstock_stats_dn_impressions
GROUP BY `domain_name`
ORDER BY `total` DESC



-----------------------------------
-- Front End Query
-- Increases the click counter
-----------------------------------

Insert into nstock_stats_dn_clicks_session 
(`session_id`,`domain`,`time_added`)
VALUES('mysession2-14-0001',(SELECT `id` FROM `nstock_domains` where `on_ticker`='y' and domain_name(`id`)='mycooldomain.com'),NULL)

-- convert domains to their domain ids for those on the current ticker only (won't work if ticker has rotated)

Update  nstock_stats_dn_clicks_session sessions 
set `sessions`.`domain`=(select `id`  FROM `nstock_domains` 
where `on_ticker`='y' and domain_name(`id`)=sessions.`domain`);


Update  nstock_stats_dn_clicks_history history
set `history`.`domain`=(select `id`  FROM `nstock_domains` 
where `on_ticker`='y' and domain_name(`id`)=`history`.`domain`);


 