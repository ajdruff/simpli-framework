-- ----------------------------------------
-- Event `nstock_stats_update`
-- Periodically insert a pre-approved name from my inventory to the ticker
-- ----------------------------------------
delimiter |
DROP EVENT IF EXISTS nstock_add_random_inventory_to_ticker|


CREATE EVENT nstock_add_random_inventory_to_ticker
    ON SCHEDULE
      EVERY 1 HOUR
	STARTS '2013-11-08 21:00:00'
    ON COMPLETION PRESERVE
    ENABLE

    DO
      BEGIN

call `nstock_add_random_inventory_to_ticker`;

      END |


delimiter ;

