// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#checkGrid").yxgrid('reload');
};

$(function() {
	$("#checkGrid").yxgrid({
		model : 'asset_checktask_check',
	    title : '盘点任务导入信息',
	    showcheckbox : false,
		// 列信息
		 colModel : [
	            {
	                display : 'id',
	                name : 'id',
	                sortable : true,
	                hide : true
	            }, {
	                display : '任务单id',
	                name : 'taskId',
	                sortable : true,
	                hide : true
	            },
	            {
	                display : '任务编号',
	                name : 'taskNo',
	                sortable : true
	            },
	            {
	                display : '开始时间',
	                name : 'beginDate',
	                sortable : true
	            },
	            {
	                display : '结束时间',
	                name : 'endDate',
	                sortable : true
	            },
	            {
	                display : '盘点部门id',
	                name : 'deptId',
	                sortable : true,
	                hide:true
	            },
	            {
	                display : '盘点部门',
	                name : 'dept',
	                sortable : true
	            },{
				   display:'盘点人id',
				   name : 'manId',
	               sortable : true,
	                hide:true
	
				},{
				   display:'盘点人',
				   name : 'man',
	               sortable : true
	
				},
	            {
	                display : '备注',
	                name : 'remark',
	                sortable : true
	            }],
	
				isViewAction : true,
	            isEditAction : false,
	            isDelAction:  false,
	            toAddConfig : {
				  formWidth : 800,
				  formHeight : 350
			},
			    toEditConfig : {
					formWidth : 800,
					formHeight : 350
			},
			    toViewConfig : {
					formWidth : 800,
					formHeight : 300
			},
			menusEx : [
			  {
				text : '删除',
				icon : 'delete',
				action : function(row) {
					if (window.confirm(("确定删除吗？"))) {
	
						$.ajax({
							type : "GET",
							url : "?model=asset_checktask_check&action=deletes&id="
									+ row.id,
							success : function(msg) {
								$("#checkGrid").yxgrid("reload");
							}
						});
					}
				}
			}],
			// 快速搜索
			searchitems : [{
				display : '任务编号',
				name : 'taskNo'
			}, {
				display : '开始时间',
				name : 'beginDate'
			}, {
				display : '盘点部门',
				name : 'dept'
			}],
			sortname : "id",
			sortorder : "ASC"
			});
		});