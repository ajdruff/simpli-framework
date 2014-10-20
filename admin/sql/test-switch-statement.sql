--- this was a test of a switch statement
--- some lessons learned: you can't use it in a prepare statement, since prepared statements can only handle a single statement. it will give a syntax
--- error if there are is more than one semicoln.
--- instead of preparing the statement, prepare the call instead .


DELIMITER $$

DROP PROCEDURE IF EXISTS `test_switch`$$

CREATE PROCEDURE `test_switch`(REPORT VARCHAR(255),DOMAIN_NAME VARCHAR(255))
BEGIN

CASE 
   WHEN  REPORT='clicks'  
        THEN Select DOMAIN_NAME;
   WHEN  REPORT='impressions'  
        THEN Select DOMAIN_NAME; 
   ELSE Select 'Nothing Selected'; 
END CASE; 


END$$

DELIMITER ;

-- -- call test_switch('clicks','example.com');

  PREPARE stmt FROM "call test_switch('clicks','example.com')";
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;

-- works standalone
CASE 
   WHEN  REPORT='clicks'  
        THEN Select DOMAIN_NAME;
   WHEN  @REPORT='impressions'  
        THEN Select DOMAIN_NAME; 
   ELSE Select 'Nothing Selected'; 
END CASE; 