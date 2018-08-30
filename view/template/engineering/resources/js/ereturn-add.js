$(document).ready(function() {
	// ��Ա��Ⱦ
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});

	// ʵ�����ʼĹ�˾
	$("#expressName").yxcombogrid_logistics({
		hiddenId : 'expressId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

	// �ӱ��ʼ��
	$("#importTable").yxeditgrid({
		url : '?model=engineering_device_esmdevice&action=selectdeviceInfo',
		param : {
			rowsId : $("#rowsId").val()
		},
		isAddAndDel : false,
		objName : 'ereturnDetail',
		tableClass : 'form_in_table',
		colModel : [ {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			width : 150,
			isSubmit : true
		}, {
			name : 'resourceName',
			display : '�豸����',
			type : 'statictext',
			width : 150,
			isSubmit : true
		}, {
			name : 'coding',
			display : '������',
			type : 'statictext',
			width : 150,
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '���ű���',
			type : 'statictext',
			width : 100,
			isSubmit : true
		}, {
			name : 'maxNum',
			display : '�ڽ�����',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 80,
			process:function($input,row){
				$input.val(row.number);
			}
		}, {
			name : 'number',
			display : '�黹����',
			width : 80,
			isSubmit : true
		}, {
			name : 'unit',
			display : '��λ',
			type : 'statictext',
			width : 80,
			isSubmit : true
		}, {
            name : 'resourceListId',
            display : 'resourceListId',
            isSubmit : true,
            type : 'hidden'
        }, {
            name : 'borrowItemId',
            display : '���õ���ϸid',
            isSubmit : true,
            type : 'hidden'
        }, {
			name : 'resourceId',
			display : '�豸ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '�豸����ID',
			isSubmit : true,
			type : 'hidden'
		} ]
	});

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"areaId" : {
			required : true
		}
	});
});

// �ύȷ�ϸı�����ֵ
function setStatus(thisType) {
	$("#status").val(thisType);
}

//����,�ύʱ���������֤
function checkSubmit() {
	var objGrid = $("#importTable");
	//��ȡ�豸������
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//��֤�黹����
	for(var i = 0; i < curRowNum ; i++){
		var maxNum = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNum").val();
		var numObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
		var number = numObj.val();
		
        if (!isNum(number)) {
            alert("�黹����" + "<" + number + ">" + "��д����!");
            numObj.focus();
            return false;
        }
        if (number*1 > maxNum*1) {
            alert("�黹�������ܴ����ڽ�����");
            numObj.focus();
            return false;
        }
	}
}