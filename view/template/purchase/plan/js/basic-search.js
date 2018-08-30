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
		param :　{
			productNumb : $("#productCode").val()
		},
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			type: 'hidden'
		},{
			display : '采购申请编号',
			name : 'planNumb',
			sortable : true,
			width : 120,
			process :function(v,row){
				return "<a href='javascript:void(0)' style='color:blue' onclick='showModalWin(\"?model=purchase_plan_basic&action=read&id="+row.id+
				"&purchType=produce&skey="+row.skey_+"\",1)'>"+v+"</a>";
			}
		}, {
			display : '批次号',
			name : 'batchNumb',
			sortable : true,
			width : 120
		},{
			display : '申请人',
			name : 'createName',
			sortable : true,
			width : 80
		},{
			display : '申请部门',
			name : 'department',
			sortable : true,
			width : 100
		},{
			display : '申请时间 ',
			name : 'sendTime',
			sortable : true,
			width : 100
		},{
			display : '申请原因 ',
			name : 'applyReason',
			sortable : true,
			width : 200
		},{
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true,
			width : 80
		}]
	});
}