/******************************************************************************
 * comment                                                                    *
View for Domain Listings
Shows only active domains unless there are  not enough , and then shows
archived domains, filtering out duplicates.

 ******************************************************************************/


DROP VIEW IF EXISTS `simpli_wp_dev`.`view_domain_listings`;

CREATE
       VIEW `simpli_wp_dev`.`view_domain_listings` 
	AS 
		(SELECT  domain_name(active.id) as `domain_name`,all_domains.*
FROM  nstock_domains all_domains
JOIN  `simpli_wp_dev`.`_view_domain_listings_grouped` active
ON      all_domains.id = active.max_id
ORDER BY field(all_domains.list_status,'active','archived' ),all_domains.time_added DESC Limit 5000)



/******************************************************************************
 * comment                                                                    *
DROP VIEW IF EXISTS `simpli_wp_dev`.`view_domain_listings`;

CREATE
       VIEW `simpli_wp_dev`.`view_domain_listings` 
	AS 
		(SELECT  domain_name(active.id) as `domain_name`,all_domains.*
FROM  nstock_domains all_domains
JOIN  `simpli_wp_dev`.`_view_domain_listings_grouped` active
ON      all_domains.id = active.max_id
ORDER BY field(all_domains.list_status,'active','archived' ),all_domains.time_added DESC Limit 5000)
 ******************************************************************************/



