var show_page = function(page) {
	$("#attrGrid").yxgrid("reload");
};
$(function() {

			$("#attrGrid").yxgrid({

						model : 'hr_inventory_attr&action=page',

						title : '�̵������',
						toViewConfig : {
							action : "toView"
						},toEditConfig : {
							action : "toEdit"
						},
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'attrName',
									display : '��������',
									sortable : true
								}, {
									name : 'attrType',
									display : '��������',
									process : function(row, v) {
										v = v == 0 ? "�ı���" : "������";
										return v;
									},
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true
								}],
						searchitems : [{
									display : "��������",
									name : 'attrName'
								}],
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"

					});
		});