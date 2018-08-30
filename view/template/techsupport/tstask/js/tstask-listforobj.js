var show_page = function(page) {
	$("#tstaskGrid").yxgrid("reload");
};
$(function() {
	$("#tstaskGrid").yxgrid({
		model : 'techsupport_tstask_tstask',
		param : {"objId" : $("#objId").val() , "objType" : $("#objType").val()},
		title : '售前支持',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
        //列信息
		colModel : [{
			display : 'id',
   			name : 'id',
   			sortable : true,
   			hide : true
		},{
			name : 'formNo',
			display : '单据编号',
			sortable : true,
			width : 120
        },{
			name : 'objName',
			display : '关联项目名称',
			sortable : true
        },{
			name : 'salesman',
			display : '销售负责人',
			sortable : true
        },{
			name : 'trainDate',
			display : '培训时间',
			sortable : true
        },{
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
        },{
			name : 'cusLinkman',
			display : '客户联系人',
			sortable : true
        },{
			name : 'cusLinkPhone',
			display : '客户联系电话',
			sortable : true
        },{
			name : 'technicians',
			display : '技术人员',
			sortable : true
        },{
			name : 'status',
			display : '当前状态',
			sortable : true,
			datacode : 'XMZT'
        },{
			name : 'createTime',
			display : '申请时间',
			sortable : true,
			width : 120
        }],
        toViewConfig :{
        	formWidth : 900,
        	formHeight : 500
        }
	});
});