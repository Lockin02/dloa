$(function() {
	$('#tt').tabs();
	$('.tabs-header').hide();
	$("#stampList").yxeditgrid({
		tableClass : 'form_in_table',
		url : '?model=contract_stamp_stamp&action=listJson',
		type : 'view',
		param : {
			'applyId' : $("#applyId").val(),
			'contractId' : $("#contractId").val(),
			'contractType' : $("#contractType").val()
		},
		isAddOneRow : false,
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��ǰ��¼',
			name��:'nowRecord',
			process : function(v,row){
				if(row.id == $("#stampId").val()){
					return "��";
				}
			},
			width : '50'
		}, {
			display : '��������',
			name : 'applyDate',
			type : 'date'
		}, {
			display : '������',
			name : 'applyUserName'
		}, {
			display : '��������',
			name : 'stampType'
		}, {
			display : '������',
			name : 'stampUserName'
		}, {
			display : '��������',
			name : 'stampDate',
			type : 'date'
		}]
	});
});

//�鿴Դ������ϸ
function viewInfo(){
	var id = $("#contractId").val();
	var contractType = $("#contractType").val();

	switch(contractType){
		case 'HTGZYD-01' :
			var skey;
			$.ajax({
			    type: "POST",
			    url: "?model=contract_outsourcing_outsourcing&action=md5RowAjax",
			    data: { "id" : id },
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + id  + "&skey=" + skey ,1 );
			break;
		case 'HTGZYD-02' :
			var skey;
			$.ajax({
			    type: "POST",
			    url: "?model=contract_other_other&action=md5RowAjax",
			    data: { "id" : id },
			    async: false,
			    success: function(data){
			   	   skey = data;
				}
			});
			var fundType = $("#fundType").val();
			showModalWin("?model=contract_other_other&action=viewTab&id=" + id + "&fundType=" + fundType + "&skey=" + skey ,1 );
			break;
		case 'HTGZYD-03' :

			break;
		default : alert('δ��������');
	}
}