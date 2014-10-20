-- Used to create the domain_name column which is a concate of subdomain and tld
DELIMITER $$

DROP TRIGGER /*!50032 IF EXISTS */ `trigger_domain_concat_insert`$$

create trigger `trigger_domain_concat_insert` BEFORE INSERT on `nstock_domains` 
for each row BEGIN
SET New.domain_name = concat(New.subdomain,'.',New.tld);
END;
$$

DELIMITER ;