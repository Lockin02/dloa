var show_page = function(page) {
	$("#taskGrid").yxgrid("reload");
};
$(function(){
	$("#taskGrid").yxgrid({
		model : 'engineering_resources_task',
		title : '项目设备任务单',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '单据状态',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_grey.png" title="未完成"/>';break;
					case '1' : return '<img src="images/icon/cicle_yellow.png" title="处理中"/>';break;
					case '2' : return '<img src="images/icon/cicle_green.png" title="已完成"/>';break;
					case '3' : return '<img src="images/icon/cicle_yellow.png" title="待确认"/>';break;
				}
			},
			width : 50
		}, {
			name : 'areaName',
			display : '发货区域',
			sortable : true,
			width : 50
		}, {
			name : 'taskCode',
			display : '任务单号',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_task&action=toView&id="
						+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '申请人',
			sortable : true,
			width : 80
		}, {
			name : 'receiverName',
			display : '接收人',
			sortable : true,
			width : 80
		}, {
			name : 'place',
			display : '设备使用地',
			sortable : true,
			width : 120
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 120
		}, {
			name : 'managerName',
			display : '项目经理',
			sortable : true
		}, {
			name : 'createId',
			display : '创建人Id',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人Id',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人名称',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改时间',
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
		// 扩展右键菜单
		menusEx : [{
			text : '设备出库',
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
			text : '修改',
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
			text : '撤销任务',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row && window.confirm("确定要撤销任务?")) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_task&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('撤销成功！');
								show_page(1);
							} else {
								alert("撤销失败! ");
							}
						}
					});
				}
			}
		}],
		buttonsEx : [{
			text : '打印',
			icon : 'print',
			action : function(row,rows,idArr) {
				if(row){
					idStr = idArr.toString();
					showModalWin("?model=engineering_resources_task&action=toBatchPrintAlone&id=" + idStr ,1);
				}else{
					alert('请至少选择一张单据打印');
				}
			}
		},{
			text : '撤销任务',
			icon : 'delete',
			action : function(row,rows,idArr) {
				if(row){
					for(var i=0; i< rows.length ; i++){
						if(rows[i].status != '0'){
							alert("任务单号为【" + rows[i].taskCode + "】的发货任务在处理或者已经处理完成，不能撤销！");
							return false;
						}
					}
					if (window.confirm("确定要撤销任务?")) {
						idStr = idArr.toString();
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_task&action=ajaxdeletes",
							data : {
								id : idStr
							},
							success : function(msg) {
								if (msg == 1) {
									alert('撤销成功！');
									show_page(1);
								} else {
									alert("撤销失败! ");
								}
							}
						});
					}
				}else{
					alert('请至少选择一条记录');
				}
			}
		}],
		comboEx : [{
			text : '单据状态',
			key : 'statusArr',
			value : '0,1,3',
			data : [{
				text : '未处理',
				value : '0'
			}, {
				text : '待确认',
				value : '3'
			}, {
				text : '处理中',
				value : '1'
			}, {
				text : '已完成',
				value : '2'
			}, {
				text : '未完成',
				value : '0,1,3'
			}]
		}],
		searchitems : [{
			display : "任务单号",
			name : 'taskCodeSch'
		},{
            display : "申请人",
            name : 'applyUserSch'
        },{
            display : "接收人",
            name : 'receiverNameSch'
        },{
			display : "项目编号",
			name : 'projectCodeSch'
		},{
			display : "项目名称",
			name : 'projectNameSch'
		}]
	});
});