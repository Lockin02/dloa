$(document).ready(function() {
	$("#planItem").yxeditgrid({
		url : '?model=produce_plan_picking&action=listJsonProduct',
		param : {
			planId : $("#planId").val(),
			productId : $("#productId").val()
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type: 'hidden'
		},{
			name : 'docCode',
			display: '生产领料单号',
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'docStatus',
			display: '单据状态',
			process : function (v) {
				switch (v) {
					case '0' : return "未提交";break;
					case '1' : return "审批中";break;
					case '2' : return "完成";break;
					case '3' : return "打回";break;
					default : return "--";
				}
			}
		},{
			name : 'docDate',
			display: '单据日期'
		},{
			name : 'relDocCode',
			display: '源单编号'
		},{
			name : 'relDocName',
			display: '源单名称',
			width : 200
		},{
			name : 'relDocType',
			display: '源单类型'
		},{
			name : 'createName',
			display: '申请人'
		},{
			name : 'remark',
			display: '备注',
			width : 250
		}]
	});
});