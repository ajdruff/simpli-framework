DROP FUNCTION IF EXISTS nstock_replace_active_listing;
CREATE FUNCTION `nstock_replace_active_listing`(old_id int,new_id int) RETURNS varchar(255) CHARSET latin1
    DETERMINISTIC
    COMMENT 'Returns the full domain name of name in nstock_domains table referenced by id'
BEGIN
-- update `nstock_domains` set price_note='active' where `id`=new_id;
update `nstock_domains` set price_note='archived' where `id`=old_id;
return 'active';
    END;

