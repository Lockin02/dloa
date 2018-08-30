<?php
/**
 * @description:工程项目更新毛利率记录表(oa_esm_records_exgross) sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id,c.projectId,c.projectCode,c.projectName,c.updateId,c.updateName,c.updateDate,c.exgross,
		c.feeAll,c.feeField,c.feePerson,c.feeEqu,c.feeOutsourcing,c.feeOther,c.feeFlights,c.feePK from oa_esm_records_exgross c where 1"
);