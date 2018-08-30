var show_page = function(page) {
	$("#hardwareGrid").yxgrid("reload");
};
$(function() {
	$("#hardwareGrid").yxgrid({
		model : 'projectmanagent_hardware_hardware',
		title : '商机设备硬件管理',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'hardwareName',
			display : '设备名称',
			sortable : true,
			width : 280
		}, {
			name : 'isUse',
			display : '使用状态',
			sortable : true,
			process : function(v,row){
			   if( v == '0'){
			      return "启用";
			   }else if(v == '1'){
			      return "停用"
			   }
			}
		}],
		menusEx : [{
			text : '启用',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isUse == "1") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认启用?")) {
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
								alert('启用成功！');
							} else {
								alert('启用失败!');
							}
						}
					});
				}
			}
		}, {
			text : '停用',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isUse == "0") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认停用?")) {
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
								alert('操作成功！');
							} else {
								alert('操作失败!');
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
			display : "设备名称",
			name : 'hardwareName'
		}]
	});
});