-- Template for Prepare Statement showing use of a paramater
-- This can then be used within a procedure.

PREPARE STMT FROM 'SELECT * FROM nstock_domains LIMIT ?';

SET @foo=5;
EXECUTE STMT USING @foo;

