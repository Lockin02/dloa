var show_page = function(page) {
	$("#hardwareGrid").yxgrid("reload");
};
$(function() {
	$("#hardwareGrid").yxgrid({
		model : 'projectmanagent_hardware_hardware',
		title : '�̻��豸Ӳ������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'hardwareName',
			display : '�豸����',
			sortable : true,
			width : 280
		}, {
			name : 'isUse',
			display : 'ʹ��״̬',
			sortable : true,
			process : function(v,row){
			   if( v == '0'){
			      return "����";
			   }else if(v == '1'){
			      return "ͣ��"
			   }
			}
		}],
		menusEx : [{
			text : '����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isUse == "1") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ������?")) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_hardware_hardware&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '0'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('���óɹ���');
							} else {
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}, {
			text : 'ͣ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isUse == "0") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��ͣ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_hardware_hardware&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '1'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('�����ɹ���');
							} else {
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�豸����",
			name : 'hardwareName'
		}]
	});
});