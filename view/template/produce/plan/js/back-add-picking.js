$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'back[items]',
		title : '�������뵥��ϸ',
		isAdd : false,
		isFristRowDenyDel: true,
		url : '?model=produce_plan_pickingitem&action=listJson',
		param : {
			pickingId : $("#pickingId").val(),
			type : 'back',
			dir : 'ASC'
		},
		event : {
			reloadData : function(event, g,data) {
				if(!data || data.length == 0){
					alert('û�п��´������');
					closeFun();
				}
			}
		},
		colModel : [{
			display : '���ϵ�Id',
			name : 'pickingId',
			type : 'hidden'
		},{
			display : 'id',
			name : 'pickingItemId',
			type : 'hidden',
			process : function ($input, rowData) {
				$input.val(rowData['id']);
			}
		},{
			display : '�����ƻ�Id',
			name : 'planId',
			type : 'hidden'
		},{
			display : '�����ƻ�����',
			name : 'planCode',
			width : 120,
			type : "statictext",
			isSubmit : true
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
			type : "statictext",
			isSubmit : true
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode',
			width : 75,
			type : "statictext",
			isSubmit : true
		},{
			display : '��������',
			name : 'productName',
			width : 250,
			type : "statictext",
			isSubmit : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			width : 90,
			type : "statictext",
			isSubmit : true
		},{
			display : '��λ',
			name : 'unitName',
			width : 80,
			type : "statictext",
			isSubmit : true
		},{
			display : '���豸��',
			name : 'JSBC',
			width : 50,
			type : "statictext",
			isSubmit : true
		},{
			display : '�����Ʒ',
			name : 'KCSP',
			width : 50,
			type : "statictext",
			isSubmit : true
		},{
			display : '������',
			name : 'SCC',
			width : 50,
			type : "statictext",
			isSubmit : true
		},{
			display : '��������',
			name : 'applyNum',
			width : 50,
			process : function ($input, rowData) {
				$input.val(rowData['realityNum']);
				var oldNum = $input.val();
				$input.change(function () {
					if(!isNum($(this).val())){
						alert("������������!");
						$(this).val(oldNum);
					}
					if(accSub($(this).val(),oldNum) > 0){
						alert("�����������ܴ���" + oldNum);
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
	$('#form1').attr("action","?model=produce_plan_back&action=add");
}

// �ύ
function sub(){
	$('#form1').attr("action","?model=produce_plan_back&action=add&act=sub");
}