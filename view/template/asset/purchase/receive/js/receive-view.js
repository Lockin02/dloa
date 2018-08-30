$(function() {
	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
//		delTagName : 'isDelTag',
		type : 'view',
		url : '?model=asset_purchase_receive_receiveItem&action=listJson',
		param : {
			receiveId : $("#receiveId").val()
		},
		colModel : [{
			display : '资产名称',
			name : 'assetName'
		}, {
			display : '规格',
			name : 'spec'
		}, {
			display : '数量',
			name : 'checkAmount',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'amount',
			tclass : 'txtshort',
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '配置',
			name : 'deploy',
			tclass : 'txt'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// 判断是否显示关闭按钮
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});