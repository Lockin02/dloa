var show_page = function(page) {
	$("#cluesGrid").yxgrid("reload");
};
$(function() {
	$("#cluesGrid").yxgrid({
		model : 'projectmanagent_clues_clues',
		title : '��ǰ����',
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
		/**
		 * �Ƿ���ʾ��Ӱ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isAddAction : false,
        customCode : 'cluesgrid',
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
		},{

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
		}, {

			text : 'ָ��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_clues_clues&action=toTrackman&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		}, {

			text : 'תΪ�̻�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},

			action : function(row) {
                showOpenWin("?model=projectmanagent_chance_chance&action=toAdd&id="
				              +row.id
				              + "&skey="+row['skey_']
				              +"&perm=clues");

           }
		}, {

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
						+ "&skey="+row['skey_']
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
			name : 'status',
			display : '״̬',
			process : function(v) {
				if (v == 0) {
					return "����";
				}else if(v == 1){
					return "��ת�̻�";
				}else if(v == 2){
					return "�ر�";
				}else if(v == 3){
					return "��ͣ";
				}
			},
			sortable : true,
			width : 70
//			datacode : 'XSZT'
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			process : function(v) {
				return v.substr(0, 10);
			}
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'trackman',
			display : '����������',
			sortable : true,
			width : 300
		}],
		buttonsEx : [{
			name : 'export',
			text : "����",
			icon : 'excel',
			action : function(row) {
				var status = $("#status").val();
				var i = 1;
				var colId = "";
				var colName = "";
				$("#cluesGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window.open("?model=projectmanagent_clues_clues&action=exportExcel&colId="
								+ colId + "&colName=" + colName
								+ "&status=" + status
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
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
		}]
	});
});