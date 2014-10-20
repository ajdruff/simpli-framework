DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_report`$$

CREATE PROCEDURE `nstock_stats_get_report`(REPORT VARCHAR(255),REPORT_TYPE VARCHAR(255),DOMAIN_NAME VARCHAR(255), ON_DATE DATE)
BEGIN

-- ----------------
-- Set @ statements
-- Initialization ( these must be here or the prepared statements will issue syntax errors --
-- ----------------

set @`REPORT`=`REPORT`;
set @`REPORT_TYPE`=`REPORT_TYPE`;
set @`DOMAIN_NAME`=`DOMAIN_NAME`;
set @`ON_DATE`=`ON_DATE`;
set @space=" ";

IF  `report_type` = 'impressions'    THEN
    SET @`data_table_today` = 'view_nstock_stats_dn_impress_today';
    SET @`data_table_history` = 'nstock_stats_dn_impress_history';
end IF;

IF `report_type` = 'clicks'  THEN
    SET @`data_table_today` = 'view_nstock_stats_dn_clicks_today';
    SET @`data_table_history` = 'nstock_stats_dn_clicks_history';
end IF;




CASE

-- --------------------------------  
WHEN REPORT='total-for-today' 
-- --------------------------------  
-- Given a domain, returns the grand total it received.
-- call nstock_stats_get_report('total-for-today','clicks','mycooldomain.com',NULL);
-- --------------------------------    
-- SELECT sum(`unique_count`) AS `total`
-- FROM `view_nstock_stats_dn_clicks_today` -- @`data_table_today`
-- GROUP BY `date`,`domain`
-- HAVING `date`=date(now())
-- AND `domain`='mycooldomain.com'
-- --------------------------------  
THEN 



set @sql= CONCAT(

"SELECT sum(`unique_count`) AS `total` from"
,@space ,@`data_table_today`
,@space,"GROUP BY `date`,`domain`"
,@space,"HAVING `date`=date(now())"
,@space,"AND `domain`=","'",@`domain_name`,"'"


); 

-- --------------------------------  
WHEN REPORT='total-for-today-all-domains' 
-- --------------------------------  
-- Returns the list of domains that ran today  and how many events they received
-- call nstock_stats_get_report('total-for-today-all-domains','clicks','mycooldomain.com',NULL);
-- Note: Need to change this to include even domains that did not receive any clicks, by doing a join on domain_id
-- --------------------------------    
-- SELECT   `domain`, sum(`unique_count`) AS `total`
-- FROM    @`data_table_today`
-- GROUP BY `date`,`domain`
-- HAVING `date`=date(now()) 
-- --------------------------------    
THEN 

set @sql= CONCAT(

"SELECT   `domain`, sum(`unique_count`) AS `total` From"
,@space ,@`data_table_today`
,@space,"GROUP BY `date`,`domain`"
,@space,"HAVING `date`=date(now())"


); 


-- --------------------------------    
WHEN REPORT='by-hour-for-today' 
-- --------------------------------  
-- Given a domain, returns today's hours and the events it received in that hour
-- call nstock_stats_get_report('by-hour-for-today','clicks','mycooldomain.com',NULL);
-- --------------------------------    
-- SELECT  `hour`, `unique_count`
-- FROM @data_table_today
-- WHERE date=date(now())
-- AND `domain`=@DOMAIN_NAME
-- --------------------------------    
THEN 


set @sql= CONCAT(

"SELECT  `hour`, `unique_count` From"
,@space ,@`data_table_today`
,@space,"WHERE date=date(now())"
,@space,"AND `domain`=","'",@`domain_name`,"'"

); 




-- --------------------------------    
WHEN  REPORT='by-hour-for-date'
-- --------------------------------  
-- Given a domain and date, returns each hour and the events it received in that hour
-- call nstock_stats_get_report('by-hour-for-date','clicks','mycooldomain.com','2014-02-07');
-- --------------------------------     
-- SELECT  `hour`, `unique_count`
-- FROM @data_table_history
-- WHERE date=@ON_DATE
-- AND domain_name(`domain_id`)=@DOMAIN_NAME
-- --------------------------------    
THEN 


set @sql= CONCAT(

"SELECT  `hour`, `unique_count` From"
,@space ,@`data_table_history`
,@space,"WHERE `date`=","'",@`on_date`,"'"
,@space,"AND domain_name(`domain_id`)=","'",@`domain_name`,"'"

); 

-- --------------------------------    
   WHEN REPORT='by-day'
-- --------------------------------  
-- Given a domain, returns each date and total for that date for the domain
-- call nstock_stats_get_report('by-day','clicks','mycooldomain.com',NULL);
-- --------------------------------    
-- SELECT date(`date`) AS date, sum(`unique_count`)
-- FROM @data_table_history
-- WHERE domain_name(`domain_id`)=@DOMAIN_NAME
-- GROUP BY `date`
-- --------------------------------    
THEN 
set @sql= CONCAT(
"SELECT  date(`date`) AS date, sum(`unique_count`) as 'unique_count' From"
,@space ,@`data_table_history`
,@space,"WHERE domain_name(`domain_id`)=","'",@`domain_name`,"'"
,@space,"GROUP BY `date`,`domain_id`"
); 

-- --------------------------------    
   WHEN REPORT='total-for-date'
-- --------------------------------  
--- Return a single grand total for the date and domain provided 
--- call nstock_stats_get_report('total-for-date','clicks','mycooldomain.com','2014-02-07');
-- --------------------------------   
-- THEN Select sum(`unique_count`)
-- from@`data_table_history`
-- where domain_name(`domain_id`)=DOMAIN_NAME
-- and date=ON_DATE; 
-- --------------------------------    
THEN
set @sql= CONCAT(
"SELECT  sum(`unique_count`) as `total` From"
,@space ,@`data_table_history`
,@space,"WHERE domain_name(`domain_id`)=","'",@`domain_name`,"'"
,@space,"AND `date`=","'",@`on_date`,"'"
); 

-- --------------------------------    
   WHEN REPORT='stats-summary'
-- --------------------------------  
--- Given a domain, returns the number of lifetime clicks and impressions
--- call nstock_stats_get_report('stats-summary',NULL,'mycooldomain.com',NULL);
-- --------------------------------   
-- select total_clicks,total_impressions from nstock_domains 
-- where `id` =(
-- select max(`id`) from
-- nstock_domains
-- where  domain_name(`id`)='mycooldomain.com')
-- --------------------------------    
THEN
set @sql= CONCAT(
"SELECT  `total_clicks`,`total_impressions` From"
,@space ,'nstock_domains'
,@space ,'where `id` =('
,@space ,'select max(`id`) from nstock_domains'
,@space,"WHERE domain_name(`id`)=","'",@`domain_name`,"'"
,@space,")"
); 


ELSE SELECT 'No Such Report';




END CASE;


PREPARE STMT FROM @sql;
EXECUTE STMT;
DEALLOCATE PREPARE STMT ;





END$$


DELIMITER ;


call nstock_stats_get_report('total-for-today','clicks','mycooldomain.com',NULL);
call nstock_stats_get_report('total-for-today-all-domains','clicks',NULL,NULL);
call nstock_stats_get_report('total-for-date','clicks','mycooldomain.com','2014-02-07');




call nstock_stats_get_report('by-hour-for-today','clicks','mycooldomain.com',NULL);
call nstock_stats_get_report('by-hour-for-date','clicks','mycooldomain.com','2014-02-12');
call nstock_stats_get_report('by-day','clicks','mycooldomain.com',NULL);

call nstock_stats_get_report('total-for-today','impressions','mycooldomain.com',NULL);
call nstock_stats_get_report('total-for-today-all-domains','impressions',NULL,NULL);
call nstock_stats_get_report('by-hour-for-today','impressions','mycooldomain.com',NULL);
call nstock_stats_get_report('by-hour-for-date','impressions','mycooldomain.com','2014-02-07');
call nstock_stats_get_report('by-day','impressions','mycooldomain.com',NULL);

call nstock_stats_get_report('stats-summary',NULL,'mycooldomain.com',NULL);


call nstock_stats_get_report('stats-summary',NULL,'tshirtt.com',NULL); 


call nstock_stats_get_report('by-day','impressions','snapforum.com',Null);


