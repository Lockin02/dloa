$(function(){
   	//�ʼ������
	$.woo.yxeditgrid.subclass('woo.showQualityDetail', {
		url : '?model=produce_quality_qualityereportequitem&action=showQualityDetail',
		realDel: false,
		type : 'view',
		title : '�ʼ챨����ϸ',
		colModel: [{
			name: 'reportCodes',
			display: '���浥��',
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
					//չ��
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
			display: '���ϱ��',
			width : 80
		},{
			name: 'productName',
			display: '��������'
		},{
			name: 'pattern',
			display: '����ͺ�'
		},{
			name: 'unitName',
			display: '��λ',
			width : 70
		},{
			name : 'supportNum',
			display : '��������',
			width : 70
		}, {
			name : 'qualitedNum',
			display : '�ϸ���',
			width : 70
		}, {
			name : 'produceNum',
			display : '���ϸ���',
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
					//�鿴���кŵ�
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
			display : '������������',
			width : 90
		}, {
			name : 'receiveNum',
			display : '�ò���������',
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
			display : '�˻�����',
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
					$("#"+g.el.attr("id")+" tbody").after('<tr class="tr_even"><td colspan="99">-- ����������� --</td></tr>');
				}
			}
		}
	});
});

//�ʼ챨��鿴����
function showQualityReport(id){
	showOpenWin("?model=produce_quality_qualityereport&action=toView&id=" + id ,1,700,1000,id);
}