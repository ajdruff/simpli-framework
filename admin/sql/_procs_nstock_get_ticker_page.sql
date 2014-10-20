DELIMITER $$

DROP PROCEDURE IF EXISTS `nstock_get_ticker_page`$$

-- --------------------------------------
-- Get Ticker Page
-- When called with 0, it returns the most recent results
-- When called with any number other than zero, it starts with that as the highest id, and 
-- returns listings in sequential order below it.
-- call nstock_get_ticker_page (0); 
-- --------------------------------------


CREATE PROCEDURE `nstock_get_ticker_page`(START_ID INT(11))
BEGIN



set @`MAX_LIMIT`=50; -- recommended 25
set @`space`=" ";
set @`START_ID`=START_ID;


-- find total records. instead of a subquery, we'll use 
-- a variable to hold the result. then we'll return it along with the 
-- rest. this isnt really any more efficient, but allows us to refer back to 
-- the total if we need to.

-- get the total number of records and divide by number max, and you'll get the total 
-- number of pages. we can then use this to inform our paging javascript
set @`total_pages`=( select CEILING(count(*)  / @`MAX_LIMIT`) from `view_domain_listings`);



-- select  @`total_records` as total,* from view_domain_listings
-- where (`id`<@`start`) or @`start`=0
-- order by `id` DESC
-- limit 25

set @sql= CONCAT(
"select"
,@space,@`total_pages`
,@space, "as `total_pages`"
,@space,",`view_domain_listings`.* from `view_domain_listings`"
,@space,"where (`id`<",@space,@`START_ID`,")"
,@space,"or (",@space,@`START_ID`,"=0)"
,@space,"order by `id` DESC"
,@space,"Limit",@space,@`MAX_LIMIT`);


START TRANSACTION;
PREPARE STMT FROM @sql;
EXECUTE STMT;
COMMIT;
-- DEALLOCATE PREPARE STMT ;





END$$


DELIMITER ;

call nstock_get_ticker_page (0);

