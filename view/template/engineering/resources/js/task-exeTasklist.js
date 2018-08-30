var show_page = function(page) {
	$("#exetaskGrid").yxgrid("reload");
};
$(function() {
	$("#exetaskGrid").yxgrid({
		model : 'engineering_resources_task',
		action : 'exetaskJson',
//		param : {"areaPrincipalId" : $("#userId").val()},
		title : '详细任务单',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=engineering_resources_task&action=toTaskView&id='
						+ row.taskId
						+ "&taskType="
						+ row.taskType
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : "接收任务",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isReAll == 'WJS') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确认接收吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_task&action=ajaxHandle",
						data : {
							id : row.taskId,
							taskType : row.taskType
						},
						success : function(msg) {
							if (msg == 1) {
								alert('确认成功！');
								show_page(1);
							} else {
								alert("确认失败! ");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text : '任务类型',
			key : 'taskType_My',
			data : [{
				text : '在库类',
				value : 'ZK'
			}, {
				text : '待申购/生产类',
				value : 'DSG'
			}, {
				text : '无法调配类',
				value : 'WFDP'
			}]
		},{
			text : '是否接收',
			key : 'isRe_t',
			value : '0',
			data : [{
				text : '已接收',
				value : '1'
			}, {
				text : '未接受',
				value : '0'
			}]
		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'taskId',
			name : 'taskId',
			sortable : true,
			hide : true
		}, {
			name : 'taskCode',
			display : '任务单号',
			sortable : true,
			width : 180
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 200
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 200
		}, {
			name : 'managerName',
			display : '项目经理',
			sortable : true
		}, {
			name : 'taskType',
			display : '任务类型',
			sortable : true,
			process : function(v){
				switch(v){
					case 'ZK' : return '在库类';
					case 'DSG' : return '待申购/生产类';
					case 'WFDP' : return '无法调配类';
					default : return v;
				}
			}
		}, {
			name : 'isReAll',
			display : '是否接收',
			sortable : true,
			process : function(v){
				switch(v){
					case 'WJS' : return '未接受';
					case 'YJS' : return '已接受';
					default : return v;
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
			name : 'resourceName'
		}]
	});
});