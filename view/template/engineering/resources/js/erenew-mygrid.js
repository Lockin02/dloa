var show_page = function() {
	$("#erenewGrid").yxgrid("reload");
};
$(function() {
	$("#erenewGrid").yxgrid({
		model : 'engineering_resources_erenew',
		title : '我的设备续借',
		action : 'mylistJson',
		isOpButton : false,
		showcheckbox : false,
		isDelAction :false,
		isAddAction : false,
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},  {
			name : 'status',
			display : '状态',
			sortable : true,
			width : 30,
			align : 'center',
			process : function(v) {
				switch(v){
	                case '0' : return '<img src="images/icon/cicle_grey.png" title="待提交"/>';break;
	                case '1' : return '<img src="images/icon/cicle_blue.png" title="待管理员确认"/>';break;
	                case '2' : return '<img src="images/icon/cicle_yellow.png" title="待确认续借"/>';break;
	                case '3' : return '<img src="images/icon/cicle_red.png" title="已打回"/>';break;
	                case '4' : return '<img src="images/icon/cicle_green.png" title="已确认"/>';break;
				}
			}
		}, {
			name : 'formNo',
			display : '申请单编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_erenew&action=toView&id="
                    + row.id + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			},
			width : 140
		}, {
			name : 'applyUser',
			display : '申请人',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'applyUserId',
			display : '申请人id',
			sortable : true,
			hide : true
		}, {
			name : 'deptId',
			display : '所属部门id',
			sortable : true,
			hide : true
		}, {
			name : 'deptName',
			display : '所属部门名称',
			sortable : true,
			hide : true
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 120
		}, {
			name : 'projectId',
			display : '项目id',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 130
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width: 130
		}, {
			name : 'managerName',
			display : '项目经理',
			sortable : true,
			hide : true,
			width : 100
		}, {
			name : 'managerId',
			display : '项目经理id',
			sortable : true,
			hide : true
		}, {
			name : 'reason',
			display : '事由',
			sortable : true,
			width : 200
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
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
		}, {
			name : 'confirmName',
			display : '确认人',
			sortable : true,
			hide : true
		}, {
			name : 'confirmId',
			display : '确认人id',
			sortable : true,
			hide : true
		}, {
			name : 'confirmTime',
			display : '确认时间',
			sortable : true,
			hide : true
		}],
		toEditConfig : {
			showMenuFn : function(row) {
                return row.status == "0" || row.status == "3";
			},
			toEditFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_erenew&action=toEdit&id="
					+ row.id,1,700,1100,row.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_erenew&action=toView&id="
                    + row[p.keyField],1,700,1100,row.id);
			}
		},
		menusEx : [{
			text : '提交确认',
			icon : 'add',
			showMenuFn : function(row) {
                return row.status == "0" || row.status == "3";
			},
			action : function(row, rows, grid) {
				if (confirm('确定将单据提交确认吗？')) {
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_resources_erenew&action=confirmStatus",
					    data: {
					    	'id' : row.id,
					    	'status' : '1'
					    },
					    async: false,
					    success: function(data){
					   		if(data == '1'){
								alert('提交成功');
								show_page();
							}else{
								alert('提交失败');
							}
						}
					});
				}
			}
		}, {
            text : '撤销单据',
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "1";
            },
            action : function(row, rows, grid) {
                if (confirm('确定撤销单据吗？')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_erenew&action=confirmStatus",
                        data: {
                            'id' : row.id,
                            'status' : 0
                        },
                        async: false,
                        success: function(data){
                            if(data == '1'){
                                alert('操作成功');
                                show_page();
                            }else{
                                alert('操作失败');
                            }
                        }
                    });
                }
            }
        }, {
            text : "删除",
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "0" || row.status == "3";
            },
            action : function(row) {
                if (window.confirm(("确定要删除?"))) {
                    $.ajax({
                        type : "POST",
                        url : "?model=engineering_resources_erenew&action=ajaxdeletes",
                        data : {
                            id : row.id
                        },
                        success : function(msg) {
                            if (msg == 1) {
                                alert('删除成功！');
                                show_page(1);
                            } else {
                                alert("删除失败! ");
                            }
                        }
                    });
                }
            }
		}, {
            text : '确认续借',
            icon : 'add',
            showMenuFn : function(row) {
                return row.status == "2";
            },
            action : function(row) {
                showOpenWin("?model=engineering_resources_erenew&action=toFinalConfirm&id="
                        + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
        }],
		searchitems : [{
			display : "申请单编号",
			name : 'formNoSch'
		},{
			display : "项目编号",
			name : 'projectCodeSch'
		},{
			display : "项目名称",
			name : 'projectNameSch'
		}],
		//过滤数据
		comboEx:[{
            text : '单据状态',
            key : 'status',
            data : [{
                text : '待提交',
                value : '0'
            }, {
                text : '待管理员确认',
                value : '1'
            }, {
                text : '待确认续借',
                value : '2'
            }, {
                text : '已打回',
                value : '3'
            }, {
                text : '已确认',
                value : '4'
            }]
        }]
	});
});