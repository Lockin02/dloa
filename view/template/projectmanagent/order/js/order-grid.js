var show_page = function(page) {
	$("#orderGrid").yxgrid("reload");
};
$(function() {
	$("#orderGrid").yxgrid({
		model: 'projectmanagent_order_order',
		title: '�����б�',

           /**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 *
			 * @type Boolean
			 */
			isViewAction : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 *
			 * @type Boolean
			 */
			isEditAction : false,
			/**
			 * �Ƿ���ʾɾ����ť/�˵�
			 *
			 * @type Boolean
			 */
			isDelAction : false,
			showcheckbox : false,
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toViewTab&id='
						+ row.id
                        +'&perm=view'
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},
			{

			text : 'ָ��������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == '2') {
					return true;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toTrackman&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		},

			{

			text : '�رն���',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == '2') {
					return true;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toCluesclose&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}],

		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'orderCode',
			display: '�������',
			sortable: true
		},
		{
			name: 'orderName',
			display: '��������',
			sortable: true
		},
		{
			name: 'deliveryDate',
			display: '��������',
			sortable: true
		},
		{
			name: 'prinvipalName',
			display: '����������',
			sortable: true
		},
		{
			name: 'state',
			display: '����״̬',
			sortable: true,
			process : function(v){
  						if( v == '0'){
  							return "δ�ύ";
  						}else if(v == '1'){
  							return "����";
  						}else if(v == '2'){
  							return "ִ����";
  						}else if(v == '3'){
  							return "�ر�";
  						}else if(v == '4'){
  							return "�����ɶ���";
  						}else if(v == '5'){
  							return "��ǩ��ͬ";
  						}
  					}
		},
		{
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true
		},
		{
			name: 'saleman',
			display: '����Ա',
			sortable: true
		}],

		 /**
		 * ��������
		 */
		searchitems : [{
			display : '��������',
			name : 'orderName'
		}]


	});
});