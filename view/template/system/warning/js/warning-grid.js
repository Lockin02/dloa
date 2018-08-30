var show_page = function() {
	$("#warningGrid").yxgrid("reload");
};
$(function() {
	$("#warningGrid").yxgrid({
		model : 'system_warning_warning',
		title : '通用预警功能',
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'objName',
			display : '业务名称',
			sortable : true,
			width : 120
		}, {
			name : 'description',
			display : '描述信息',
			sortable : true,
			width : 120,
            hide : true
		}, {
			name : 'executeSql',
			display : '查询脚本',
			sortable : true,
			width : 500
		}, {
			name : 'isUsing',
			display : '是否启用',
			sortable : true,
			width : 70,
			process : function(v) {
				if (v == "1") {
					return '是';
				} else {
					return '否';
				}
			}
		}, {
            name : 'executeClass',
            display : '执行类',
            sortable : true,
            width : 120
        }, {
            name : 'executeFun',
            display : '执行方法',
            sortable : true,
            width : 120
        }, {
			name : 'mailCode',
			display : '执行邮件通知编码',
			sortable : true,
			width : 120
		}, {
			name : 'inKeys',
			display : '传入邮件字段',
			sortable : true,
			width : 120
		}, {
			name : 'receiverIdKey',
			display : '邮件接收人id字段',
			sortable : true,
			width : 120
		}, {
			name : 'receiverNameKey',
			display : '邮件接收人姓名字段',
			sortable : true,
			width : 120
		}, {
			name : 'isMailManager',
			display : '是否通知上级',
			sortable : true,
			width : 70,
			process : function(v) {
				if (v == "1") {
					return '是';
				} else {
					return '否';
				}
			}
		} , {
			name : 'lastTime',
			display : '最后执行时间',
			sortable : true,
			width : 120
		} , {
			name : 'intervalDay',
			display : '间隔时间(天)',
			sortable : true,
			width : 70
		} , {
			name : 'regularPlan',
			display : '定期计划',
			sortable : true,
			width : 70
		} ],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		menusEx : [{
			text : '测试脚本',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=system_warning_warning&action=testSql&id="
					+ row.id + "&skey=" + row.skey_ + "&height="+400 + "&width=" + 800
                    + "&placeValuesBefore&TB_iframe=true&modal=false");
			}
		},{
			text : '手动执行',
			icon : 'edit',
			action : function(row) {
				if (row) {
					if (window.confirm(("确定要手动执行【" + row.objName + "】?"))) {
						$.ajax({
						    type: "POST",
						    url: "?model=system_warning_warning&action=dealWarningByMan",
						    data: {
						    	"id" : row.id
						    },
						    async: false,
						    success: function(data){
						   		if(data == 1){
									alert('执行成功!');
									show_page();
						   	    }else{
						   	    	alert('执行失败!');
						   	    }
							}
						});
					}
				}
			}
		}],
		searchitems : [{
			display : "测试业务",
			name : 'objName'
		}]
	});
});