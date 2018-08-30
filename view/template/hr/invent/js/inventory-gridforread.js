var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};

$(function() {

	buttonsArr = [
        {
			name : 'view',
			text : "��ѯ",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=hr_invent_inventory&action=toSearch"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }
    ];

	//��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_invent_inventory&action=toImport"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});

	$("#inventoryGrid").yxgrid({
		model : 'hr_invent_inventory',
		action : 'pageJsonForRead',
		title : '��Ա�̵���Ϣ',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true
			}, {
//				name : 'userAccount',
//				display : 'Ա���˺�',
//				sortable : true
//			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_invent_inventory&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
//				name : 'companyType',
//				display : '��˾����',
//				sortable : true
//			}, {
//				name : 'companyName',
//				display : '��˾����',
//				sortable : true
//			}, {
				name : 'deptNameS',
				display : '��������',
				sortable : true
			}, {
//				name : 'deptNameT',
//				display : '��������',
//				sortable : true
//			}, {
				name : 'entryDate',
				display : '��ְ����',
				sortable : true
			}, {
				name : 'inventoryDate',
				display : '�̵�����',
				sortable : true
			}, {
				name : 'alternative',
				display : '��ְλ���г��������',
				sortable : true
			}, {
				name : 'matching',
				display : '�ֹ�������������ְλ��ƥ���',
				sortable : true
			}, {
				name : 'critical',
				display : 'Ա���ؼ���',
				sortable : true
			}, {
				name : 'isCore',
				display : '���ı����˲�',
				sortable : true
			}, {
				name : 'recruitment',
				display : '�г���Ƹ�Ѷ�',
				sortable : true
			}, {
				name : 'recruitment',
				display : '�Լ�Ч������������',
				sortable : true
			}, {
				name : 'examine',
				display : '��һ���ȿ����Ƿ��ź�5%',
				sortable : true
			}, {
				name : 'preEliminated',
				display : 'Ԥ��̭',
				sortable : true
			}, {
				name : 'remark',
				display : '�Ƿ������ʧ',
				sortable : true
			}, {
				name : 'adjust',
				display : '�Դ�Ա���ĺ�����������',
				sortable : true
			}],

//		buttonsEx : buttonsArr,
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_invent_inventory&action=toView&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Ա�����',
			name : 'userNoM'
		}, {
			display : 'Ա������',
			name : 'userNameM'
		}, {
//			display : '��˾����',
//			name : 'companyName'
//		}, {
			display : '��������',
			name : 'deptName'
		}],
		sortorder : "DESC",
		sortname : "id",
		title : 'Ա���̵���Ϣ'
	});
});