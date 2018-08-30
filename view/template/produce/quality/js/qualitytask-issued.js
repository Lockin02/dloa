$(document).ready(function() {
	//�ʼ���
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserCode',
		formCode : 'qualitytaskUser'
	});

	validate({
		"chargeUserName" : {
			required : true
		}
	});

	//��ʼ���ʼ���ϸ
	initDetail();
})

//��ʼ���ʼ���ϸ
function initDetail(){
	var paramArr;
	if($("#applyId").val() == ""){
		paramArr = {
			'idArr' : $("#itemId").val(),
			'status' : '4'
		};
	}else{
		paramArr = {
			'mainId' : $("#applyId").val(),
			'status' : '4'
		};
	}

	$("#itemTable").yxeditgrid({
		objName : 'qualitytask[items]',
		url : '?model=produce_quality_qualityapplyitem&action=confirmPassJson',
		param : paramArr,
		isAdd : false,
		event : {
			'reloadData' : function(e){
				//�����ȡ�����ӱ�
				if($("#itemTable").yxeditgrid("getCmpByCol", "productCode").length == 0){
					alert('�ʼ���������ȫ�´���ܼ����˲���');
					closeFun();
				}
				//��ʼ���ʼ�������
				countAssignNum();
				//��ʼ�����뵥��Ϣ
				countApplyCode();
			},
			'removeRow' : function(){
				//��ʼ�����뵥��Ϣ
				countApplyCode();
			}
		},
		colModel : [{
			name : 'productCode',
			display : '���ϱ��',
			width : 90,
			type : 'statictext'
		}, {
			name : 'productName',
			display : '��������',
			width : 180,
			type : 'statictext'
		}, {
			name : 'pattern',
			display : '�ͺ�',
			width : 130,
			type : 'statictext'
		}, {
			name : 'unitName',
			display : '��λ',
			width : 60,
			type : 'statictext'
		}, {
			name : 'checkTypeName',
			display : '�ʼ췽ʽ',
			width : 70,
			type : 'statictext'
		}, {
			name : 'qualityNum',
			display : '��������',
			width : 70,
			type : 'statictext'
		}, {
			name : 'assignNum',
			display : '�´�����',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'canAssignNum',
			display : '���´�����',
			type : 'hidden'
		}, {
			name : 'remark',
			display : '��ע'
		}, {
			name : 'relDocCode',
			display : 'Դ�����',
			width : 90,
			type : 'statictext'
		}, {
			name : 'applyUserName',
			display : '������',
			width : 90,
			type : 'statictext'
		}, {
			name : 'productId',
			display : "productId",
			type : 'hidden'
		}, {
			name : 'productCode',
			display : "productCode",
			type : 'hidden'
		}, {
			name : 'productName',
			display : "productName",
			type : 'hidden'
		}, {
			name : 'pattern',
			display : "pattern",
			type : 'hidden'
		}, {
			name : 'unitName',
			display : "unitName",
			type : 'hidden'
		}, {
			name : 'checkTypeName',
			display : "checkTypeName",
			type : 'hidden'
		}, {
			name : 'checkType',
			display : "checkType",
			type : 'hidden'
		}, {
			name : 'applyItemId',
			display : "applyItemId",
			type : 'hidden'
		}, {
			name : 'supplierId',
			display : "supplierId",
			type : 'hidden'
		}, {
			name : 'supplierName',
			display : "supplierName",
			type : 'hidden'
		}, {
			name : 'supportTime',
			display : "supportTime",
			type : 'hidden'
		}, {
			name : 'purchaserName',
			display : "purchaserName",
			type : 'hidden'
		}, {
			name : 'purchaserId',
			display : "purchaserId",
			type : 'hidden'
		}, {
			name : 'objId',
			display : "objId",
			type : 'hidden'
		}, {
			name : 'objCode',
			display : "objCode",
			type : 'hidden'
		}, {
			name : 'objType',
			display : "objType",
			type : 'hidden'
		}, {
			name : 'objItemId',
			display : "objItemId",
			type : 'hidden'
		}, {
			name : 'applyId',
			display : "applyId",
			type : 'hidden'
		}, {
			name : 'applyCode',
			display : "applyCode",
			type : 'hidden'
		}]
	});
}

//������´�����
function countAssignNum(){
	var objGrid = $("#itemTable");
	var cmps = objGrid.yxeditgrid("getCmpByCol", "canAssignNum");
	cmps.each(function(i,n) {
		//���˵�ɾ������
		if($("#qualitytask[items]_" + i +"_isDelTag").length == 0){
			//�������´�����
			objGrid.yxeditgrid("getCmpByRowAndCol",i,"assignNum").val(this.value);
		}
	});
}

//�������뵥��Ϣ
function countApplyCode(){
	var objGrid = $("#itemTable");
	var cmps = objGrid.yxeditgrid("getCmpByCol", "applyId");

	//��������
	var applyIdArr = [];
	var applyCodeArr = [];

	cmps.each(function(i,n) {
		//���˵�ɾ������
		if($("#qualitytask[items]_" + i +"_isDelTag").length == 0){
			if(jQuery.inArray(this.value,applyIdArr) == -1){

				//��������
				applyIdArr.push(this.value);
				applyCodeArr.push(objGrid.yxeditgrid("getCmpByRowAndCol",i,"applyCode").val());
			}
		}
	});

	$("#applyId").val(applyIdArr.toString());
	$("#applyCode").val(applyCodeArr.toString());
}

/**
 * ��У��
 * @returns {Boolean}
 */
function checkForm() {
	var checkResult = true;
	var itemCount = $("#itembody").children("tr").size();
	for ( var i = 0; i < itemCount; i++) {
		if (parseFloat($("#assignNum" + i).val()) > parseFloat($(
				"#notExeNum" + i).val())) {
			checkResult = false;
			alert("�������ܴ���δ�´�����!");
		}

		if (!checkResult) {
			break;
		}
	}

	return checkResult;
}