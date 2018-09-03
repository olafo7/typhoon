set global log_bin_trust_function_creators=TRUE;
//获取组织结构
create function getChildrenOrg(orgid INT)
returns varchar(4000)
BEGIN
DECLARE oTemp VARCHAR(4000);
DECLARE oTempChild VARCHAR(4000);

SET oTemp = '';
SET oTempChild = CAST(orgid AS CHAR);

WHILE oTempChild IS NOT NULL
DO
SET oTemp = CONCAT(oTemp,',',oTempChild);
SELECT GROUP_CONCAT(id) INTO oTempChild FROM db_main_struct WHERE FIND_IN_SET(fid,oTempChild) > 0;
END WHILE;
RETURN oTemp;
END


--eg.   select * from db_main_struct where FIND_IN_SET(fid,getChildrenOrg(3));