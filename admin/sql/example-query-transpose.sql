

-- transpose Example
SELECT `form_id`,
 MAX(CASE WHEN `field_name` = 'form_name' THEN `field_value` ELSE NULL END) AS 'form_name',
 MAX(CASE WHEN `field_name` = 'email' THEN `field_value` ELSE NULL END) AS 'email',
 MAX(CASE WHEN `field_name` = 'newsletter_optin[signmeup]' THEN `field_value` ELSE NULL END) AS 'newsletter_optin[signmeup]'
FROM `simpli_forms_v2_data`
where `form_id` in (select `simpli_forms_v2`.`id` from `simpli_forms_v2` where `simpli_forms_v2`.`form_name`='list_domain')
GROUP BY `form_id` Having `newsletter_optin[signmeup]`='yes'