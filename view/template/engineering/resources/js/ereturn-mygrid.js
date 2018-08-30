var show_page = function() {
	$("#myereturnlistGrid").yxgrid("reload");
};

$(function() {
	$("#myereturnlistGrid").yxgrid({
		model : 'engineering_resources_ereturn',
		action : 'mylistJson',
		title : '我的设备归还',
		isDelAction : false,
		isOpButton : false,
		isAddAction : false,
		showcheckbox : false,
		//列信息
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
                    case '0' : return '<img src="images/icon/cicle_grey.png" title="未提交"/>';break;
                    case '1' : return '<img src="images/icon/cicle_yellow.png" title="待确认"/>';break;
                    case '4' : return '<img src="images/icon/cicle_blue.png" title="部分确认"/>';break;
                    case '2' : return '<img src="images/icon/cicle_green.png" title="已确认"/>';break;
                    case '3' : return '<img src="images/icon/cicle_red.png" title="已打回"/>';break;
				}
			}
		}, {
			display : '申请单编号',
			name : 'formNo',
			width : 140,
			sortable : true,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_ereturn&action=toView&id="
						+ row.id + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '申请人',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 90
		}, {
			name : 'areaName',
			display : '归还区域',
			sortable : true,
			width : 80
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 150
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
			width : 130
		}],
		toEditConfig : {
			showMenuFn : function(row) {
				return row.status == "0" || row.status == "3";
			},
			toEditFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_ereturn&action=toEdit&id="
					+ row.id,1,700,1100,row.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_ereturn&action=toView&id="
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
					    url: "?model=engineering_resources_ereturn&action=confirmStatus",
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
                        url: "?model=engineering_resources_ereturn&action=confirmStatus",
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
						url : "?model=engineering_resources_ereturn&action=ajaxdeletes",
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
		}],
		searchitems : [{
			display : "申请单编号",
			name : 'formNoSch'
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
			data : [{
                text : '待提交',
                value : '0'
            }, {
                text : '待确认',
                value : '1'
            },{
                text : '部分确认',
                value : '4'
            },{
                text : '已确认',
                value : '2'
            },{
                text : '已打回',
                value : '3'
            }]
        }]
	});
});