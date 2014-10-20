
-- goal: return lifetime clicks, impressions, and conversion rate
-- does not include todays. if no data is in history, then return today's . 

-- this should really be a report.

--todays' clicks
select `domain_id`,count(`id`) as 'todays clicks' from nstock_stats_dn_clicks_session
where domain_name(`domain_id`)='mycooldomain.com'
and `date_added`=date(now());

-- history's clicks
select `domain_id`,sum(`unique_count`) as 'history clicks' from nstock_stats_dn_clicks_history
where domain_name(`domain_id`)='mycooldomain.com';

-- join the two tables , (`a`.`todays_clicks`+`b`.`history_clicks`) as `total_clicks`
select `a`.`todays_clicks`,`b`.`history_clicks`,(`a`.`todays_clicks`+`b`.`history_clicks`) as `total_clicks`
from (select `domain_id`,count(`id`) as 'todays_clicks' from nstock_stats_dn_clicks_session
where domain_name(`domain_id`)='mycooldomain.com') a
LEFT outer join (select `domain_id`,sum(`unique_count`) as 'history_clicks' from nstock_stats_dn_clicks_history
where domain_name(`domain_id`)='mycooldomain.com') b
on a.domain_id=b.domain_id;

impress
select `a`.`todays_impress`,`b`.`history_impress`,(`a`.`todays_impress`+`b`.`history_impress`) as `total_impress`
from (select `domain_id`,count(`id`) as 'todays_impress' from nstock_stats_dn_impress_session
where domain_name(`domain_id`)='mycooldomain.com') a
LEFT outer join (select `domain_id`,sum(`unique_count`) as 'history_impress' from nstock_stats_dn_impress_history
where domain_name(`domain_id`)='mycooldomain.com') b
on a.domain_id=b.domain_id

