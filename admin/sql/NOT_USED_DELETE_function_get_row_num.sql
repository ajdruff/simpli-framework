DROP FUNCTION IF EXISTS get_row_num;
CREATE FUNCTION `get_row_num`(row_num int) RETURNS int(11) CHARSET latin1
    DETERMINISTIC
    COMMENT 'Returns 1 more than given'
BEGIN
DECLARE row_num_result int(11);
SELECT row_num+1 into row_num_result ;
RETURN row_num_result;
    END;

