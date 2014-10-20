select id,form_name,fields,status,time_added from `simpli_forms` where `status`='new' order by id

update simpli_forms
set status='new'
where status IN ('new')

update simpli_forms set status='saved' where id IN ('1', '3', '4', '5')


Alter TABLE simpli_forms modify column time_added TIMESTAMP DEFAULT 0;
Alter TABLE simpli_forms modify column time_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

Alter TABLE simpli_forms add(time_added TIMESTAMP DEFAULT 0,time_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);



select form_name from simpli_forms where status != 'deleted'
group by form_name