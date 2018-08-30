$(function(){
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	$("#taskInfo").yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=listJson",
		param : {"taskId":$("#id").val()},
		tableClass : 'form_in_table',
		objName : 'task[detail]',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			width : 100,
			type : 'statictext'
		}, {
			display : '�豸����',
			name : 'resourceName',
			validation : {
				required : true
			},
			width : 120,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var resourceTypeId = g.getCmpByRowAndCol(rowNum,'resourceTypeId').val();
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						param : {typeid : resourceTypeId},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var resourceName = g.getCmpByRowAndCol(rowNum,'resourceName').val();
									var remark = g.getCmpByRowAndCol(rowNum,'remark').val();
									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									g.getCmpByRowAndCol(rowNum,'remark').val(remark+"(����Ա����"+resourceName+"���޸�Ϊ��"+rowData.device_name+"��)");
								}
							})(rowNum)
						}
					}
				}).attr("readonly",true);
			}
		}, {
			display : '��λ',
			name : 'unit',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 50
		}, {
			display : '��������',
			name : 'number',
			width : 50
		}, {
			display : '����������',
			name : 'maxNumber',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'planBeginDate',
			width : 70,
			type : 'statictext'
		}, {
			display : '�黹����',
			name : 'planEndDate',
			width : 70,
			type : 'statictext'
		}, {
			display : 'ʹ������',
			name : 'useDays',
			width : 50,
			type : 'statictext'
		}, {
			display : '��ע',
			name : 'remark',
			width : 300
		}]
	});
});
//����֤
function checkForm() {
	var objGrid = $("#taskInfo");
	//�ӱ�����Ϊ��
	if(objGrid.yxeditgrid("getCurShowRowNum") == 0){
		alert("����������鲻��Ϊ��!");
		return false;
	}
	var itemscount = objGrid.yxeditgrid("getCurRowNum");
	var num = 0;//�����������Ϊ0����ϸ����
	for (var i = 0; i < itemscount; i++) {
		//��ȡ��������
		var numberObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
		var number = numberObj.val();
		if(number == 0){
			num++;
		}
		//��ȡ�ɷ����������
		var maxNumber = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNumber").val();
		if(number == ""){
			alert("������������Ϊ�գ�");
			numberObj.focus();
			return false;
		}
		var re = /^[0-9]\d*$/;
		if(!re.test(number)){
			alert("������������Ϊ��������");
			numberObj.focus();
			return false;
		}
		if (parseInt(number) > parseInt(maxNumber)) {
			alert("�����������ܴ���"+maxNumber);
			numberObj.focus();
			return false;
		}
	}
	if(num == itemscount){
		alert("������ϸ����������Ϊ0����ص����������б���С��������񡿲���!");
		return false;
	}
	if(confirm('ȷ���ύ������')){
		return true;
	}else{
		return false;
	}
}