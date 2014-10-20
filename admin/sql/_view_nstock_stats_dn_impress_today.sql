/******************************************************************************
 * comment                                                                    *
View for Today's Viewport Impression Stats
Data > than a day old is pushed to the dn_impress_history table. Today's data
is summarized using this view. Splitting it apart like this allows us to 
avoid the complexity of merging live data with historical data.

 ******************************************************************************/


DROP VIEW IF EXISTS `view_nstock_stats_dn_impress_today`;

CREATE
       VIEW `view_nstock_stats_dn_impress_today` 
	AS 
		(

SELECT domain_name(`domain_id`) as `domain`,date(`time_added`) AS date,hour(`time_added`) AS hour ,COUNT(`id`) AS `unique_count`
FROM `nstock_stats_dn_impress_session`
where date(`time_added`)=date(now())
GROUP BY `domain_id`,`hour`
)



/******************************************************************************
 * comment                                                                    *
DROP VIEW IF EXISTS `view_nstock_stats_dn_impress_today`;

CREATE
       VIEW `view_nstock_stats_dn_impress_today` 
	AS 
		(

SELECT domain_name(domain_id) as domain,date(time_added) AS date,hour(time_added) AS hour ,COUNT(id) AS `unique_count`
FROM nstock_stats_dn_impress_session
where date(time_added)=date(now())
GROUP BY domain,hour
)

 ******************************************************************************/



