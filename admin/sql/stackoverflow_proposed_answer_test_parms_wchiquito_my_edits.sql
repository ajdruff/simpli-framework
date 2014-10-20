DELIMITER //
DROP PROCEDURE IF EXISTS `test_parms_wchiquito`//

CREATE PROCEDURE `test_parms_wchiquito`(`REPORT` VARCHAR(255), `DOMAIN_NAME` VARCHAR(255))
BEGIN

  SET @`REPORT` := `REPORT`;
  SET @`DOMAIN_NAME` := `DOMAIN_NAME`;
  SET @`sql` := 'SELECT @`DOMAIN_NAME`, @`REPORT`';
  PREPARE `stmt` FROM @`sql`;
  EXECUTE `stmt` USING @`DOMAIN_NAME`, @`REPORT`;
  DEALLOCATE PREPARE `stmt`;
END//

CALL `test_parms_wchiquito`('my report', 'example.com');
CALL `test_parms_wchiquito`('my report', 'example.com');