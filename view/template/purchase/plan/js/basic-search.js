var show_page = function() {
	$("#basicGrid").yxeditgrid("reload");
};
$(function() {
	$("#productCode").yxcombogrid_product({
		isDown : true
	});
	
	$("#search").click(function(){
		search();
	});
})
function search(){
	$("#basicGrid").yxeditgrid('remove').yxeditgrid({
		url : '?model=purchase_plan_basic&action=searchList',
		action : 'post', 
		isAddAndDel : false,
		type : 'view',
		param :��{
			productNumb : $("#productCode").val()
		},
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			type: 'hidden'
		},{
			display : '�ɹ�������',
			name : 'planNumb',
			sortable : true,
			width : 120,
			process :function(v,row){
				return "<a href='javascript:void(0)' style='color:blue' onclick='showModalWin(\"?model=purchase_plan_basic&action=read&id="+row.id+
				"&purchType=produce&skey="+row.skey_+"\",1)'>"+v+"</a>";
			}
		}, {
			display : '���κ�',
			name : 'batchNumb',
			sortable : true,
			width : 120
		},{
			display : '������',
			name : 'createName',
			sortable : true,
			width : 80
		},{
			display : '���벿��',
			name : 'department',
			sortable : true,
			width : 100
		},{
			display : '����ʱ�� ',
			name : 'sendTime',
			sortable : true,
			width : 100
		},{
			display : '����ԭ�� ',
			name : 'applyReason',
			sortable : true,
			width : 200
		},{
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true,
			width : 80
		}]
	});
}