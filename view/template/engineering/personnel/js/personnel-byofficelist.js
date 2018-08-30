// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".byOfficeGrid").yxgrid("reload");
};
$(function() {
	$(".byOfficeGrid").yxgrid({
		model: 'engineering_personnel_personnel',
		action : 'pageJsonOff',
		param : { 'rprocode' : $('#procode').val() },
		title: "Ա����Ϣ  " + $('#provinceName').val(),
		showcheckbox: false,
		isEditAction: false,
		isAddAction: false,
		isDelAction: false,
		isViewAction: false,
		comboEx: [{
			text: "�Ƿ�����Ŀ",
			key: 'isProj',
			data : [{
				text : '����Ŀ',
				value : '2'
				}, {
				text : '��Ŀ��',
				value : '1'
				}
			]
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_personnel_personnel&action=viewTab&id=" + row.id + "&perm=view");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "��ʼ��",
			icon : 'edit',
			/**
			 * row ���һ��ѡ�е��� rows ѡ�е��У���ѡ�� rowIds
			 * ѡ�е���id���� grid ��ǰ���ʵ��
			 */
			action : function(row) {
				location='?model=engineering_personnel_personnel&action=byOfficeList'
			}
		}],

		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			display: '����',
			name: 'userName',
			sortable: true
			// ���⴦���ֶκ���
		},
		{
			display: '����',
			name: 'officeName',
			sortable: true,
			width : 70
		},
		{
			display: '��ǰ��Ŀ',
			name: 'currentProName',
			sortable: true,
			process : function(v) {
					if(v==""){
						return "��";
					}else{
						return v;
				}
			},
			width: 180
		},
		{
			display: '��Ԥ�ƣ�����',
			name: 'proEndDate',
			sortable: true,
			width: 90,
			process : function(v) {
					if(v=="0000-00-00" || v==""){
						return "��";
					}else{
						return v;
				}
			}
		},
		{
			display: '���ڵ�',
			name: 'locationName',
			sortable: true,
			width: 50
		},
		{
			display: '����',
			name: 'attendStatus',
			datacode :  'KQZT',
			sortable: true,
			width: 40
		}],
		// ��������
		searchitems: [{
			display: '����',
			name: 'userName'
		}],
		// Ĭ������˳��
		sortorder: "ASC"

	});
});