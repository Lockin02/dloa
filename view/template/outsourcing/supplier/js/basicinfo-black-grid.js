var show_page = function(page) {
	$("#basicinfoGrid").yxgrid("reload");
};
$(function() {
	$("#basicinfoGrid").yxgrid({
		model : 'outsourcing_supplier_basicinfo',
		title : '���������',
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		param:{'suppGrade':'4'},
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'suppCode',
			display : '��Ӧ�̱��',
			width:70,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'suppName',
			display : '��Ӧ������',
			width:150,
			sortable : true
		},  {
			name : 'suppTypeName',
			display : '��Ӧ������',
			width:60,
			sortable : true
		},  {
			name : 'blackListReason',
			display : '���������ԭ��',
			width:470,
			align:'left',
			sortable : true
		}],
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + get[p.keyField],1);
				}
			}
		},
		searchitems : [{
						display : "��Ӧ�̱��",
						name : 'suppCode'
					},{
						display : "��Ӧ������",
						name : 'suppName'
					},{
						display : "����",
						name : 'officeName'
					},{
						display : "ʡ��",
						name : 'province'
					},{
						display : "��Ӧ������",
						name : 'suppTypeName'
					},{
						display : "����ʱ��",
						name : 'registeredDate'
					},{
						display : "���˴���",
						name : 'legalRepre'
					},{
						display : "��Ӫҵ��",
						name : 'mainBusiness'
					},{
						display : "�ó���������",
						name : 'adeptNetType'
					},{
						display : "�ó������豸",
						name : 'adeptDevice'
					}],

				sortname : 'suppGrade',
				sortorder : 'ASC'
	});
});