$(document).ready(function() {

})



//�ύ����
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=add";
	}
}




//�ύ���� - �༭ҳ��
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_certifyapply_certifyresult&action=edit";
	}
}

