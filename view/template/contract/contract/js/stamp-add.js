$(document).ready(function(){
    //�ͻ�����
    customerTypeArr = getData('GZLB');
    addDataToSelect(customerTypeArr, 'categoryId');

	// ��֤��Ϣ
	validate({
		"fileName" : {
			required : true
		},
		"signCompanyName" : {
			required : true
		},
		"stampExecution" : {
			required : true
		},
		"stampType" : {
			required : true
		},
		"contractType" : {
			required : true
		}
	});

	//����������Ⱦ
	$("#stampType").yxcombogrid_stampconfig({
		hiddenId : 'stampType',
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : true
		}
	});
	//���ݺ�ͬ������˾�ı��ύ��ť��ֻ�б�Ѷ�ĺ�ͬ��Ҫ��������
	if($("#businessBelong").val() == "bx"){
		$("#businessBelong").next("input").val("�ύ����");
	}
});

function checkForm(){
	if($("#uploadfileList").html() =="" || $("#uploadfileList").html() =="�����κθ���"){
		alert('�������ǰ��Ҫ�ϴ���ͬ������');
		return false;
	}
	//�ύǰ����Ƿ��Ѵ��ڸ��²���δ������
	var	msg = $.ajax({
		url:'?model=contract_stamp_stamp&action=checkStamp',
		data:'contractId=' + $("#contractId").val() + '&contractType=HTGZYD-04',
		dataType:'html',
		type:'post',
		async:false
	}).responseText;
	if(msg == 1){
		alert('�˺�ͬ��������£������ظ����룬�ɵ��ҵĸ�������鿴��');
		return false;
	}
}

//���� - �ύ�����򱣴�
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=contract_stamp_stampapply&action=add&act=audit";
	}
}