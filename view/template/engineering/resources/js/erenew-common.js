$(document).ready(function() {
	//��֤��Ϣ
	validate({
		"applyUser" : {
			required : true
		},
		"reason" : {
			required : true
		}
	});
	
	//��ʾ��Ϣ
	if($("#projectId").val() != "" && $("#projectId").val() != "0"){//��Ŀ��������
		if($("#flag").val() == "2"){
			$("#trips").html("������ʾ������ĿΪ�ر�״̬��ֻ���������������£������ڲ��ܳ���1����");
		}else if($("#flag").val() == "3"){
			$("#trips").html("������ʾ������ĿΪ������Ŀ�����ڲ��ܳ���1����");
		}
	}else{//������������
		$("#trips").html("������ʾ����������ʱ�����ڲ��ܳ���1����");
	}
});

//�ύ�򱣴�ʱ�ı�����ֵ
function setConfirm(thisType){
	$("#status").val(thisType);
}

//����,�ύʱ���������֤
function checkSubmit(type) {
	var objGrid = $("#importTable");
	//��ȡ�豸������
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//�����֤
	for(var i = 0; i < curRowNum ; i++){
		//������֤
		var beginDate = objGrid.yxeditgrid("getCmpByRowAndCol",i,"beginDate").val();
		var endDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"endDate");
		var days = DateDiff(beginDate,endDateObj.val());
		if(days < 0){
			alert("Ԥ�ƹ黹���ڲ�������Ԥ�ƿ�ʼ����");
			endDateObj.focus();
			return false;
		}
		if(days > 30 && $("#flag").val() != "1"){
			alert("������಻�ܳ���1����");
			endDateObj.focus();
			return false;
		}
		//����ҳ�棬��֤����
		if(type == 'add'){
			var maxNum = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNum").val();
			var numObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
			var number = numObj.val();
			
	        if (!isNum(number)) {
	            alert("��������" + "<" + number + ">" + "��д����!");
	            numObj.focus();
	            return false;
	        }
	        if (number*1 > maxNum*1) {
	            alert("�����������ܴ����ڽ�����");
	            numObj.focus();
	            return false;
	        }
		}
	}
}