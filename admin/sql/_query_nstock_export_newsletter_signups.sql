
-- Export Domains To An Excel File
Select * from `view_signups`
    INTO OUTFILE "C:/projects/nomstock/domains/manage/database-import/nomstock-signups.xlsx"
    FIELDS TERMINATED BY '|'
optionally enclosed by '"'
    LINES TERMINATED BY '\r\n';


--  All signups, stripping out duplicates and exporting to a file. Must delete any existing file before running it.

select * from (
-- start union
(select `form_id`,`data`.`form_name`,`email`,`opt_in`,`time_added` from
(
SELECT `form_id`,
 MAX(CASE WHEN `field_name` = 'form_name' THEN `field_value` ELSE NULL END) AS 'form_name',
 MAX(CASE WHEN `field_name` = 'email' THEN `field_value` ELSE NULL END) AS 'email',
 MAX(CASE WHEN `field_name` = 'newsletter_optin[signmeup]' THEN `field_value` ELSE NULL END) AS 'opt_in'
FROM `simpli_forms_v2_data`
where `form_id` in (select `simpli_forms_v2`.`id` from `simpli_forms_v2` where `simpli_forms_v2`.`form_name`='list_domain')
GROUP BY `form_id` Having `opt_in`='yes' and `email` IS NOT NULL and email <>''
)data
left join simpli_forms_v2
on `data`.`form_id`=`simpli_forms_v2`.id
)
union
(select `form_id`,`data`.`form_name`,`email`,`opt_in`,`time_added` from
(

SELECT `form_id`,
 MAX(CASE WHEN `field_name` = 'form_name' THEN `field_value` ELSE NULL END) AS 'form_name',
 MAX(CASE WHEN `field_name` = 'email' THEN `field_value` ELSE NULL END) AS 'email',
'yes' AS 'opt_in'
FROM `simpli_forms_v2_data`
where `form_id` in (select `simpli_forms_v2`.`id` from `simpli_forms_v2` where `simpli_forms_v2`.`form_name`='beta_signup')
GROUP BY `form_id` Having `opt_in`='yes' and `email` IS NOT NULL and email <>''
)data
left join simpli_forms_v2
on `data`.`form_id`=`simpli_forms_v2`.id
)
-- end union
) b
GROUP BY `email` 

    INTO OUTFILE "C:/projects/nomstock/domains/manage/database-import/nomstock-signups.csv"
    FIELDS TERMINATED BY '|'
optionally enclosed by '"'
    LINES TERMINATED BY '\r\n';