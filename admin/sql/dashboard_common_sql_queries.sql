-- Dashboard - Frequently Used Queries when administering MySQL



-- System Queries
select VERSION();


-- ---------------------------------------------------------------------- 
-- MySQL Job Scheduler
-- ----------------------------------------------------------------------
-- Show MySQL Jobs
show events;
-- SHOW SCHEDULER STATUS
SHOW VARIABLES LIKE 'event_scheduler';

-- Turn Jobs on or off
SET GLOBAL event_scheduler = On;
SET GLOBAL event_scheduler = Off;




-- Timezone references: http://stackoverflow.com/a/19075291/3306354 http://stackoverflow.com/a/19069310/3306354

-- View UTC Current Time
select UTC_TIMESTAMP;

-- View Local Time ( -7 for Daylight Savings Time, -8 without)
SELECT CONVERT_TZ(UTC_TIMESTAMP,'+00:00','-07:00');




select *
from
(
(
select 'UTC using UTC_TIMESTAMP' as 'TIMEZONE' , UTC_TIMESTAMP as 'Time'
)
UNION
(
select 'UTC using now()' as 'TIMEZONE' , UTC_TIMESTAMP as 'now()'
)

UNION
(
select 'PDT DST (Summer)'  , CONVERT_TZ(UTC_TIMESTAMP,'+00:00','-07:00')
)
UNION
(
select 'PDT No DST (Winter)', CONVERT_TZ(UTC_TIMESTAMP,'+00:00','-08:00')
)
)`time_comparison`


-- Show Current Time Zone 
select timediff(now(),convert_tz(now(),@@session.time_zone,'+00:00')) as 'Time Zone Difference From UTC';
SELECT IF(@@session.time_zone = 'SYSTEM', @@system_time_zone, @@session.time_zone) as 'Current Time Zone';

-- two different variables that hold time zone
select @@system_time_zone;
select @@session.time_zone;
SET time_zone = '-07:00';
select timediff(now(),convert_tz(now(),'+00:00',@@system_time_zone)) as 'Time Zone Difference From UTC';
select timediff(now(),convert_tz(now(),'+00:00','-07:00')) as 'Time Zone Difference From UTC';

-- SET time_zone = 'America/Los_Angeles';//named timezones are not setup by default. need to do this: mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql
http://en.wikipedia.org/wiki/List_of_tz_database_time_zones 

SELECT CONVERT_TZ(now(),'US/Eastern','US/Central');

SELECT @@global.time_zone;


SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP);

SELECT UNIX_TIMESTAMP(NOW())