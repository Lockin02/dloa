//ÏÔÊ¾ÉóºË½á¹û
function showAudit(assessResult,row){
	if(row.managerAuditState != ""){
		if(row.managerAuditState == assessResult){
			return "<span class='blue'>¡Ì</span>";
		}
	}else{
		return '-';
	}
}