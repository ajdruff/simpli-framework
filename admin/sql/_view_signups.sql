/******************************************************************************
 * comment                                                                    *
View for Signups

1) Transposes the data for each form submission 'beta_signup' and 'list_domain'
2) joins it to simpli_forms which provides the time submitted
3) when used in select * from view_signups group `email` , filters out duplicates


 ******************************************************************************/

-- start transaction;
DROP VIEW IF EXISTS `view_signups`;



CREATE
       VIEW `view_signups` 
	AS 

(
SELECT `time_added`,`form_id`,
 MAX(CASE WHEN `field_name` = 'form_name' THEN `field_value` ELSE NULL END) AS 'form_name',
 MAX(CASE WHEN `field_name` = 'email' THEN `field_value` ELSE NULL END) AS 'email',
 MAX(CASE WHEN `field_name` = 'newsletter_optin[signmeup]' THEN `field_value` ELSE NULL END) AS 'opt_in'
FROM `simpli_forms_v2_data` `data`
left join simpli_forms_v2
on `data`.`form_id`=`simpli_forms_v2`.`id`
where `form_id` in (select `simpli_forms_v2`.`id` from `simpli_forms_v2` where `simpli_forms_v2`.`form_name`='list_domain')
GROUP BY `form_id` Having `opt_in`='yes' and `email` IS NOT NULL and email <>''

)
union


(
SELECT `time_added`,`form_id`,
 MAX(CASE WHEN `field_name` = 'form_name' THEN `field_value` ELSE NULL END) AS 'form_name',
 MAX(CASE WHEN `field_name` = 'email' THEN `field_value` ELSE NULL END) AS 'email',
'yes' AS 'opt_in'
FROM `simpli_forms_v2_data` `data`
left join simpli_forms_v2
on `data`.`form_id`=`simpli_forms_v2`.`id`
where `form_id` in (select `simpli_forms_v2`.`id` from `simpli_forms_v2` where `simpli_forms_v2`.`form_name`='beta_signup')
GROUP BY `form_id` Having `opt_in`='yes' and `email` IS NOT NULL and email <>''

)



-- To get Unique Signups: select * from `view_signups` GROUP BY `email` 