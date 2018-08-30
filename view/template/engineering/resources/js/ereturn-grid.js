var show_page = function() {
	$("#ereturnlistGrid").yxgrid("reload");
};

$(function() {
	$("#ereturnlistGrid").yxgrid({
		model : 'engineering_resources_ereturn',
		action : 'pageJson',
		title : '设备归还单',
		isDelAction : false,
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		param : {
			statusArr : '1,2,4'
		},
		//列信息
		colModel : [{
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
                    case '1' : return '<img src="images/icon/cicle_yellow.png" title="待确认"/>';break;
                    case '4' : return '<img src="images/icon/cicle_blue.png" title="部分确认"/>';break;
                    case '2' : return '<img src="images/icon/cicle_green.png" title="已确认"/>';break;
				}
			}
		}, {
			display : '申请单编号',
			name : 'formNo',
			width : 110,
			sortable : true,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_ereturn&action=toView&id="
                    + row.id + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '申请人',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 80
		}, {
            name : 'areaName',
            display : '归还区域',
            sortable : true,
            width : 50
        }, {
			name : 'deviceDeptName',
			display : '归还部门',
			sortable : true,
			width : 70
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 150
		}, {
			name : 'managerName',
			display : '项目经理',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'expressName',
			display : '快递公司',
			sortable : true,
			width : 120
		},{
			name : 'expressNo',
			display : '快递单号',
			sortable : true,
			width : 120
		},{
			name : 'mailDate',
			display : '邮寄日期',
			sortable : true,
			width : 90
		},{
			name : 'remark',
			display : '备注信息',
			sortable : true,
			width : 150
		}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_ereturn&action=toView&id="
                    + row[p.keyField],1,700,1100,row.id);
			}
		},
		menusEx : [{
			text : '确认',
			icon : 'add',
			showMenuFn : function(row) {
                return row.status == "1" || row.status == "4";
			},
			action : function(row, rows, grid) {
                showOpenWin("?model=engineering_resources_ereturn&action=toConfirm&id="
                        + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
		},{
            text : '打回',
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "1";
            },
            action : function(row, rows, grid) {
                if (confirm('确认打回单据吗？')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_ereturn&action=confirmStatus",
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
			display : "归还区域",
			name : 'areaNameSch'
		},{
			display : "项目编号",
			name : 'projectCodeSch'
		},{
			display : "项目名称",
			name : 'projectNameSch'
		}],
		//过滤数据
		comboEx:[{
			text : '状态',
			key : 'status',
            value : '1,4',
			data : [{
                text : '待确认',
                value : '1'
            },{
                text : '部分确认',
                value : '4'
            },{
                text : '已确认',
                value : '2'
            },{
                text : '-未完成-',
                value : '1,4'
            }]
        }]
	});
});