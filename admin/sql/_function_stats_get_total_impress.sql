DELIMITER $$

DROP FUNCTION IF EXISTS get_total_impress$$
CREATE FUNCTION `get_total_impress`(DOMAIN_NAME VARCHAR(255)) RETURNS int(11)
-- ------------------------------- 
-- Get Total Impress
-- Get Total Impressions for a Given Domain Id
-- select get_total_impress('mydomain.com');
-- ------------------------------
    DETERMINISTIC
    COMMENT 'Returns the total impressions to date for the given domain name'
BEGIN
DECLARE total_impressions int;


-- Now combine them with a union
select sum(`total`.`impressions`) as 'total_impressions'
-- select *  -- uncomment this line and uncomment the above if you want to see the results of the union before adding the two
from
(
(
-- Get the historical impressions by adding up all the impressions for the ids used by the domain we want.
select 'history' as 'when',sum(`nstock_stats_dn_impress_history`.`unique_count`) as 'impressions' from nstock_stats_dn_impress_history
where `nstock_stats_dn_impress_history`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)=DOMAIN_NAME)
)
UNION
(
select 'today',count(`nstock_stats_dn_impress_session`.`id`) as 'impressions' from nstock_stats_dn_impress_session
where `nstock_stats_dn_impress_session`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)=DOMAIN_NAME)
)
)`total`




into total_impressions;




RETURN total_impressions;
    END$$

DELIMITER ;


select get_total_impress ('wp-phplist.com');
select get_total_impress ('snaptonic.com');
select get_total_impress ('zzask.com');
select get_total_impress ('test722014A.com');

-- Deadlocks
-- Deadlocks might occur and you may just have to live with them until the database is redesigned

----------- Development -------
--- I tried using joins and the domain_name function, but they were either too complicated and/or gave timeouts/deadlocks.
-- I also found that i did not have to check for null for new domains. but if i had to, this is what i would use: 
-- select (IFNULL(`a`.`todays_impress`, 0)+IFNULL(`b`.`history_impress`, 0)) as `total_impress`
-- the following are brief notes on how i developed this query.
-- i use a union of the two queries i want, and then add them together.


-- get total historical impressions for a given domain
select sum(`nstock_stats_dn_impress_history`.`unique_count`) as 'history_impress' from nstock_stats_dn_impress_history
where `nstock_stats_dn_impress_history`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)='wp-phplist.com')


-- get total historical impressions for a given domain
select count(`nstock_stats_dn_impress_session`.`id`) as 'todays_impress' from nstock_stats_dn_impress_session
where `nstock_stats_dn_impress_session`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)='wp-phplist.com')



-- Now combine them with a union
select sum(`total`.`impressions`) as 'total_impressions'
-- select *
from
(
(
select 'history' as 'when',sum(`nstock_stats_dn_impress_history`.`unique_count`) as 'impressions' from nstock_stats_dn_impress_history
where `nstock_stats_dn_impress_history`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)='wp-phplist.com')
)
UNION
(
select 'today',count(`nstock_stats_dn_impress_session`.`id`) as 'impressions' from nstock_stats_dn_impress_session
where `nstock_stats_dn_impress_session`.`domain_id` in (select `nstock_domains`.`id` from nstock_domains where concat(`nstock_domains`.`subdomain`,'.',`nstock_domains`.`tld`)='wp-phplist.com')
)
)`total`