/******************************************************************************
 * comment                                                                    *
Subquery view for domain listings
Not intended to be called directly
Used to find the maximum id of archived domains so as to filter out duplicates.
then joined in the parent query to get the rest of the fields
 ******************************************************************************/

DROP VIEW IF EXISTS `view_domain_listings_grouped`;

CREATE
    VIEW `view_domain_listings_grouped` 
	AS 
		(select  domain_name(nstock_domains.id) as 'domain_name' ,nstock_domains.*,
MAX(nstock_domains.id) AS max_id
        FROM    nstock_domains
where (nstock_domains.list_status='active' or nstock_domains.list_status='archived') and nstock_domains.approved='y'
        GROUP BY `domain_name`)

