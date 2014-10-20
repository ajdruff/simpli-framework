/*
* These are the raw queries that I used to build the CronEmailAlerts module methods for finding who to email.
* These are only for reference - for the latest, please see the CronEmailAlerts module.
*/



-- Email Listed Alert - All those whose domains have actually listed by no alerts have been sent to them telling them so
select 
`email`,
concat(`subdomain`,`tld`) as domain,
bin,
bid,
currency,
time_added,
time_list_start,
list_status
from nstock_domains 
where 
list_status='active' 
and `email_sent_listed`='n'
;

-- Email Rejected Alert- All those (except spammers) whose domains have been rejected by the review process.
select 
`email`,
concat(`subdomain`,`tld`) as domain,
bin,
bid,
currency,
time_added,
list_status,
not_listed_reason 
from nstock_domains 
where 
approved='n' 
and `email_sent_listed`='n'
and `rejected_reason` !='spam' -- don't email a spammer!
;

-- Email Not Listed Alert - All those whose domains have not listed but should have because there were too many people ahead of them.
select 
`email`,
concat(`subdomain`,`tld`) as domain,
bin,
bid,
currency,
time_added,
time_list_start,
list_status,
not_listed_reason 
from nstock_domains 
where 
approved='y'
and list_status='not listed'
and `email_sent_listed`='n'
and `not_listed_reason`!='disapproved'
;

