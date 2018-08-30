$(document).ready(function() {

})



//提交审批
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=add";
	}
}




//提交审批 - 编辑页面
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=edit";
	}
}

