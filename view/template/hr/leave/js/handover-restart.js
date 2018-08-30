$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val() ,'affstate' : 1},
		isAddAndDel : false,
		colModel : [ {
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '工作及设备交接事项',
			name : 'items',
			type : 'statictext'
		},{
			display : '工作及设备交接事项',
			name : 'items',
			type : 'hidden'
		},{
			display : '接收人',
			name : 'recipientName',
			width : 120,
			type : 'statictext'
		},{
			display : '接收人id',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '遗失财务',
			name : 'lose',
			type : 'statictext'
		},{
			display : '金额',
			name : 'deduct',
			width : 120,
			type : 'statictext'
		},{
			display : '备注',
			name : 'remark',
			type : 'statictext'
		},{
			display : '是否必须提前确认',
			name : 'isKey',
			width : 110,
			type : 'statictext',
			process : function (v) {
				if (v == 'on') {
				   return "<span style='color:blue'>是</span>";
				}else{
				   return "<span style='color:red'>否</span>";
				}
			}
		},{
			display : '确认状态',
			name : 'affstate',
			width : 80,
			type : 'statictext',
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>√</span>";
				}else{
				   return "<span style='color:red'>×</span>";
				}
			}

		},{
			display : '重启',
			name : 'restart',
			width : 80,
			type : 'checkbox',
			process : function (v ,row) {
				if (row.affstate != 1) {
					var num = $("#handoverList").yxeditgrid("getCurRowNum") - 1;
					$("#handoverList_cmp_restart" + num).hide();
				}
			}

		}]
	});
});

function checkData() {
	var tmp = 0;
	$("input[id^='handoverList_cmp_restart']:checkbox").each(function () {
		if ($(this).attr("checked")) {
			tmp = 1;
		}
	});
	if (tmp == 0) {
		alert("请选择重启事项！");
		return false;
	}else {
		return true;
	}
}