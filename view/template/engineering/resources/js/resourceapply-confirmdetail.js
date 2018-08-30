$(document).ready(function() {
	//��ȷ������
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_resourceapplydet&action=listJson',
		param : {
			'mainId' : $("#id").val(),
			"isConfirm" : 1//ȷ���˵�����
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			name : 'resourceTypeName',
			display : '�豸����'
		}, {
			name : 'resourceName',
			display : '�豸����'
		}, {
			name : 'number',
			display : '����',
			width : 60
		}, {
			name : 'exeNumber',
			display : '���´�����',
			width : 60
		}, {
			name : 'unit',
			display : '��λ',
			width : 60
		}, {
			name : 'planBeginDate',
			display : '��������',
			width : 80
		}, {
			name : 'planEndDate',
			display : '�黹����',
			width : 80
		}, {
			name : 'useDays',
			display : 'ʹ������',
			width : 60
		}, {
			name : 'price',
			display : '���豸�۾�',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'amount',
			display : '�豸�ɱ�',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'remark',
			display : '��ע'
		}]
	});


	//δȷ������
	$("#unconfirmDetial").yxeditgrid({
		url : "?model=engineering_resources_resourceapplydet&action=listJson",
		param : {
			'mainId' : $("#id").val(),
			"isNotConfirm" : 1//δȷ������
		},
		objName : 'resourceapply[resourceapplydet]',
		tableClass : 'form_in_table',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
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
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 80
		}, {
			display : '�豸����',
			name : 'resourceName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'resourceTypeName').val(rowData.deviceType);
									g.getCmpByRowAndCol(rowNum,'resourceTypeId').val(rowData.typeid);

									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									g.setRowColValue(rowNum, 'price', rowData.budgetPrice, true);
									//�����豸���
									calResourceBatch(rowNum);
								}
							})(rowNum)
						}
					}
				}).attr("readonly",false);
			}
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 50
		}, {
			display : '����',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 50
		}, {
			display : '��������',
			name : 'planBeginDate',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 90
		}, {
			display : '�黹����',
			name : 'planEndDate',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 90
		}, {
			display : 'ʹ������',
			name : 'useDays',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txtmiddle'
		}],
		event : {
			'reloadData' : function(e,g,data){
				if(!data){
					alert('û����Ҫȷ�ϵ��豸');
					window.close();
				}
			}
		}
	})
});

//�����豸��� - �������� - ���� ������ʹ��
function calResourceBatch(rowNum){
	//�ӱ�ǰ���ַ���
	var beforeStr = "unconfirmDetial_cmp";
	//��ȡ��ǰ����
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_resourceName"  + rowNum ).val() != "" && number != ""){
		//��ȡ����
		var price = $("#" + beforeStr +  "_price" + rowNum + "_v").val();
		//��ȡ����
		var useDays = $("#" + beforeStr +  "_useDays" + rowNum ).val();
		//���㵥���豸���
		var amount = accMul(number,price,2);

		//��������豸���
		var amount = accMul(useDays,amount,2);

		setMoney(beforeStr +  "_amount" +  rowNum,amount,2);
	}
}

//����֤
function checkForm(){
	var resourceIdArr = $("#unconfirmDetial").yxeditgrid('getCmpByCol','resourceId');
	var isAllConfirm = true;
	resourceIdArr.each(function(){
		if(this.value == "0"){
			isAllConfirm = false;
		}
	});
	if(isAllConfirm == false){
		alert('����δȷ�ϵ�����,�����ύ��');
		return false;
	}
	if(confirm('ȷ���ύ������')){
		return true;
	}else{
		return false;
	}
}