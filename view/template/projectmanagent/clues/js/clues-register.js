var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};
$(function() {
	$("#registerGrid").yxgrid({
		model : 'projectmanagent_clues_clues',
		param : {
			"createName" : $('#userName').val()
		},
		title : '��ע�������',
		showcheckbox : false, // �Ƿ���ʾcheckbox
		/**
		 * �Ƿ���ʾɾ����ť/�˵�
		 */
		isDelAction : false,
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
		isAddAction : false,
        //��չ��ť
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',

			action : function(row) {
				showOpenWin('?model=projectmanagent_clues_clues&action=toAdd');
			}
		}],

		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_clues_clues&action=toViewTab&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},{

			text : '��ͣ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toPause&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}, {
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=projectmanagent_clues_clues&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {

			text : '��д���ټ�¼',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_track_track&action=toCluesTrack&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}
//		, {
//
//			text : '�̻�����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.status == '0') {
//					return true;
//				}
//				return false;
//			},
//
//			action : function(row) {
//
//				parent.location="?model=projectmanagent_chance_chance&action=toAdd&id="
//				              +row.id
//				              +"&perm=clues";
//
//           }
//		}
		,{

			text : '�ָ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toRecover&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		},{

			text : '�ر�����',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toCluesclose&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'cluesName',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'status',
			display : '״̬',
			sortable : true,
			process : function(v){
//				switch(v){
//					case '0' : "����";break;
//					case '1' : "��ת�̻�";break;
//					case '2' : "�ر�";break;
//				}
				if( v == '0' ){
					return "����";
				}else if(v == '1'){
					return "��ת�̻�";
				}else if(v == '2'){
					return "�ر�";
				}else if(v == '3'){
					return "��ͣ";
				}
			},
			width : 80
//			datacode : 'XSZT'
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 80
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			sortable : true,
			datacode : 'KHLX'
		}, {
			name : 'customerProvince',
			display : '����ʡ��',
			sortable : true,
			width : 80
		}, {
			name : 'trackman',
			display : '����������',
			sortable : true,
			width : 300
		}],
		comboEx : [ {
			text : '״̬',
			key : 'status',
			data : [ {
				text : '����',
				value : '0'
			}, {
				text : '��ת�̻�',
				value : '1'
			},{
				text : '�ر�',
				value : '2'
			},{
				text : '��ͣ',
				value : '3'
			}  ]
		}],
		 /**
		 * ��������
		 */
		searchitems : [{
			display : '��������',
			name : 'cluesName'
		}],
		//��������ҳ����
          toAddConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //���ñ༭ҳ����
          toEditConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //���ò鿴ҳ����
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          }
	});
});