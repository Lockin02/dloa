$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJson',
		title : '���´���Ŀ',
		comboEx: [{
			text: "��Ŀ״̬",
			key: 'status',
			data : [{
				text : '����',
				value : '1'
				}, {
				text : '������',
				value : '2'
				}, {
				text : '���',
				value : '4'
				}, {
				text : 'ִ����',
				value : '6'
				}, {
				text : '���',
				value : '7'
				}, {
				text : '�ر�',
				value : '8'
				}, {
				text : '������',
				value : '9'
				}, {
				text : '�ѽ���',
				value : '10'
				}
			]
		},{
			text: "��Ŀ����",
			key: 'projectType',
			datacode: 'GCXMXZ'
		},{
			text: "����",
			key: 'officeId',
			data : [{
				text : ' �������´� ',
				value : '46'
				}, {
				text : ' �ɶ����´� ',
				value : '45'
				}, {
				text : ' ��ɳ���´� ',
				value : '44'
				}, {
				text : ' �Ͼ����´� ',
				value : '43'
				}, {
				text : ' �������´� ',
				value : '42'
				}, {
				text : ' ���ݰ��´� ',
				value : '41'
				}
			]
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1' || row.status == '4'  || row.status == '9'  || row.status == '10' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=openTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}
		, {
			name : 'desmanager',
			text : 'ָ����Ŀ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '2'||row.status == '7'||row.status == '8') {
					return false;
				}
			},
			action : function(row) {
				if(row.managerName != ''){
					if(confirm("ȷ��Ҫ�����Ŀ����")){
						showThickboxWin("?model=engineering_project_esmproject&action=designateManager"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 250 + "&width=" + 600);
					}
				}else{
					showThickboxWin("?model=engineering_project_esmproject&action=designateManager"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 250 + "&width=" + 600);
				}

			}
		}]
	});

});