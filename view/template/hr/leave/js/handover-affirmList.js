$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val() , 'recipientIdArr' : $("#userId").val(),'affstate':'0'},
		isAddAndDel : false,
		colModel : [ {
			display : 'id',
			name : 'id',
//			type : 'txt'
			type : 'hidden'
		},{
			display : 'handoverId',
			name : 'handoverId',
//			type : 'txt'
			type : 'hidden'

		},{
			display : '工作及设备交接事项',
			name : 'items',
			readonly : "readonly",
			type : 'statictext'
		},{
			display : '工作及设备交接事项',
			name : 'items',
			type : 'hidden'
		}, {
			display : '交接情况',
			name : 'handoverCondition',
			type : 'hidden',
			tclass : 'txtlong'
		}, {
			display : '接收人',
			name : 'recipientName',
			readonly : "readonly",
			type : 'statictext'
		}, {
			display : '确认状态',
			name : 'affstate',
			type : 'checkbox'

		}, {
			display : '接收人Id',
			name : 'recipientId',
//			type : 'txt'
			type : 'hidden'
		}, {
			display : '遗失财务',
			name : 'lose',
			type : 'txt',
			event : {
				blur : function() {
					var thisVal = $.trim($(this).val());
					if(thisVal!=''){
						var rowNum = $(this).data("rowNum");
						var g = $(this).data("grid");
						var affstate = g.getCmpByRowAndCol(rowNum,'affstate').attr("checked");
						if(!affstate){
							alert("请勾选'确认状态',否则该交接项确认无效.");
						}
					}
				}
			}
		}, {
			display : '金额',
			name : 'deduct',
			type : 'txt',
			event : {
				blur : function() {
					var thisVal = $.trim($(this).val());
					if(thisVal!=''){
						var rowNum = $(this).data("rowNum");
						var g = $(this).data("grid");
						var affstate = g.getCmpByRowAndCol(rowNum,'affstate').attr("checked");
						if(!affstate){
							alert("请勾选'确认状态',否则该交接项确认无效.");
						}
					}
				}
			}
		}, {
			display : '备注',
			name : 'remark',
			type : 'textarea',
			event : {
				blur : function() {
					var thisVal = $.trim($(this).val());
					if(thisVal!=''){
						var rowNum = $(this).data("rowNum");
						var g = $(this).data("grid");
						var affstate = g.getCmpByRowAndCol(rowNum,'affstate').attr("checked");
						if(!affstate){
							alert("请勾选'确认状态',否则该交接项确认无效.");
						}
					}
				}
			}
		},{
			display : '排序',
			name : 'sort',
			type : 'hidden'
		},{
			display : '是否发送邮件',
			name : 'mailAffirm',
			type : 'hidden'
		},{
			display : '发送前提',
			name : 'sendPremise',
			type : 'hidden'
		}]
	});
});

function checkData() {
	var tmp = 0;
	$("input[id^='handoverList_cmp_affstate']:checkbox").each(function () {
		if ($(this).attr("checked")) {
			tmp = 1;
		}
	});
	if (tmp == 0) {
		alert("请勾选'确认状态',否则该交接项确认无效.");
		return false;
	}else {
		return true;
	}
}