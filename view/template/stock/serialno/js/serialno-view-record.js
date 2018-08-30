function searchSer(){
	var serialno = $('#sequence').val();
	if( serialno=='' ){
		alert('序列号不允许为空')
	}else{
		itemAddFun(serialno);
	}
}
function itemAddFun(serialno){
	$("#itemTable").yxeditgrid("remove");
	$("#itemTable").yxeditgrid({
		url : '?model=stock_serialno_serialno&action=listBySerialno',
		type: 'view',
		param : {
			serialno : serialno
		},
		isAddOneRow : false,
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '日期',
			name : 'auditDate',
			tclass : 'txt'
		}, {
			display : '事件',
			name : 'type',
			process : function(e,data){
				if( e=='outstock' ){
					return '<a title="详细记录" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">出库</a>';
				}else if( e=='backStock' ){
					return '<a title="详细记录" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">退库</a>';
				}else if( e=='allocation' ){
					return '<a title="详细记录" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">借用</a>';
				}else if( e=='backAllocation' ){
					return '<a title="详细记录" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">归还</a>';
				}else if( e=='instock' ){
					return '<a title="详细记录" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">入库</a>'
				}
			},
			tclass : 'txtshort'
		}, {
			display : '操作人',
			name : 'createName',
			tclass : 'txtshort'
//		}, {
//			display : '备注',
//			name : 'remark',
//			tclass : 'txt'
		}],
		isAddOneRow : false
	});
}