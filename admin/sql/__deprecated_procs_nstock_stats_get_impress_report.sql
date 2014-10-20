-- Impression Reports - old queries that measure page views vs real impressions


DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_impress_report`$$
-- REPORT VARCHAR(255),
CREATE PROCEDURE `nstock_stats_get_impress_report`(REPORT VARCHAR(255),DOMAIN_NAME VARCHAR(255), ON_DATE DATE)
BEGIN



CASE 
    -- X Total Impressions of All Domains for today 
   WHEN  REPORT='total-for-today-all-domains' 
        THEN SELECT   domain_name(`domain_id`), sum(`count`) as `total`
        FROM    nstock_stats_dn_impressions
        GROUP BY `date`,`domain_id`
        having `date`=date(now());

-- X Total Impressions made today by a domain
   WHEN  REPORT='total-for-today'  
        THEN Select `count` 
        from nstock_stats_dn_impressions 
        where domain_name(`domain_id`)=DOMAIN_NAME
        and date=date(now());


-- X Total Impressions made on a Given Date for a given domain
   WHEN  REPORT='total-for-date'  
        THEN Select `count`
        from nstock_stats_dn_impressions 
        where domain_name(`domain_id`)=DOMAIN_NAME
        and date=ON_DATE;


-- Finds the domain with the most impressions on a single day
    WHEN  REPORT='domain-with-most-impressions-on-a-single-day'  
        THEN SELECT `date`,domain_name(`domain_id`) as `domain_name`,`count` FROM nstock_stats_dn_impressions WHERE `count` IN (SELECT max(`count`)          From nstock_stats_dn_impressions);

 -- Impressions by day for a given domain
        WHEN  REPORT='by-day'  
        THEN Select date(`date`) as date, `count` 
	From nstock_stats_dn_impressions
	where domain_name(`domain_id`)=DOMAIN_NAME
        group by `date`
        Order by `date` DESC;

-- Domains Ranked by Number of Impressions   
        WHEN  REPORT='by-domain'  
        Then SELECT   domain_name(`domain_id`) as `domain_name`, sum(`count`) as `total`
        FROM     nstock_stats_dn_impressions
        GROUP BY `domain_id`
        ORDER BY `total` DESC;
   
-- Lists Each Domain with the date they achieved their highest impressions,
-- in order of magnitude
-- call nstock_stats_get_impress_report('daily-max',NULL,NULL); 
        WHEN  REPORT='daily-max'  
        Then SELECT domain_name(`domain_id`) as `domain_name`,`count`
FROM (SELECT *
FROM nstock_stats_dn_impressions
ORDER BY count DESC) AS s
GROUP BY `domain_id` -- dont try to group on domain_name, it wont work when in stored procedure
Order By count DESC;

   WHEN  REPORT='by-day-by-domain'  
-- Lists all domains and their dates with impression counts
Then SELECT  `date`,domain_name(`domain_id`) as `domain_name`,count from 
nstock_stats_dn_impressions 
order by `date` DESC,`domain_name` ASC;
   WHEN  REPORT='by-day-all-domains'  
-- Lists Each Day with total impressions for all domains
Then SELECT  `date`,sum(`count`) from 
nstock_stats_dn_impressions 
group by `date`
order by `date` DESC;
   ELSE Select 'No Such Report'; 
END CASE; 



END$$

DELIMITER ;

-- call nstock_stats_get_impress_report('total-for-today-all-domains',NULL,NULL);
-- call nstock_stats_get_impress_report('total-for-today','mycooldomain.com',NULL);
-- call nstock_stats_get_impress_report('by-day','mycooldomain.com',NULL);


-- Total Impressions of All Domains for today 
call nstock_stats_get_impress_report('total-for-today-all-domains',NULL,NULL); 

-- Total Impressions made today by a domain 
call nstock_stats_get_impress_report('total-for-today','mycooldomain.com',NULL);  


-- Total Impressions made on a Given Date for a Given Domain
call nstock_stats_get_impress_report('total-for-date','mycooldomain.com','14-02-14');   

-- Finds the domain with the most impressions on a single day
call nstock_stats_get_impress_report('domain-with-most-impressions-on-a-single-day',NULL,NULL);   


 
-- Impressions by day for a given domain 
call nstock_stats_get_impress_report('by-day','mycooldomain.com',NULL);  

-- Domains Ranked by Number of Impressions
call nstock_stats_get_impress_report('by-domain',NULL,NULL);


-- Lists Each Domain with the date they achieved their highest impressions, in order of magnitude
call nstock_stats_get_impress_report('daily-max',NULL,NULL); 

-- Lists all domains and their dates with impression counts
call nstock_stats_get_impress_report('by-day-by-domain',NULL,NULL);  

-- Lists Each Day with total impressions for all domains
call nstock_stats_get_impress_report('by-day-all-domains',NULL,NULL);  