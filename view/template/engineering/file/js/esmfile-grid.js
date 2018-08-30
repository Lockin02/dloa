var show_page = function() {
	$("#esmfileGrid").yxgrid("reload");
};
$(function() {
	// �ر���ҳʱ���ص��¼�
	$(window).bind('beforeunload',function(){
		window.opener.loadGrid();
	});

	$("#tree").yxtree({
		url: '?model=engineering_file_esmfiletype&action=getTree&projectId=' + $("#projectId").val(),
		event: {
			node_click: function(event, treeId, treeNode) {
				var esmfileGrid = $("#esmfileGrid").data('yxgrid');
				esmfileGrid.options.param['typeId'] = treeNode.id;
				esmfileGrid.reload();
				$("#typeId").val(treeNode.id);
			}
		}
	});
	$("#esmfileGrid").yxgrid({
		model: 'engineering_file_esmfile',
		param: {
			projectId: $("#projectId").val()
		},
		title: '���̸�����',
		isViewAction: false,
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'serviceNo',
			display: 'ҵ����',
			sortable: true,
			hide: true
		}, {
			name: 'serviceType',
			display: 'ҵ������',
			sortable: true,
			hide: true
		}, {
			name: 'typeName',
			display: '�ļ���������',
			sortable: true,
			width: '150'
		}, {
			name: 'originalName',
			display: 'ԭʼ�ļ���',
			sortable: true,
			width: '150'

		}, {
			name: 'newName',
			display: 'ϵͳ�ļ���',
			sortable: true,
			hide: true
		}, {
			name: 'inDocument',
			display: 'inDocument',
			sortable: true,
			hide: true
		}, {
			name: 'tFileSize',
			display: '�ļ���С',
			sortable: true,
			process: function(v) {
				if (v >= 1048576) {
					return moneyFormat2(v / 1048576) + "M";
				} else if (v >= 1024) {
					return moneyFormat2(v / 1024) + "K";
				} else {
					return moneyFormat2(v) + "B";
				}
			}
		}, {
			name: 'uploadPath',
			display: '����·��',
			sortable: true,
			hide: true
		}, {
			name: 'isTemp',
			display: '�Ƿ���ʱ�ļ�',
			sortable: true,
			hide: true
		}, {
			name: 'styleThree',
			display: 'styleThree',
			sortable: true,
			hide: true
		}, {
			name: 'styleTwo',
			display: 'styleTwo',
			sortable: true,
			hide: true
		}, {
			name: 'styleOne',
			display: 'styleOne',
			sortable: true,
			hide: true
		}, {
			name: 'createId',
			display: '������Id',
			sortable: true,
			hide: true
		}, {
			name: 'createName',
			display: '����������',
			sortable: true,
			width: '100'
		}, {
			name: 'createTime',
			display: '����ʱ��',
			sortable: true,
			width: 150
		}, {
			name: 'updateId',
			display: '�޸���Id',
			sortable: true,
			hide: true
		}, {
			name: 'updateName',
			display: '�޸�������',
			sortable: true,
			hide: true
		}, {
			name: 'updateTime',
			display: '�޸�ʱ��',
			sortable: true,
			hide: true
		}],

		buttonsEx: [{
			name: 'exportIn',
			text: "�����ĵ�",
			icon: 'add',
			action: function(row, rows, grid) {
				if ($("#typeId").val()) {
					showThickboxWin("?model=engineering_file_esmfile&action=toUploadFile"
					+ "&projectId=" + $("#projectId").val()
					+ "&typeId=" + $("#typeId").val()
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ���ĵ�����");
				}
			}
		}],

		menusEx: [{
			text: '�����ĵ�',
			icon: 'edit',
			action: function(row) {
				window.open(
					"?model=engineering_file_esmfile&action=toDownFileById&fileId=" + row.id,
					"", "width=200,height=200,top=200,left=200");

			}
		}, {
			text: 'ɾ���ĵ�',
			icon: 'delete',
			action: function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type: "POST",
						url: "?model=engineering_file_esmfile&action=ajaxdelete",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page();
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],

		searchitems: [{
			display: "ҵ������",
			name: 'serviceTypeSch'
		}, {
			display: "�ļ���������",
			name: 'typeNameSch'
		}, {
			display: "ԭʼ�ļ���",
			name: 'originalNameSch'
		}]
	});
});