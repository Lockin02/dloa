var show_page = function(page) {
	$("#produceGrid").yxgrid("reload");
};
$(function() {
			$("#produceGrid").yxgrid({
						model : 'report_report_produce',
						title : '����������',
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
									width : 180
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