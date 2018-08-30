$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_ereturndetail&action=listJson',
		param : {
			'mainId' : $("#id").val(),
			'status' : '0'
		},
		isAdd : false,
		hideRowNum : true,
		objName : 'ereturn[item]',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'ȷ��',
			name : 'isChecked',
			type : 'checkbox',
			checkVal : '1',
			process : function ($input ,rowData) {
				var rowNum = $input.data("rowNum");
				$("#importTable_cmp_isChecked" + rowNum).attr('checked' ,'checked');
			}
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext',
			width : 150
		}, {
			name : 'resourceName',
			display : '�豸����',
			isSubmit : true,
			type : 'statictext',
			width : 150
		}, {
			name : 'coding',
			display : '������',
			isSubmit : true,
			type : 'statictext',
			width : 150
		}, {
			name : 'dpcoding',
			display : '���ű���',
			isSubmit : true,
			type : 'statictext',
			width : 100
		}, {
			name : 'number',
			display : '��������',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 60
		}, {
			name : 'confirmNumV',
			display : '�ѹ黹����',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 60,
			process:function($input,row){
				$input.val(row.confirmNum);
			}
		}, {
			name : 'confirmNum',
			display : '�ѹ黹����',
			process:function($input,row){
				$input.val(row.number);
			},
			type : 'hidden'
		}, {
			name : 'remainNum',
			display : 'ʣ������',
			type : 'hidden'
		}, {
			name : 'returnNum',
			display : '�黹����',
			width : 60,
			process:function($input,row){
				$input.val(row.remainNum);
			},
			event : {
				blur : function(){
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					//�ۼ��ѹ黹����
					var confirmNumV = g.getCmpByRowAndCol(rowNum,'confirmNumV').val();
					var confirmNumObj = g.getCmpByRowAndCol(rowNum,'confirmNum');
					confirmNumObj.val(accAdd(confirmNumV,$(this).val()));
				}
			}
		}, {
			name : 'unit',
			display : '��λ',
			isSubmit : true,
			type : 'statictext',
			width : 100
		}, {
			name : 'id',
			display : 'id',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'borrowItemId',
			display : '���õ���ϸId',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceListId',
			display : 'resourceListId',
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
		}]
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

//ȷ��ʱ���������֤
function checkSubmit() {
	var objGrid = $("#importTable");
	//�Ƿ������ύ
	var submit = true;
	var returnNumArr = objGrid.yxeditgrid('getCmpByCol','returnNum');
	if(returnNumArr.length > 0){
	    //�Ƿ�ѡ������
	    var isChecked = false;
		returnNumArr.each(function(){
	    	var rowNum = $(this).data("rowNum");//��ǰ����
	        if(objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"isChecked").attr('checked')){//ֻ��֤��ѡ������
	        	isChecked = true;
	    		var remainNum = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"remainNum").val();
	    		var returnNum = $(this).val();
	            if (!isNum(returnNum)) {
	                alert("�黹����" + "<" + returnNum + ">" + "��д����!");
	                $(this).focus();
	                submit = false;
	            }
	            if (returnNum*1 > remainNum*1) {
	                alert("�黹�������ܴ���" + remainNum);
	                $(this).focus();
	                submit = false;
	            }
	        }
	    });
	    if(isChecked == false){
	        alert('�����ٹ�ѡһ���豸����ȷ��');
	        return false;
	    }
	}
    return submit;
}