var show_page = function(page) {
	$("#produceGrid").yxgrid("reload");
};
$(function() {
			$("#produceGrid").yxgrid({
						model : 'report_report_produce',
						title : '����������',
						isViewAction : true,
						isEditAction : false,
						isDelAction : false,
						 showcheckbox : false,
						isAddAction : false,
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									display : '��Ʒ��',
									sortable : true,
									width : 180,
									process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=report_report_produce&action=toView&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					}
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true,
									width : 220
								}, {
									name : 'createName',
									display : '������',
									sortable : true
								}, {
									name : 'createTime',
									display : '��������',
									sortable : true
								}],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "��Ʒ��",
									name : 'name'
								}]
					});
		});