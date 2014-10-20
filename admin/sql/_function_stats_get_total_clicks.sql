DELIMITER $$

DROP FUNCTION IF EXISTS get_total_clicks$$
CREATE FUNCTION `get_total_clicks`(DOMAIN_NAME VARCHAR(255)) RETURNS int(11)
-- ------------------------------- 
-- Get Total Clicks
-- Get Total Clicks for a Given Domain Id
-- select get_total_clicks('mydomain.com');
-- ------------------------------

    DETERMINISTIC
    COMMENT 'Returns the total clicks to date for the given domain_id'
BEGIN

DECLARE total_clicks_summary int;

select sum(`total`.`clicks`) as 'total_clicks'
-- select *  -- uncomment this line and uncomment the above if you want to see the results of the union before adding the two
from
(
(
-- Get the historical clicks by adding up all the clicks for the ids used by the domain we want.
select 'history' as 'when',sum(`nstock_stats_dn_clicks_history`.`unique_count`) as 'clicks' from nstock_stats_dn_clicks_history
where `nstock_stats_dn_clicks_history`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)=DOMAIN_NAME)
)
UNION
(
-- Get todays clicks by adding up all the clicks for the ids used by the domain we want that ar ein the clicks session table
select 'today',count(`nstock_stats_dn_clicks_session`.`id`) as 'clicks' from nstock_stats_dn_clicks_session
where `nstock_stats_dn_clicks_session`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)=DOMAIN_NAME)
)
)`total`

into total_clicks_summary;




RETURN total_clicks_summary;
    END$$

DELIMITER ;


select get_total_clicks ('wp-phplist.com');
select get_total_clicks ('snaptonic.com');
select get_total_clicks ('zzask.com');
select get_total_clicks ('test722014A.com');

