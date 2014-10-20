/******************************************************************************
 * comment                                                                    *
View for Domain Listings
Shows only active domains unless there are  not enough , and then shows
archived domains, filtering out duplicates.
# recommended DESC LIMIT = 500
 ******************************************************************************/

-- start transaction;
DROP VIEW IF EXISTS `view_domain_listings`;



CREATE
       VIEW `view_domain_listings` 
	AS 
		(SELECT   domain_name(active.id) as `domain_name`,all_domains.*
FROM  nstock_domains all_domains
JOIN  `view_domain_listings_grouped` active
ON      all_domains.id = active.max_id
ORDER BY field(all_domains.list_status,'active','archived' ),all_domains.time_added DESC Limit 500);
-- commit;


