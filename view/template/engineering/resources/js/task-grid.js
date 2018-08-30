var show_page = function(page) {
	$("#taskGrid").yxgrid("reload");
};
$(function(){
	$("#taskGrid").yxgrid({
		model : 'engineering_resources_task',
		title : '��Ŀ�豸����',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '����״̬',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_grey.png" title="δ���"/>';break;
					case '1' : return '<img src="images/icon/cicle_yellow.png" title="������"/>';break;
					case '2' : return '<img src="images/icon/cicle_green.png" title="�����"/>';break;
					case '3' : return '<img src="images/icon/cicle_yellow.png" title="��ȷ��"/>';break;
				}
			},
			width : 50
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 50
		}, {
			name : 'taskCode',
			display : '���񵥺�',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_task&action=toView&id="
						+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'receiverName',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'place',
			display : '�豸ʹ�õ�',
			sortable : true,
			width : 120
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 120
		}, {
			name : 'managerName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'createId',
			display : '������Id',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '����������',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���Id',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '�޸�������',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			hide : true
		}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_task&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�豸����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0' || row.status == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
                    showModalWin("?model=engineering_resources_task&action=toOutStock&id=" + row.id ,1);
				}
			}
		},{
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
                    showOpenWin("?model=engineering_resources_task&action=toEditNumber&id=" + row.id ,1,700,1100,row.id);
				}
			}
		},{
			text : '��������',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row && window.confirm("ȷ��Ҫ��������?")) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_task&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								show_page(1);
							} else {
								alert("����ʧ��! ");
							}
						}
					});
				}
			}
		}],
		buttonsEx : [{
			text : '��ӡ',
			icon : 'print',
			action : function(row,rows,idArr) {
				if(row){
					idStr = idArr.toString();
					showModalWin("?model=engineering_resources_task&action=toBatchPrintAlone&id=" + idStr ,1);
				}else{
					alert('������ѡ��һ�ŵ��ݴ�ӡ');
				}
			}
		},{
			text : '��������',
			icon : 'delete',
			action : function(row,rows,idArr) {
				if(row){
					for(var i=0; i< rows.length ; i++){
						if(rows[i].status != '0'){
							alert("���񵥺�Ϊ��" + rows[i].taskCode + "���ķ��������ڴ�������Ѿ�������ɣ����ܳ�����");
							return false;
						}
					}
					if (window.confirm("ȷ��Ҫ��������?")) {
						idStr = idArr.toString();
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_task&action=ajaxdeletes",
							data : {
								id : idStr
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�����ɹ���');
									show_page(1);
								} else {
									alert("����ʧ��! ");
								}
							}
						});
					}
				}else{
					alert('������ѡ��һ����¼');
				}
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'statusArr',
			value : '0,1,3',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '��ȷ��',
				value : '3'
			}, {
				text : '������',
				value : '1'
			}, {
				text : '�����',
				value : '2'
			}, {
				text : 'δ���',
				value : '0,1,3'
			}]
		}],
		searchitems : [{
			display : "���񵥺�",
			name : 'taskCodeSch'
		},{
            display : "������",
            name : 'applyUserSch'
        },{
            display : "������",
            name : 'receiverNameSch'
        },{
			display : "��Ŀ���",
			name : 'projectCodeSch'
		},{
			display : "��Ŀ����",
			name : 'projectNameSch'
		}]
	});
});