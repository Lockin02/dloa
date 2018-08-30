//借用里归还的js
$(function() {
	$("#borrowTable").yxeditgrid({
		objName : 'return[item]',
		url : '?model=asset_assetcard_assetcard&action=listJson',
		param : {
			id : $("#assetId").val()
		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode',
			validation : {
				required : true
			},
			readonly : true
		}, {
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			readonly : true
		}, {
			display : '资产Id',
			name : 'assetId',
			process : function($input,row){
				var assetId = row.id;
				$input.val(assetId);
			},
			type : 'txtshort',
			type : 'hidden'
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '预计使用期间数',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '剩余使用年限',
			name : 'residueYears',
			tclass : 'txtshort',
			process : function($input,row){
				var residueYears = row.estimateDay*1-row.alreadyDay*1;
				$input.val(residueYears);
			},
			readonly : true
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	$("#borrowTable").yxeditgrid("hideAddBn");
	// 选择人员组件
	$("#returnMan").yxselect_user({
		hiddenId : 'returnManId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */
	validate({
		"chargeMan" : {
			required : true
		},
		"chargeDate" : {
			required : true,
			custom : ['date']
		}
	});

});

/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_return&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}