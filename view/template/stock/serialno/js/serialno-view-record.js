function searchSer(){
	var serialno = $('#sequence').val();
	if( serialno=='' ){
		alert('���кŲ�����Ϊ��')
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
			display : '����',
			name : 'auditDate',
			tclass : 'txt'
		}, {
			display : '�¼�',
			name : 'type',
			process : function(e,data){
				if( e=='outstock' ){
					return '<a title="��ϸ��¼" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">����</a>';
				}else if( e=='backStock' ){
					return '<a title="��ϸ��¼" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">�˿�</a>';
				}else if( e=='allocation' ){
					return '<a title="��ϸ��¼" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">����</a>';
				}else if( e=='backAllocation' ){
					return '<a title="��ϸ��¼" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">�黹</a>';
				}else if( e=='instock' ){
					return '<a title="��ϸ��¼" href="#" onclick="javascript:showThickboxWin(\'?model=stock_serialno_serialno&action=toViewDetail&id='
						+ data.id
						+ "&type="
						+ data.type
						+ "&contractType="
						+ data.contractType
						+ "&contractId="
						+ data.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">���</a>'
				}
			},
			tclass : 'txtshort'
		}, {
			display : '������',
			name : 'createName',
			tclass : 'txtshort'
//		}, {
//			display : '��ע',
//			name : 'remark',
//			tclass : 'txt'
		}],
		isAddOneRow : false
	});
}