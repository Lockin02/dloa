// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".trainGrid").yxegrid('reload');
};
$(function() {
			$(".trainGrid").yxegrid({
						// �������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=engineering_serviceContract_serviceContract&action=pageJson',
						model : 'engineering_serviceTrain_servicetrain',
//						objName : 'serviceContract',
						isToolBar : false,
						title : '��ѵ�ƻ�',
						// action : 'pageJson',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
//								{
//									display : '��ͬ���',
//									name : 'contractNo',
//									sortable : true
//								},
//								{
//									display : '��ͬ����',
//									name : 'contractName',
//									editor : {
//										type : 'text'
//									},
//									sortable : true
//								},
								{
									display : '��ѵ��ʼ����',
									name : 'beginDT'
								},
								{
									display : '��ѵ��������',
									name : 'endDT'
								},
								{
									display : '��ѵ����',
									name : 'traNum'
								},
								{
									display : '��ѵ�ص�',
									name : 'adress'
								},
								{
									display : '��ѵ����',
									name : 'content'
								},
								{
									display : '��ѵʦҪ��',
									name : 'trainer'
								},
								{
									display : '�Ƿ����',
									name : 'isOver'
								},
								{
									display : '����ʱ��',
									name : 'overDT'
								}
//									{
//									display : '�ͻ�����',
//									name : 'Name',
//									sortable : true,
//									sortname : 'c.Name',
//									editor : {
//										defVal : 'Ĭ�Ͽͻ�����'
//									}
//								}, {
//									display : '��������',
//									name : 'AreaLeader',
//									sortable : true
//								}, {
//									display : '���۹���ʦ',
//									editor : {
//										type : 'text'
//									},
//									name : 'SellMan',
//									sortable : true
//								}, {
//									display : '�ͻ�����',
//									name : 'TypeOne',
//									hiddenName : 'TypeOneName',// �����ύ������ֵ
//									datacode : 'KHLX',// �����ֵ����
//									editor : {
//										type : 'combo',
//										// Ĭ��ѡ���һ��
//										defValIndex : 1
//
//									},
//									sortable : true
//								}, {
//									display : 'ʡ��',
//									name : 'Prov',
//									datacode : 'PROVINCE',
//									sortable : true
//								}
								],
						// ��������
						searchitems : [{
									display : '��ͬ����',
									name : 'contractName'
								}, {
									display : '��ѵ�ص�',
									name : 'adress',
									isdefault : true
								}],
						// title : '�ͻ���Ϣ',
						// ҵ���������
						boName : '�ͻ�',
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					});

		});