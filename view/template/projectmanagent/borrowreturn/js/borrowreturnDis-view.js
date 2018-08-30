$(function() {
	// 产品清单
	$("#productInfo").yxeditgrid({
		url:'?model=projectmanagent_borrowreturn_borrowreturnDisequ&action=listJson',
		tableClass : 'form_in_table',
		type:'view',
		title : '物料清单',
		param:{
        	'disposeId' : $("#id").val()
        },
		colModel : [{
			display : '物料编号',
			name : 'productNo'
		},{
			display : '物料名称',
			name : 'productName'
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '待归还数量',
			name : 'disposeNum',
			width : 80
		}, {
			display : '已归还数量',
			name : 'backNum',
			width : 80
		}, {
			display : '待出库数量',
			name : 'outNum',
			width : 80
		}, {
			display : '已出库数量',
			name : 'executedNum',
			width : 80
		}, {
			name : 'serialName',
			display : '序列号'
		}]
	});

	//显示质检情况
	$("#showQualityReport").showQualityDetail({
		param : {
			"objId" : $("#qualityObjId").val(),
			"objType" : 'ZJSQYDGH'
		}
	});
});