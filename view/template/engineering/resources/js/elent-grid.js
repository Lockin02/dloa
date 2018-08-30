var show_page = function() {
	$("#myelentGrid").yxgrid("reload");
};
$(function() {
	$("#myelentGrid").yxgrid({
		model : 'engineering_resources_elent',
		title : '设备转借管理',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			statusArr : '1,2,4,5'
		},
		// 列信息
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'status',
            display : '状态',
            sortable : true,
            width : 30,
            align : 'center',
            process : function(v) {
                switch(v){
	                case '1' : return '<img src="images/icon/cicle_yellow.png" title="待确认"/>';break;
	                case '5' : return '<img src="images/icon/cicle_yellow.png" title="部分确认"/>';break;
	                case '2' : return '<img src="images/icon/cicle_blue.png" title="待接收人确认"/>';break;
	                case '4' : return '<img src="images/icon/cicle_green.png" title="已确认"/>';break;
                }
            }
        }, {
            name : 'formNo',
            display : '申请单编号',
            sortable : true,
            width : 110,
            process : function(v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_elent&action=toView&id="
                    + row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
            }
        }, {
            name : 'applyUser',
            display : '申请人',
            width : 80,
            sortable : true
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
            width : 70
        }, {
            name : 'deviceDeptName',
            display : '设备归属部门',
            sortable : true,
            width : 70
        }, {
            name : 'projectId',
            display : '借出项目id',
            sortable : true,
            hide : true
        }, {
            name : 'projectCode',
            display : '借出项目编号',
            sortable : true,
            width : 120
        }, {
            name : 'projectName',
            display : '借出项目名称',
            sortable : true,
            width : 120
        }, {
            name : 'managerName',
            display : '借出项目经理',
            sortable : true,
            hide : true
        }, {
            name : 'managerId',
            display : '借出项目经理id',
            sortable : true,
            hide : true
        }, {
            name : 'receiverId',
            display : '接收人id',
            sortable : true,
            hide : true
        }, {
            name : 'receiverName',
            display : '接收人',
            sortable : true,
            width : 80
        }, {
            name : 'receiverDept',
            display : '接收人部门',
            sortable : true,
            hide : true
        }, {
            name : 'receiverDeptId',
            display : '接收人部门id',
            sortable : true,
            hide : true
        }, {
            name : 'rcProjectId',
            display : '接收项目id',
            sortable : true,
            hide : true
        }, {
            name : 'rcProjectCode',
            display : '接收项目编号',
            sortable : true,
            width : 120
        }, {
            name : 'rcProjectName',
            display : '接收项目名称',
            sortable : true,
            width : 120
        }, {
            name : 'rcManagerName',
            display : '接收项目经理',
            sortable : true,
            hide : true
        }, {
            name : 'rcManagerId',
            display : '接收项目经理id',
            sortable : true,
            hide : true
        }, {
            name : 'reason',
            display : '转借原因',
            sortable : true
        }, {
            name : 'remark',
            display : '备注',
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
            hide : true
        }, {
            name : 'confirmName',
            display : '确认人',
            sortable : true
        }, {
            name : 'confirmId',
            display : '确认人id',
            sortable : true,
            hide : true
        }, {
            name : 'confirmTime',
            display : '确认时间',
            sortable : true,
            width : 120
        } ],
        toViewConfig : {
            toViewFn : function(p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_elent&action=toView&id="
                    + row[p.keyField],1,700,1100,row.id);
            }
        },
        menusEx : [{
			text : '确认',
			icon : 'add',
			showMenuFn : function(row) {
                return row.status == "1" || row.status == "5";
			},
			action : function(row, rows, grid) {
                showOpenWin("?model=engineering_resources_elent&action=toConfirm&id="
                        + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
		}, {
            text : '打回',
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "1";
            },
            action : function(row, rows, grid) {
                if (confirm('确认打回单据吗？')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_elent&action=confirmStatus",
                        data: {
                            'id' : row.id ,
                            'status' : '3'
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
        }],
        searchitems : [{
            display : "申请单编号",
            name : 'formNoSch'
        },{
            display : "申请人",
            name : 'applyUserSch'
        },{
            display : "借出项目编号",
            name : 'projectCodeSch'
        },{
            display : "借出项目名称",
            name : 'projectNameSch'
        },{
            display : "接收人",
            name : 'receiverNameSch'
        },{
            display : "接收项目编号",
            name : 'rcProjectCodeSch'
        },{
            display : "接收项目名称",
            name : 'rcProjectNameSch'
        }],
		//过滤数据
		comboEx:[{
			text : '状态',
			key : 'status',
            value : '1,2,5',
			data : [{
                text : '待确认',
                value : '1'
            },{
                text : '部分确认',
                value : '5'
            },{
                text : '待接收人确认',
                value : '2'
            },{
                text : '已确认',
                value : '4'
            },{
                text : '-未完成-',
                value : '1,2,5'
            }]
        }]
	});
});