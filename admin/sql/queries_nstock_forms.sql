-----------------------------------
-- Forms
-- Misc queries to help you maintain Simpli Forms
-----------------------------------

-- ----------------------------------------
-- Delete All
-- ----------------------------------------

delete from `simpli_forms`


-- ----------------------------------------
-- Delete Forms Marked for deletion
-- ----------------------------------------

select * from `simpli_forms`
where `status`='deleted';

delete from `simpli_forms`
where `status`='deleted';



-- ----------------------------------------
-- Delete sspm Suspected Spam
-- ----------------------------------------

delete from `simpli_forms`
where `status`='sspam';


-- ----------------------------------------
-- Delete New
-- ----------------------------------------

delete from `simpli_forms`
where `status`='new';


-- ----------------------------------------
-- Delete New
-- ----------------------------------------

delete from `simpli_forms`
where `status`='saved';
