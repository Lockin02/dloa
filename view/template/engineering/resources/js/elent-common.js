//�ύ�򱣴�ʱ�ı�����ֵ
function setConfirm(thisType){
	$("#status").val(thisType);
}

//����,�ύʱ���������֤
function checkSubmit(type) {
	var objGrid = $("#importTable");
	//��ȡ�豸������
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//������֤
	for(var i = 0; i < curRowNum ; i++){
		var beginDate = objGrid.yxeditgrid("getCmpByRowAndCol",i,"beginDate").val();
		var endDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"endDate");
		var days = DateDiff(beginDate,endDateObj.val());

		if(days < 0){
			alert("Ԥ�ƹ黹���ڲ�������Ԥ����������");
			endDateObj.focus();
			return false;
		}
		if(days > 30){
			if($("#rcContractType").val() != ""){
				if($("#rcContractType").val() == "GCXMYD-04"){//������ĿΪ������Ŀ
					alert("������ĿΪ������Ŀ������ʱ�䲻�ܳ���1����");
					endDateObj.focus();
					return false;
				}
			}else{
				if($("#rcProjectId").val() != "" && $("#rcProjectId").val() != "0"){
					//��֤��ĿΪ�Ƿ�������Ŀ
					var isPK = false;
					$.ajax({
		                type: "POST",
		                url: "?model=engineering_project_esmproject&action=isPK",
		                data: {'projectId': $("#rcProjectId").val()},
		                async: false,
		                success: function (data) {
		                    if (data == 1) {
		                        isPK = true;
		                    }
		                }
		            });
					if(isPK){
						alert("������ĿΪ������Ŀ������ʱ�䲻�ܳ���1����");
						endDateObj.focus();
						return false;
					}
				}else{//������ĿΪ�գ���ת�赽��������
					alert("ת�赽��������ʱ������ʱ�䲻�ܳ���1����");
					endDateObj.focus();
					return false;
				}
			}
		}
		//����ҳ�棬��֤����
		if(type == 'add'){
			var maxNum = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNum").val();
			var numObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
			var number = numObj.val();
			
	        if (!isNum(number)) {
	            alert("ת������" + "<" + number + ">" + "��д����!");
	            numObj.focus();
	            return false;
	        }
	        if (number*1 > maxNum*1) {
	            alert("ת���������ܴ����ڽ�����");
	            numObj.focus();
	            return false;
	        }
		}
	}
}