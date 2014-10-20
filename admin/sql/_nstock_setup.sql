-- Set Global Variables
set @`NSTOCK_VARS_DB`='simpli_wp_dev';



-- Import Domain Names from a CSV File.
LOAD DATA
    INFILE "C:/projects/nomstock/domains/manage/database-import/nomstock-inventory-import-7-5-2014.csv"
INTO TABLE `simpli_wp_dev`.`nstock_inventory`
    FIELDS TERMINATED BY '|'
optionally enclosed by '"'
    LINES TERMINATED BY '\r\n'
-- ignores the first line containing header
    IGNORE 1 LINES  
        (@id,`subdomain`, `tld`, `bin`, `bid`, `price`, `currency`, `time_added`, `seller`, `time_lastupdated`, `verified`) 
   set time_added = NULL,  time_lastupdated = NULL;


-- Insert Portfolio into nstock_domains with random start times.