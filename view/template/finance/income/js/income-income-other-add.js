// ҵ����������
$(function(){
	$("#objCode").yxcombogrid_other({
		hiddenId : 'objId',
		height : 300,
		width : 700,
		searchName : 'docCode',
		gridOptions : {
			param : {'ExaStatus' : '���','fundType' : 'KXXZA'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#province").val(data.proName);
					$("#incomeUnitName").val(data.signCompanyName);
					$("#incomeUnitId").val(data.signCompanyId);
					$("#contractUnitName").val(data.signCompanyName);
					$("#contractUnitId").val(data.signCompanyId);
					$("#rObjCode").val(data.objCode);
					$("#objType").val('KPRK-09');
				}
			}
		}
	});
	
	//������˾
	$("#businessBelongName").yxcombogrid_branch('remove').yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//��ʼ�����ṹ
					initTree();
					//�������η�Χ
					reloadManager();
				}
			}
		}
	});
});


/**
 * ���������뵽�������
 */
function toSubmit() {
	if($("#incomeUnitName").val() == ''){
		alert('����д�ͻ�����');
		return false;
	}

	if($("#incomeMoney").val() == '' || $("#incomeMoney").val()*1 == 0){
		alert('�����뵥�ݽ��');
		return false;
	}
	
	if($("#businessBelongName").val() == ''){
		alert('�����������˾');
		return false;
	}
}
