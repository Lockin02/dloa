$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_resourceapplydet&action=listJson',
//		title : '设备需求明细',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		async : false,
		colModel : [{
			name : 'status',
			display : '状态',
			process : function(v){
				if(v == 0){
					return '待确认';
				}else if(v == 1){
					return '已确认';
				}else if(v == 2){
					return '撤回待确认';
				}else if(v == 3){
					return '撤回已确认';
				}
			},
			width : 60
		}, {
			name : 'resourceTypeName',
			display : '设备类型',
			process : function(v,row){
				if(row.resourceId == "0"){
					return "<span class='red'>-- 新设备 --</span>";
				}else{
					return v;
				}
			},
			width : 80
		}, {
			name : 'resourceName',
			display : '设备名称',
			width : 80
		}, {
			name : 'number',
			display : '数量',
			width : 40
		}, {
			name : 'exeNumber',
			display : '已下达数量',
			width : 40
		}, {
			name : 'backNumber',
			display : '撤回数量',
			width : 40
		}, {
			name : 'unit',
			display : '单位',
			width : 40
		}, {
			name : 'planBeginDate',
			display : '领用日期',
			width : 70
		}, {
			name : 'planEndDate',
			display : '归还日期',
			width : 70
		}, {
			name : 'useDays',
			display : '使用天数',
			width : 40
		}, {
			name : 'price',
			display : '单设备折旧',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 60
		}, {
			name : 'amount',
			display : '设备成本',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'remark',
			display : '备注'
		}, {
			name : 'feeBack',
			display : '预计筹备天数反馈',
			width : 80
		}]
	});
	var divDocument = document.getElementById("importTable");
	var tbody = divDocument.getElementsByTagName("tbody");
	var $tbody = $(tbody)
	$tbody.after('<tr class="tr_count"><td colspan="3">合计</td>'+
			'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>'
			+'<td style="text-align: right;"><input type="text" id="view_amount" name="resourceapply[amount]" dir="rtl" class="readOnlyTxtShortCount formatMoney" readonly="readonly"/></td>'
			+'<td></td><td></td></tr>');
	calAmount();
});