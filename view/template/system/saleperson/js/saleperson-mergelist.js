var show_page = function(page) {
	$("#mergeGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [  {
		text : "���ݵ���",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=system_saleperson_saleperson&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	},{
		text : "�л���ͼ",
		icon : 'view',
		action : function(row) {
           location='?model=system_saleperson_saleperson';
		}
	},{
		text : "����",
		icon : 'add',
		action : function(row) {
			showModalWin("?model=system_saleperson_saleperson&action=toAdd"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

		}
	}],
	$("#mergeGrid").yxgrid({
		model : 'system_saleperson_saleperson',
		action : 'mergeJson',
		title : '���۸����˹���',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : "�鿴",
			icon : 'view',
			action : function(row) {
				showModalWin("?model=system_saleperson_saleperson&action=toViewall"
				        + "&id="
				        + row.id
				        + "&ids="
				        + row.ids
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

			}
		},{
			text : "�޸�",
			icon : 'edit',
			action : function(row) {
				showModalWin("?model=system_saleperson_saleperson&action=toEditAll"
				        + "&id="
				        + row.id
				        + "&ids="
				        + row.ids
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ�� ��"+row.personName+"�� ��������Ϣ��"))) {
					$.ajax({
						type : "POST",
						url : "?model=system_saleperson_saleperson&action=ajaxdeletesPerson",
						data : {
							personId : row.personId
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#mergeGrid").yxgrid("reload");
							}else{
							   alert('ɾ��ʧ�ܣ�');
							}
						}
					});
				}
			}
		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'ids',
			name : 'ids',
			sortable : true,
			hide : true
		}, {
			name : 'personName',
			display : '����������',
			sortable : true
		}, {
			name : 'isDirector',
			display : '��ҵ�ܼ�',
			sortable : true,
			process : function (v,row){
			   if(v == '0'){
			      return "��";
			   }else if(v == '1'){
			      return "��";
			   }
			},
			width : 50
		}, {
			name : 'personId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'deptName',
			display : '�����˲���',
			sortable : true
		}, {
			name : 'deptId',
			display : '�����˲���id',
			sortable : true,
			hide : true
		}, {
			name : 'country',
			display : '����',
			sortable : true
		}, {
			name : 'province',
			display : 'ʡ��',
			sortable : true,
			width : 150
		}, {
			name : 'city',
			display : '����',
			sortable : true,
			width : 180
		}, {
			name : 'businessBelongName',
			display : '������˾',
			sortable : true
		}, {
			name : 'customerTypeName',
			display : '�ͻ�����',
			sortable : true,
			width : 300
		}, {
            name : 'exeDeptName',
            display : 'ִ������',
            sortable : true
        }],
        buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// ����״̬���ݹ���
//		comboEx : [{
//			text: "�Ƿ�����",
//			key: 'isUse',
//			value : '0',
//			data : [{
//				'text' : '����',
//				'value' : '0'
//			}, {
//				'text' : '�ر�',
//				'value' : '1'
//			}]
//		},{
//			text: "��ҵ�ܼ�",
//			key: 'isDirector',
//			data : [{
//				'text' : '��',
//				'value' : '1'
//			}, {
//				'text' : '��',
//				'value' : '0'
//			}]
//		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���۸�����',
			name : 'personNameSearch'
		},{
			display : '��������',
			name : 'areaNameSearch'
		},{
			display : '�ͻ�����',
			name : 'customerTypeSearch'
		},{
			display : '����',
			name : 'countrySearch'
		},{
			display : 'ʡ��',
			name : 'provinceSearch'
		},{
			display : '����',
			name : 'citySearch'
		}]
	});
});