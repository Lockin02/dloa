var show_page = function(page) {
	$("#salepersonGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [ {
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
           location='?model=system_saleperson_saleperson&action=mergelist';
		}
	},{
		text : "����",
		icon : 'add',
		action : function(row) {
			showModalWin("?model=system_saleperson_saleperson&action=toAdd"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

		}
	}],
	$("#salepersonGrid").yxgrid({
		model : 'system_saleperson_saleperson',
		title : '���۸����˹���',
//		isViewAction : false,
		isAddAction : false,
//		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=system_saleperson_saleperson&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#salepersonGrid").yxgrid("reload");
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
			sortable : true
		}, {
			name : 'city',
			display : '����',
			sortable : true
		}, {
			name : 'businessBelongName',
			display : '������˾',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����Code',
			sortable : true,
			hide : true
		}, {
			name : 'customerTypeName',
			display : '�ͻ�����',
			sortable : true,
			width : 180
		}, {
            name : 'salesAreaName',
            display : '��������',
            sortable : true
        }, {
            name : 'areaName',
            display : '��������',
            sortable : true
        }, {
            name : 'exeDeptName',
            display : 'ִ������',
            sortable : true
        }, {
			name : 'isUse',
			display : '�Ƿ�����',
			sortable : true,
            process : function(v) {
				if (v == '0') {
					return "����";
				} else{
					return "����";
				}
			}
		}],
        buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// ����״̬���ݹ���
		comboEx : [{
			text: "�Ƿ�����",
			key: 'isUse',
			value : '0',
			data : [{
				'text' : '����',
				'value' : '0'
			}, {
				'text' : '�ر�',
				'value' : '1'
			}]
		},{
			text: "��ҵ�ܼ�",
			key: 'isDirector',
			data : [{
				'text' : '��',
				'value' : '1'
			}, {
				'text' : '��',
				'value' : '0'
			}]
		}],
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
			display : '������˾',
			name : 'businessBelongNameSearch'
		},{
			display : 'ִ������',
			name : 'exeDeptNameSearch'
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