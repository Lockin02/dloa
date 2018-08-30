$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_resourceapplydet&action=listJson',
//		title : '�豸������ϸ',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		async : false,
		colModel : [{
			name : 'status',
			display : '״̬',
			process : function(v){
				if(v == 0){
					return '��ȷ��';
				}else if(v == 1){
					return '��ȷ��';
				}else if(v == 2){
					return '���ش�ȷ��';
				}else if(v == 3){
					return '������ȷ��';
				}
			},
			width : 60
		}, {
			name : 'resourceTypeName',
			display : '�豸����',
			process : function(v,row){
				if(row.resourceId == "0"){
					return "<span class='red'>-- ���豸 --</span>";
				}else{
					return v;
				}
			},
			width : 80
		}, {
			name : 'resourceName',
			display : '�豸����',
			width : 80
		}, {
			name : 'number',
			display : '����',
			width : 40
		}, {
			name : 'exeNumber',
			display : '���´�����',
			width : 40
		}, {
			name : 'backNumber',
			display : '��������',
			width : 40
		}, {
			name : 'unit',
			display : '��λ',
			width : 40
		}, {
			name : 'planBeginDate',
			display : '��������',
			width : 70
		}, {
			name : 'planEndDate',
			display : '�黹����',
			width : 70
		}, {
			name : 'useDays',
			display : 'ʹ������',
			width : 40
		}, {
			name : 'price',
			display : '���豸�۾�',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 60
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
		}, {
			name : 'feeBack',
			display : 'Ԥ�Ƴﱸ��������',
			width : 80
		}]
	});
	var divDocument = document.getElementById("importTable");
	var tbody = divDocument.getElementsByTagName("tbody");
	var $tbody = $(tbody)
	$tbody.after('<tr class="tr_count"><td colspan="3">�ϼ�</td>'+
			'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>'
			+'<td style="text-align: right;"><input type="text" id="view_amount" name="resourceapply[amount]" dir="rtl" class="readOnlyTxtShortCount formatMoney" readonly="readonly"/></td>'
			+'<td></td><td></td></tr>');
	calAmount();
});