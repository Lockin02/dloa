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
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '��������',
			name : 'buyDate',
			type : 'date'
		}, {
			display : 'ԭֵ',
			name : 'origina',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort'
		}, {
			display : '������',
			name : 'sequence',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'deploy',
			tclass : 'txtshort'
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&assetId='
						+ data.assetId + '\')">��ϸ</a>'
			}

		}, {
			display : '��������',
			name : 'estimateDay',
			tclass : 'txtshort'
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			tclass : 'txtshort'
		}, {
			display : '���۾ɽ��',
			name : 'depreciation',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '��ע',
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