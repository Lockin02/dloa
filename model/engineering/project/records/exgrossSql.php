<?php
/**
 * @description:������Ŀ����ë���ʼ�¼��(oa_esm_records_exgross) sql�����ļ�
 */
$sql_arr = array (
	"select_default"=>"select c.id,c.projectId,c.projectCode,c.projectName,c.updateId,c.updateName,c.updateDate,c.exgross,
		c.feeAll,c.feeField,c.feePerson,c.feeEqu,c.feeOutsourcing,c.feeOther,c.feeFlights,c.feePK from oa_esm_records_exgross c where 1"
);