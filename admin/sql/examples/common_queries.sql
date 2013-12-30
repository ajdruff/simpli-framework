
-- System Queries
select VERSION();

-- Show MySQL Jobs
show events;

-- Turn Jobs on or off
SET GLOBAL event_scheduler = Off;


-- UTC Current Time
select UTC_TIMESTAMP;

-- Local Time
SELECT CONVERT_TZ(UTC_TIMESTAMP,'+00:00','-08:00');


-- time zone
select timediff(now(),convert_tz(now(),@@session.time_zone,'+00:00')) as 'Time Zone Difference From UTC';
SELECT IF(@@session.time_zone = 'SYSTEM', @@system_time_zone, @@session.time_zone) as 'Current Time Zone';


-- SET time_zone = 'America/Los_Angeles';//named timezones are not setup by default. need to do this: mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql
http://en.wikipedia.org/wiki/List_of_tz_database_time_zones 