$(function() {
	var userId = $('#userId').val();
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonMyPj',
		title : '�ҵ���Ŀ',
		// ��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action :function(row) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
						+ "&id="
						+ row.id);
				}else{
					alert("��ѡ��һ������");
				}
			}
		},
		{
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if( row.managerId == userId ){
					if (row.status != '7' && row.status != '8' &&row.status != '2'&&row.status != '6' &&row.status != '9' ) {
						return true;
					}
				}
				return false;
			},
			action : function(row) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
						+ "&id="
						+ row.id);
				}else{
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6'&& row.managerId == userId ) {
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
		},
		{
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '9'&& row.managerId == userId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷȷ�Ͻ���?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_project_esmproject&action=receive",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('���ճɹ���');
								$("#esmprojectGrid").esmprojectgrid("reload");
							}else{
								alert("����ʧ��");
							}
						}
					});
				}
			}
		},
		{
			text : '��д��չ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6'&& row.managerId == userId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=toProgress"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 200 + "&width=" + 600
					);
			}
		},
		{
			text : '�ر�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6'&& row.managerId == userId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=closeProject"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 200 + "&width=" + 600
					);
			}
		},
		{
			name : 'exam',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if( row.managerId == userId ){
					if (row.status == '1'||row.status == '10'||row.status == '4') {
						return true;
					}
				}
				return false;
			},
			action : function(row) {
				if(row.projectCode == "" || row.officeName ==""||row.planDateStart == "" || row.planDateClose == "" ){
					alert('�����Ϣδ��д���,������д');
					return false;
				}else{
					location = 'controller/engineering/project/ewf_index.php?actTo=ewfSelect&formName=������Ŀ���&examCode=oa_esm_project&billId='
						+ row.id
				}
			}
		}]

	});

});