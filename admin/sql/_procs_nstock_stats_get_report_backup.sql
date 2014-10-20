DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_stats_get_report`$$

CREATE PROCEDURE `nstock_stats_get_report`(REPORT VARCHAR(255),REPORT_TYPE VARCHAR(255),DOMAIN_NAME VARCHAR(255), ON_DATE DATE)
BEGIN

set @`REPORT`=`REPORT`;
set @`REPORT_TYPE`=`REPORT_TYPE`;
set @`DOMAIN_NAME`=`DOMAIN_NAME`;
set @`ON_DATE`=`ON_DATE`;

IF  `report_type` = 'impressions'    THEN
    SET @`data_table_today` = 'view_nstock_stats_dn_impress_today';
    SET @`data_table_history` = 'nstock_stats_dn_impress_history';
end IF;

IF `report_type` = 'clicks'  THEN
    SET @`data_table_today` = 'view_nstock_stats_dn_clicks_today';
    SET @`data_table_history` = 'nstock_stats_dn_impress_history';
end IF;

set @space=" ";


CASE

-- ================================    
WHEN REPORT='total-for-today' 
-- ================================
-- SELECT sum(`unique_count`) AS `total`
-- FROM @`data_table_today`
-- GROUP BY `date`,`domain`
-- HAVING `date`=date(now())
-- AND `domain`=@`DOMAIN_NAME`
-- ================================   
THEN 

set @sql= CONCAT(

"SELECT sum(`unique_count`) AS `total` from"
,@space ,@`data_table_today`
,@space,"GROUP BY `date`,`domain`"
,@space,"HAVING `date`=date(now())"
,@space,"AND `domain`=","'",@`domain_name`,"'"


); 



-- ================================   
WHEN REPORT='total-for-today-all-domains' 
-- ================================  
-- SELECT   `domain`, sum(`unique_count`) AS `total`
--- FROM    @`data_table_today`
--- GROUP BY `date`,`domain`
--- HAVING `date`=date(now()) 
-- ================================   
THEN 

set @sql= CONCAT(

"SELECT   `domain`, sum(`unique_count`) AS `total` From"
,@space ,@`data_table_today`
,@space,"GROUP BY `date`,`domain`"
,@space,"HAVING `date`=date(now())"


); 


-- ================================   
WHEN REPORT='by-hour-for-today' 
-- ================================   
-- SELECT  `hour`, `unique_count`
-- FROM @data_table_today
-- WHERE date=date(now())
-- AND `domain`=@DOMAIN_NAME
-- ================================   
THEN 


set @sql= CONCAT(

"SELECT  `hour`, `unique_count` From"
,@space ,@`data_table_today`
,@space,"WHERE date=date(now())"
,@space,"AND `domain`=","'",@`domain_name`,"'"

); 

-- ================================   
WHEN  REPORT='by-hour-for-date'
-- ================================   
-- SELECT  `hour`, `unique_count`
-- FROM @data_table_history
-- WHERE date=@ON_DATE
-- AND domain_name(`domain_id`)=@DOMAIN_NAME
-- ================================   
THEN 


set @sql= CONCAT(

"SELECT  `hour`, `unique_count` From"
,@space ,@`data_table_history`
,@space,"WHERE `date`=","'",@`on_date`,"'"
,@space,"AND domain_name(`domain_id`)=","'",@`domain_name`,"'"

); 

-- ================================   
   WHEN @REPORT='by-day'
-- ================================   
-- SELECT date(`date`) AS date, `unique_count`
-- FROM @data_table_history
-- WHERE domain_name(`domain_id`)=@DOMAIN_NAME
-- GROUP BY `date`
-- ================================   
THEN 
set @sql= CONCAT(
"SELECT  date(`date`) AS date, `unique_count` From"
,@space ,@`data_table_history`
,@space,"WHERE domain_name(`domain_id`)=","'",@`domain_name`,"'"
,@space,"GROUP BY `date`,`domain_id`"
); 


ELSE SELECT 'Report Not Available';


END CASE;




PREPARE STMT FROM @sql;
EXECUTE STMT;
DEALLOCATE PREPARE STMT ;





END$$


DELIMITER ;


call nstock_stats_get_report('total-for-today','clicks','mycooldomain.com',NULL);
call nstock_stats_get_report('total-for-today-all-domains','clicks',NULL,NULL);
call nstock_stats_get_report('by-hour-for-today','clicks','mycooldomain.com',NULL);
call nstock_stats_get_report('by-hour-for-date','clicks','mycooldomain.com','2014-02-07');
call nstock_stats_get_report('by-day','clicks','mycooldomain.com',NULL);
-- 
call nstock_stats_get_report('total-for-today','impressions','mycooldomain.com',NULL);
call nstock_stats_get_report('total-for-today-all-domains','impressions',NULL,NULL);
call nstock_stats_get_report('by-hour-for-today','impressions','mycooldomain.com',NULL);
call nstock_stats_get_report('by-hour-for-date','impressions','mycooldomain.com','2014-02-07');
call nstock_stats_get_report('by-day','impressions','mycooldomain.com',NULL);


