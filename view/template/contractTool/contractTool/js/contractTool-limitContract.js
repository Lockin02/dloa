var show_page = function(page) {
	$("#contractToolGrid").yxgrid("reload");
};
$(function() {
	$("#contractToolGrid").yxgrid({
				model : 'contractTool_contractTool_authorize',
				action : "pagejsons",
				title : 'Ȩ������',
				param :{
					'dir' : 'ASC'
				},
				isViewAction : false,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'userCode',
							display : '�û����',
							sortable : true,
							width : 200,
							hide : true
						}, {
							name : 'userName',
							display : '�û�����',
							sortable : true,
							width : 100
						}, {
							name : 'limitInfo',
							display : '����Ȩ�ޱ���',
							sortable : true,
							width : 200,
							hide : true
						}, {
							name : 'limitInfos',
							display : '����Ȩ��',
							sortable : true,
							width : 400
						}],
				searchitems : [{
					display : "�û�����",
					name : 'userName'
				},{
					display : "����Ȩ��",
					name : 'limitInfo'
				}]
			});
});