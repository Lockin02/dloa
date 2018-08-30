var show_page = function() {
	$("#esmdeviceGrid").yxgrid("reload");
};

$(function() {
	var projectCode = $("#projectCode").val();
	//默认不显示
	var show = false;
	//获取权限
	$.ajax({
		type : 'POST',
		url : '?model=engineering_project_esmproject&action=getLimits',
		data : {
			'limitName' : '项目设备记录修改'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				//拥有对应权限，则显示
				show = true;
			}
		}
	});
	$("#esmdeviceGrid").yxgrid({
		model : 'engineering_device_esmdevice',
		action : 'deviceJson',
		title : '项目设备',
		showcheckbox : false,
		param : {'dprojectcode' : projectCode},
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isOpButton : false,
		// 列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'deviceType',
				display : '设备类型',
				sortable : true
			}, {
				name : 'device_name',
				display : '设备名称',
				sortable : true,
				width : 150
			}, {
				name : 'coding',
				display : '机身码',
				sortable : true,
				width : 80
			}, {
				name : 'dpcoding',
				display : '部门编码',
				sortable : true,
				width : 80
			}, {
				name : 'borrowNum',
				display : '数量',
				sortable : true,
				width : 50
			}, {
				name : 'unit',
				display : '单位',
				sortable : true,
				width : 50
			}, {
				name : 'borrowUserName',
				display : '借用人',
				sortable : true,
				width : 80
			}, {
				name : 'borrowDate',
				display : '借出时间',
				sortable : true,
				width : 80
			}, {
				name : 'returnDate',
				display : '归还时间',
				sortable : true,
				width : 80
			}, {
				name : 'useDays',
				display : '使用天数',
				sortable : true,
				width : 60
			}, {
				name : 'amount',
				display : '实时决算',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'notse',
				display : '备注',
				sortable : true,
				hide : true
			}, {
				name : 'description',
				display : '描述信息',
				sortable : true,
				width : 200
			}],
		buttonsEx : [{
			name : 'export',
			text : "导出EXCEL",
			icon : 'excel',
			action : function(row) {
				showOpenWin("?model=engineering_device_esmdevice&action=exportDevice&dprojectcode=" +
						projectCode+
						"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=550");
			}
		}],
		menusEx : [{
			text : '修改',
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
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.id){
					return show;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('确定要删除该记录？')) {
					$.ajax({
						type : 'POST',
						url : '?model=engineering_device_esmdevice&action=ajaxdelete',
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 1) {
								alert('删除成功');
								show_page();
							} else {
								alert("删除失败");
							}
							return false;
						}
					});
				}
			}
		}],
		searchitems : [{
			display : '设备名称',
			name : 'device_nameSearch'
		}, {
			display : '描述信息',
			name : 'descriptionSearch'
		}]
	});
});