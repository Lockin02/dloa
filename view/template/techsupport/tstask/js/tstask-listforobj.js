var show_page = function(page) {
	$("#tstaskGrid").yxgrid("reload");
};
$(function() {
	$("#tstaskGrid").yxgrid({
		model : 'techsupport_tstask_tstask',
		param : {"objId" : $("#objId").val() , "objType" : $("#objType").val()},
		title : '��ǰ֧��',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
        //����Ϣ
		colModel : [{
			display : 'id',
   			name : 'id',
   			sortable : true,
   			hide : true
		},{
			name : 'formNo',
			display : '���ݱ��',
			sortable : true,
			width : 120
        },{
			name : 'objName',
			display : '������Ŀ����',
			sortable : true
        },{
			name : 'salesman',
			display : '���۸�����',
			sortable : true
        },{
			name : 'trainDate',
			display : '��ѵʱ��',
			sortable : true
        },{
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
        },{
			name : 'cusLinkman',
			display : '�ͻ���ϵ��',
			sortable : true
        },{
			name : 'cusLinkPhone',
			display : '�ͻ���ϵ�绰',
			sortable : true
        },{
			name : 'technicians',
			display : '������Ա',
			sortable : true
        },{
			name : 'status',
			display : '��ǰ״̬',
			sortable : true,
			datacode : 'XMZT'
        },{
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 120
        }],
        toViewConfig :{
        	formWidth : 900,
        	formHeight : 500
        }
	});
});