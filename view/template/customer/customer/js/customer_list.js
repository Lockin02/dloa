// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$(".customerGrid").yxgrid('reload');
};
$(function() {
			$(".customerGrid").yxgrid({
						// �������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						// param : {
						// status : 1
						// },
						model : 'customer_customer_customer',
						// showcheckbox:false,
						// action : 'pageJson',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},  {
									display : '�ͻ����',
									name : 'objectCode',
									sortable : true
								}, {
									display : '�ͻ�����',
									name : 'Name',
									sortable : true,
									// ���⴦���ֶκ���
									process : function(v, row) {
										return row.Name;
									}
								}, {
									display : '��������',
									name : 'AreaLeader',
									sortable : true
								}, {
									display : '���۹���ʦ',
									name : 'SellMan',
									sortable : true
								}, {
									display : '�ͻ�����',
									name : 'TypeOne',
									datacode : 'KHLX',// �����ֵ����
									sortable : true
								}, {
									display : 'ʡ��',
									name : 'Prov',
									sortable : true
								}],
						comboEx : [{
							text : "�ͻ�����",
							key : 'TypeOne',
							datacode : 'KHLX'
								// value : 'XTS'
								// data : [{
								// text : 'A���ͻ�',
								// value : 'A'
								// }, {
								// text : 'B���ͻ�',
								// value : 'B'
								// }]
							}, {
							text : "�ͻ�����1",
							key : 'TypeOne',
							datacode : 'KHLX'
								// value : 'XTS'
								// data : [{
								// text : 'A���ͻ�',
								// value : 'A'
								// }, {
								// text : 'B���ͻ�',
								// value : 'B'
								// }]
							}],
						// ��չ��ť
						buttonsEx : [{
									name : 'Add',
									// hide : true,
									text : "��չ��ť1",
									icon : 'add',
									/**
									 * row ���һ��ѡ�е��� rows ѡ�е��У���ѡ�� rowIds
									 * ѡ�е���id���� grid ��ǰ���ʵ��
									 */
									action : function(row, rows, rowIds, grid) {
										$.showDump(rows)
									}
								}, {
									separator : true
								}, {
									name : 'Delete',
									text : "��չ��ť2",
									icon : 'delete',
									action : function() {
										alert(333)
									}
								}],
						// ��չ�Ҽ��˵�
						menusEx : [{
									text : '��չ�˵�',
									hide : true,
									/**
									 * row ���һ��ѡ�е��� rows ѡ�е��У���ѡ�� rowIds
									 * ѡ�е���id���� grid ��ǰ���ʵ��
									 */
									action : function(row, rows, rowIds, grid) {
										$.showDump(rowIds);
									}
								}],
						// ��������
						searchitems : [{
									display : '�ͻ�����',
									name : 'customerType'
								}, {
									display : '�ͻ�����',
									name : 'Name',
									isdefault : true
								}],
						// title : '�ͻ���Ϣ',
						// ҵ���������
						boName : '�ͻ�',
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC",
						// �����չ��Ϣ
						toAddConfig : {
							text : 123,
							toAddFn : function() {
								alert(123)
							}
						}
					});

		});