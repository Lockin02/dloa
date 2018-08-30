$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=editItemJson',
		type : 'view',
		title :'质检申请明细',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			name : 'productCode',
			display : '物料编号',
			width : 90
		}, {
			name : 'productName',
			display : '物料名称',
			width : 150
		}, {
			name : 'pattern',
			display : '型号',
			width : 100
		}, {
			name : 'unitName',
			display : '单位',
			width : 50
		}, {
			name : 'checkTypeName',
			display : '质检方式',
			width : 80
		}, {
			name : 'qualityNum',
			display : '报检数量',
			width : 80
		}, {
			name : 'assignNum',
			display : '已下达数量',
			width : 80
		}, {
			name : 'standardNum',
			display : '合格数量',
			width : 80
		},{
			name : 'status',
			display : '处理结果',
			width : 80,
			process : function(v){
				switch(v){
					case "0" : return "质检放行";
					case "1" : return "部分处理";
					case "2" : return "处理中";
					case "3" : return "质检完成";
					case "4" : return "未处理";
					default : return "";
				}
			}
		},{
			name : 'dealUserName',
			display : '处理人',
			width : 80
		},{
			name : 'dealTime',
			display : '处理时间',
			width : 140
		},{
			name : 'passReason',
			display : '放行原因',
			width : 140,
			align : 'left'
		}, {
			display : '批次号',
			name : 'batchNum',
			width : 80
		}, {
			display : '序列号',
			name : 'serialName',
			process : function(v){
				if(v!=""){
					return "<a href='javascript:void(0);' onclick='showOpenWin(\"?model=stock_serialno_serialno&action=toViewFormat"+
						"&nos=" + v
						+"\",1,400,600)'>点击查看</a>";
				}else{
					return '无';
				}
			},
			width : 80
		}]
	});
})