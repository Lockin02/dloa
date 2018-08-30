$(function() {
	// 选择调拨申请人组件
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		mode : 'single',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	// 选择调入确认人组件
	$("#recipient").yxselect_user({
		hiddenId : 'recipientId',
		mode : 'single'
	});
	indeptAddSelect();
	outdeptAddSelect();

	$('#outAgencyType').removeClass("validate[required]");
	$('#inAgencyType').removeClass("validate[required]");
	$('.outAgencyType').hide();
	$('.inAgencyType').hide();

	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		url : '?model=asset_assetcard_assetcard&action=listJson',
		isAddAndDel : false,
		param : {
			'id' : $('#assetId').val()
		},
		colModel : [{
			display : '资产Id',
			name : 'assetId',
			process : function($input,row){
				$input.val(row.id);
			},
			type : 'hidden'
		}, {
			display : '卡片编号',
			name : 'assetCode',
			tclass : 'readOnlyTxtNormal',
			validation : {
				required : true
			},
			readonly : true,
			width : 150
		}, {
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '英文名称',
			name : 'englishName',
			type : 'hidden'
		}, {
			display : '购置日期',
			name : 'buyDate',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : '原值',
			name : 'origina',
			type : 'money',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '机器码',
			name : 'sequence',
			process:function($input,row){
				$input.val(row.machineCode);
			},
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '配置',
			name : 'deploy',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '附属设备',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>详细</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window.open('?model=asset_assetcard_equip&action=toPage&assetId='
										+ data.id);
					})
					return $href;
				} else {
					return '<a href="#" >详细</a>';
				}
			}

		}, {
			display : '耐用年限',
			name : 'estimateDay',
			type : 'hidden'
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			type : 'hidden'
		}, {
			display : '月折旧额',
			name : 'monthDepr',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.monthlyDepreciation);
			}
		}, {
			display : '已折旧金额',
			name : 'depreciation',
			type : 'hidden'
		}, {
			display : '残余价值',
			name : 'salvage',
			type : 'hidden'
		}, {
			display : '调出前用途',
			name : 'beforeUse',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.useType);
			}
		}, {
			display : '调入后用途',
			name : 'afterUse',
			tclass : 'txtshort'

		}, {
			display : '调出前存放地点',
			name : 'beforePlace',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.place);
			}
		}, {
			display : '调入后存放地点',
			name : 'afterPlace',
			tclass : 'txtshort'

		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	/**
	 * 验证信息
	 */
	validate({
		"moveDate" : {
			required : true
		},
		"outDeptName" : {
			required : true
		},
		"inDeptName" : {
			required : true
		},
		"proposer" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});
	//切换调拨类型
	$('#alloType').change(function() {
		if ($('#alloType').val() == 'DTD') {
			//显示部门
			$('.inDeptType').show();
			$('.outDeptType').show();
			//设置部门必填
			$('#outDeptName').addClass("validate[required]");
			$('#inDeptName').addClass("validate[required]");
			//部门设置可写
			$('#outDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			$('#inDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			//移除区域下拉列表
			$("#outAgencyName").yxcombogrid_agency("remove");
			$("#inAgencyName").yxcombogrid_agency("remove");
			//取消区域必填
			$('#outAgencyName').removeClass("validate[required]");
			$('#inAgencyName').removeClass("validate[required]");
			//隐藏区域并置空
			$('.inAgencyType > input').val("");
			$('.inAgencyType').hide();
			$('.outAgencyType > input').val("");
			$('.outAgencyType').hide();
			//加载组织机构组件
			indeptAddSelect();
			outdeptAddSelect();
		} else if ($('#alloType').val() == 'ATA') {
			//显示区域
			$('.inAgencyType').show();
			$('.outAgencyType').show();
			//取消部门必填
			$('#outDeptName').removeClass("validate[required]");
			$('#inDeptName').removeClass("validate[required]");
			//设置区域必填
			$('#outAgencyName').addClass("validate[required]");
			$('#inAgencyName').addClass("validate[required]");
			//设置组织机构不可写
			$('#outDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			$('#inDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			//移除组织机构组件
			$("#inDeptName").yxselect_dept("remove");
			$("#outDeptName").yxselect_dept("remove");
			//载入区域下拉列表
			inAgencyAddSelect();
			outAgencyAddSelect();
			//隐藏部门并置空
			$('.inDeptType > input').val("");
			$('.inDeptType').hide();
			$('.outDeptType > input').val("");
			$('.outDeptType').hide();
		}
	});
});
function indeptAddSelect() {
	// 选择调入部门组件
	$("#inDeptName").yxselect_dept({
		hiddenId : 'inDeptId',
		mode : 'single'
	});
}
function outdeptAddSelect() {
	// 选择调出部门组件
	$("#outDeptName").yxselect_dept({
		hiddenId : 'outDeptId',
		mode : 'single'
	});
}

function inAgencyAddSelect() {
	// 选择调出部门组件
	$("#inAgencyName").yxcombogrid_agency({
		hiddenId : 'inAgencyCode'
	});
}

function outAgencyAddSelect() {
	// 选择调出部门组件
	$("#outAgencyName").yxcombogrid_agency({
		hiddenId : 'outAgencyCode',
		gridOptions : {
			showcheckbox : false
		}
	});
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_allocation&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
