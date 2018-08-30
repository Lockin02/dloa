$(document).ready(function() {
	//Դ������Ϊԭ���ϼ���ʱ,����ɾ����ϸ
	var isDel = false;
	if($("#relDocType").val() == "ZJSQYDSL"){
		isDel = true;
	}
	//Դ������Ϊ�����ƻ���ʱ,����༭��������
	var isQualityNumReadonly = true;
	var qualityNumClass = 'readOnlyTxtItem';
	if($("#relDocType").val() == "ZJSQYDSC"){
		isQualityNumReadonly = false;
		qualityNumClass = 'txtmiddle';
	}
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapply&action=toAddDetail',
		param : {
			relDocId : $("#relDocId").val(),
			relDocType : $("#relDocType").val()
		},
        event : {
			reloadData : function(e,g,data) {
				if(!data || data.length == 0){
					alert('��ȫ�������ʼ�,���ܼ�������');
					closeFun();
				}
			}
		},
		title : '�ʼ�������ϸ',
		isAddAndDel : isDel,
		isAdd : false,
		colModel : [{
			name : 'productId',
			display : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '���ϱ��',
			tclass : 'readOnlyTxtItem',
			width : 80,
			readonly : true
		}, {
			name : 'productName',
			tclass : 'readOnlyTxtMiddle',
			display : '��������',
			readonly : true
		}, {
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			display : '�ͺ�',
			readonly : true
		}, {
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			display : '��λ',
			readonly : true
		}, {
			name : 'checkTypeName',
			tclass : 'readOnlyTxtItem',
			display : '�ʼ췽ʽ',
			readonly : true
		}, {
			name : 'checkType',
			display : 'checkType',
			type : 'hidden'
		}, {
			name : 'qualityNum',
			tclass : qualityNumClass,
			display : '��������',
            readonly : isQualityNumReadonly,
            event : {
            	blur : function(e){
	            	var rownum = $(this).data('rowNum');// �ڼ���
	            	var grid = $(this).data('grid');// ������
	
	            	var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
	            	
	            	if(!isNum($(this).val()) || $(this).val() *1 <= 0){
		            	alert("������������Ϊ��������");
		            	$(this).val(maxNum);
	            	}
	
	            	if($(this).val() *1 > maxNum *1){
		            	alert("�����������ܴ���" + maxNum);
		            	$(this).val(maxNum);
	            	}
            	}
            },
		}, {
			name : 'maxNum',
			display : '�������',
			process:function($input,row){
				$input.val(row.qualityNum);
			},
			type : 'hidden'
		}, {
			name : 'relDocItemId',
			display : 'relDocItemId',
			type : 'hidden'
		}, {
			name : 'serialId',
			display : '���к�ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			tclass : 'readOnlyTxtNormal',
			display : '���к�',
			readonly : true
		}]
	});
});

//�ύʱ��֤
function checkForm(){
	if($("#relDocType").val() == "ZJSQYDSL"){//Դ������Ϊԭ���ϼ���ʱ,��ϸ������Ϊ��
		if($("#itemTable").yxeditgrid("getCurShowRowNum") == 0){
			alert("�ʼ�������ϸ����Ϊ��");
			return false;
		}
	}
	return true;
}