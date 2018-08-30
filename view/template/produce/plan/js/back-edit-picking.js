$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'back[items]',
		title : '�������뵥��ϸ',
		isAdd : false,
		url : '?model=produce_plan_backitem&action=listJson',
		param : {
			backId : $("#id").val(),
			pickingId : $("#pickingId").val(),
			type : 'edit',
			dir : 'ASC'
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '���ϵ�Id',
			name : 'pickingId',
			type : 'hidden'
		},{
			display : '���ϵ��嵥Id',
			name : 'pickingItemId',
			type : 'hidden'
		},{
			display : '�����ƻ�Id',
			name : 'planId',
			type : 'hidden'
		},{
			display : '�����ƻ�����',
			name : 'planCode',
			width : 120,
			type : "statictext"
		},{
			display : '��������Id',
			name : 'taskId',
			type : 'hidden'
		},{
			display : '��������Code',
			name : 'taskCode',
			type : 'hidden'
		},{
			display : '��ͬId',
			name : 'relDocId',
			type : 'hidden'
		},{
			display : '��ͬ���',
			name : 'relDocCode',
			width : 120,
			type : "statictext"
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode',
			width : 75,
			type : "statictext"
		},{
			display : '��������',
			name : 'productName',
			width : 250,
			type : "statictext"
		},{
			display : '����ͺ�',
			name : 'pattern',
			width : 90,
			type : "statictext"
		},{
			display : '��λ',
			name : 'unitName',
			width : 80,
			type : "statictext"
		},{
			display : '���豸��',
			name : 'JSBC',
			width : 50,
			type : "statictext"
		},{
			display : '�����Ʒ',
			name : 'KCSP',
			width : 50,
			type : "statictext"
		},{
			display : '������',
			name : 'SCC',
			width : 50,
			type : "statictext"
		},{
			display : '��������',
			name : 'applyNum',
			width : 50,
			process : function ($input, rowData) {
				var oldNum = $input.val();
				var maxNum = rowData['maxNum'];
				$input.change(function () {
					if(!isNum($(this).val())){
						alert("������������!");
						$(this).val(oldNum);
					}
					if(accSub($(this).val(),maxNum) > 0){
						alert("�����������ܴ���" + maxNum);
						$(this).val(oldNum);
					}
				})
			}
		},{
			display : '��ע',
			name : 'remark',
			width : 120,
			align : 'left'
		}]
	});
});

//����
function save(){
	$('#form1').attr("action","?model=produce_plan_back&action=edit");
}

// �ύ
function sub(){
	$('#form1').attr("action","?model=produce_plan_back&action=edit&act=sub");
}

//�ύʱ��֤
function confirmCheck(){
	if($("#productItem").yxeditgrid('getCurShowRowNum') == 0){
		alert("�������뵥��ϸ����Ϊ��!");
		return false;
	}
}