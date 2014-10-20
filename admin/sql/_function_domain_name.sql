DROP FUNCTION IF EXISTS domain_name;
CREATE FUNCTION `domain_name`(id int) RETURNS varchar(255) CHARSET latin1
    DETERMINISTIC
    COMMENT 'Returns the full domain name of name in nstock_domains table referenced by id'
BEGIN
DECLARE domain_name varchar(255);
SELECT CONCAT(`nstock_domains`.`subdomain`, '.',`nstock_domains`.`tld`) into domain_name from `nstock_domains` where `nstock_domains`.`id`=id;
RETURN domain_name;
    END;

