$(function(){
   	//质检情况表
	$.woo.yxeditgrid.subclass('woo.showQualityDetail', {
		url : '?model=produce_quality_qualityereportequitem&action=showQualityDetail',
		realDel: false,
		type : 'view',
		title : '质检报告详细',
		colModel: [{
			name: 'reportCodes',
			display: '报告单号',
			process : function(v, row) {
				var codeArr = v.split(',');
				if(codeArr.length == 1){
					return "<a href='javascript:void(0);' onclick='showQualityReport("+ row.reportIds +")'>" + v + "</a>";
				}else{
					var idArr = row.reportIds.split(',');

					var newIdArr = [];
					var newCodeArr = [];
					var showArr = [];
					for(var i=0;i<idArr.length;i++){
						if($.inArray(idArr[i],newIdArr) == -1){
							newIdArr.push(idArr[i]);
							newCodeArr.push(codeArr[i]);
						}
					}
					//展现
					for(var i=0;i<newIdArr.length;i++){
						showArr.push("<a href='javascript:void(0);' onclick='showQualityReport("+ newIdArr[i] +")'>" + newCodeArr[i] + "</a>");
					}

					return showArr.toString();
				}
     		}
		},{
			name : 'reportIds',
			display : 'reportIds',
			type : 'hidden'
		}, {
			name: 'productCode',
			display: '物料编号',
			width : 80
		},{
			name: 'productName',
			display: '物料名称'
		},{
			name: 'pattern',
			display: '规格型号'
		},{
			name: 'unitName',
			display: '单位',
			width : 70
		},{
			name : 'supportNum',
			display : '报检数量',
			width : 70
		}, {
			name : 'qualitedNum',
			display : '合格数',
			width : 70
		}, {
			name : 'produceNum',
			display : '不合格数',
			process : function(v) {
				if(v*1 != 0){
					return "<a href='javascript:void(0);' style='color:red;'>" + v + "</a>";
				}else{
					return v;
				}
     		},
			width : 70,
			event : {
				click : function(e){
					//查看序列号的
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					showThickboxWin("?model=produce_quality_serialno&action=toDealView"
						+"&relDocId="+ g.getCmpByRowAndCol(rowNum, 'relItemIds').val()
						+"&relDocType=qualityEreport"
						+"&productId="+ g.getCmpByRowAndCol(rowNum, 'productId').val()
						+"&productCode="+ g.getCmpByRowAndCol(rowNum, 'productCode').val()
						+"&productName="+ g.getCmpByRowAndCol(rowNum, 'productName').val()
						+"&pattern="+ g.getCmpByRowAndCol(rowNum, 'pattern').val()
						+"&productNum=" + $(this).val()
						+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		}, {
			name : 'relItemIds',
			display : 'relItemIds',
			type : 'hidden'
		}, {
			name : 'passNum',
			display : '正常接收数量',
			width : 90
		}, {
			name : 'receiveNum',
			display : '让步接收数量',
			width : 90,
			process : function(v){
				if(v*1 != 0){
					return "<span class='red'>" + v + "</span>";
				}else{
					return v;
				}
			}
		}, {
			name : 'backNum',
			display : '退回数量',
			width : 70,
			process : function(v){
				if(v*1 != 0){
					return "<span class='red'>" + v + "</span>";
				}else{
					return v;
				}
			}
		}],
		event : {
			'reloadData': function(e,g,data) {
				if(!data){
					$("#"+g.el.attr("id")+" tbody").after('<tr class="tr_even"><td colspan="99">-- 暂无相关数据 --</td></tr>');
				}
			}
		}
	});
});

//质检报告查看方法
function showQualityReport(id){
	showOpenWin("?model=produce_quality_qualityereport&action=toView&id=" + id ,1,700,1000,id);
}