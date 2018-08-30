$(function() {
	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		url : '?model=asset_daily_allocationitem&action=listJson',
		type : 'view',
		param : {
			allocateID : $("#allocateID").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}, {
			display : '购置日期',
			name : 'buyDate',
			type : 'date'
		}, {
			display : '原值',
			name : 'origina',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort'
		}, {
			display : '机器码',
			name : 'sequence',
			tclass : 'txtshort'
		}, {
			display : '配置',
			name : 'deploy',
			tclass : 'txtshort'
		}, {
			display : '附属设备',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&assetId='
						+ data.assetId + '\')">详细</a>'
			}

		}, {
			display : '耐用年限',
			name : 'estimateDay',
			tclass : 'txtshort'
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			tclass : 'txtshort'
		}, {
			display : '已折旧金额',
			name : 'depreciation',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});

});

$(function() {
	$(".outAgencyType").hide();
	$(".inAgencyType").hide();
	$(".outDeptType").hide();
	$(".inDeptType").hide();
	switch ($('#alloType').val()) {
		case 'DTD' :
			$(".outDeptType").show();
			$(".inDeptType").show();
			break;
		case 'ATA' :
			$(".outAgencyType").show();
			$(".inAgencyType").show();
			break;
	}
})