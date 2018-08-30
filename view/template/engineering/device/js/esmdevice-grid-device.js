var show_page = function() {
	$("#esmdeviceGrid").yxgrid("reload");
};

$(function() {
	var projectCode = $("#projectCode").val();
	//Ĭ�ϲ���ʾ
	var show = false;
	//��ȡȨ��
	$.ajax({
		type : 'POST',
		url : '?model=engineering_project_esmproject&action=getLimits',
		data : {
			'limitName' : '��Ŀ�豸��¼�޸�'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				//ӵ�ж�ӦȨ�ޣ�����ʾ
				show = true;
			}
		}
	});
	$("#esmdeviceGrid").yxgrid({
		model : 'engineering_device_esmdevice',
		action : 'deviceJson',
		title : '��Ŀ�豸',
		showcheckbox : false,
		param : {'dprojectcode' : projectCode},
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'deviceType',
				display : '�豸����',
				sortable : true
			}, {
				name : 'device_name',
				display : '�豸����',
				sortable : true,
				width : 150
			}, {
				name : 'coding',
				display : '������',
				sortable : true,
				width : 80
			}, {
				name : 'dpcoding',
				display : '���ű���',
				sortable : true,
				width : 80
			}, {
				name : 'borrowNum',
				display : '����',
				sortable : true,
				width : 50
			}, {
				name : 'unit',
				display : '��λ',
				sortable : true,
				width : 50
			}, {
				name : 'borrowUserName',
				display : '������',
				sortable : true,
				width : 80
			}, {
				name : 'borrowDate',
				display : '���ʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'returnDate',
				display : '�黹ʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'useDays',
				display : 'ʹ������',
				sortable : true,
				width : 60
			}, {
				name : 'amount',
				display : 'ʵʱ����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'notse',
				display : '��ע',
				sortable : true,
				hide : true
			}, {
				name : 'description',
				display : '������Ϣ',
				sortable : true,
				width : 200
			}],
		buttonsEx : [{
			name : 'export',
			text : "����EXCEL",
			icon : 'excel',
			action : function(row) {
				showOpenWin("?model=engineering_device_esmdevice&action=exportDevice&dprojectcode=" +
						projectCode+
						"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=550");
			}
		}],
		menusEx : [{
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.id){
					return show;
				}else{
					return false;
				}		
			},
			action : function(row) {
				showThickboxWin('?model=engineering_device_esmdevice&action=toEditLog&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.id){
					return show;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('ȷ��Ҫɾ���ü�¼��')) {
					$.ajax({
						type : 'POST',
						url : '?model=engineering_device_esmdevice&action=ajaxdelete',
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 1) {
								alert('ɾ���ɹ�');
								show_page();
							} else {
								alert("ɾ��ʧ��");
							}
							return false;
						}
					});
				}
			}
		}],
		searchitems : [{
			display : '�豸����',
			name : 'device_nameSearch'
		}, {
			display : '������Ϣ',
			name : 'descriptionSearch'
		}]
	});
});