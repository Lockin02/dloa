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
			display : '当前记录',
			name　:'nowRecord',
			process : function(v,row){
				if(row.id == $("#stampId").val()){
					return "√";
				}
			},
			width : '50'
		}, {
			display : '申请日期',
			name : 'applyDate',
			type : 'date'
		}, {
			display : '申请人',
			name : 'applyUserName'
		}, {
			display : '盖章类型',
			name : 'stampType'
		}, {
			display : '盖章人',
			name : 'stampUserName'
		}, {
			display : '盖章日期',
			name : 'stampDate',
			type : 'date'
		}]
	});
});

//查看源单据详细
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
		default : alert('未定义类型');
	}
}