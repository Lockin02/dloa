//��ʾ��˽��
function showAudit(assessResult,row){
	if(row.managerAuditState != ""){
		if(row.managerAuditState == assessResult){
			return "<span class='blue'>��</span>";
		}
	}else{
		return '-';
	}
}