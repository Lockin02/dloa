var show_page = function(page) {
	$("#orderGrid").yxgrid("reload");
};
$(function() {
	$("#orderGrid").yxgrid({				      
		model : 'outsourcing_workorder_order',
       	title : '外包工单',
       	isAddAction:false,
       	isEditAction:false,
       	isViewAction:false,
       	isDelAction:false,
		//列信息
		colModel : [{
						　display : 'id',
							　name : 'id',
						　sortable : true,
						　hide : true
		        　	　　},{
        					name : 'approvalCode',
      					display : '外包立项编号',
      					sortable : true
                  },{
        					name : 'suppName',
      					display : '外包供应商名称',
      					sortable : true
                  },{
        					name : 'suppCode',
      					display : '外包供应商编号',
      					sortable : true
                  },{
        					name : 'projectName',
      					display : '项目名称',
      					sortable : true
                  },{
        					name : 'projectCode',
      					display : '项目编号',
      					sortable : true
                  },{
        					name : 'province',
      					display : '项目省份',
      					sortable : true
                  },{
        					name : 'suppType',
      					display : '外包类型',
      					sortable : true,
						　	hide : true
                  },{
        					name : 'suppTypeName',
      					display : '外包类型名称',
      					sortable : true
                  },{
        					name : 'natureCode',
      					display : '项目性质',
      					sortable : true,
						　	hide : true
                  },{
        					name : 'natureName',
      					display : '项目性质名称',
      					sortable : true
                  },{
        					name : 'projectManager',
      					display : '项目经理',
      					sortable : true
                  },{
        					name : 'projectManagerId',
      					display : '项目经理ID',
      					sortable : true,
						　	hide : true
                  },{
        					name : 'createId',
      					display : '创建人ID',
      					sortable : true,
						　	hide : true
                  },{
        					name : 'createName',
      					display : '填表人',
      					sortable : true
                  },{
        					name : 'createTime',
      					display : '填表时间',
      					sortable : true
                  },{
        					name : 'ExaStatus',
      					display : '审核状态',
      					sortable : true
                  },{
        					name : 'ExaDT',
      					display : '审核时间',
      					sortable : true
                  }],
	//表头按钮
	buttonsEx : [{
			name : 'view',
			text : "新增",
			icon : 'add',
			action : function() {
				 showModalWin("?model=outsourcing_workorder_order&action=toAdd");
				}
			},{
			name : 'exportOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				window.open("?model=outsourcing_workorder_order&action=excelOutAll"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=40&width=60")
				}
			},{
			name : 'searchExport',
			text : "查询导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_workorder_order&action=toSearchExport"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
    menusEx : [{
    		text : '编辑',
    		icon : 'edit',
    		action : function(row){
    			showModalWin("?model=outsourcing_workorder_order&action=toEdit&id="+row.id);
    		}
    		
			},{
    		text : '查看',
    		icon : 'view',
    		action : function(row){
    			showModalWin("?model=outsourcing_workorder_order&action=toView&id="+row.id);
    				}
    		},{
	    		text : '删除',
					icon : 'delete',
					action : function(row, rows, grid) {
						if (window.confirm(("确定要删除?"))) {
							$.ajax({
								type : "POST",
								url : "?model=outsourcing_workorder_order&action=ajaxdeletes",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('删除成功！');
										$("#orderGrid").yxgrid("reload");
									}
								}
							});
						}
				}
    		}],
		// 主从表格设置
//		subGridOptions : {
//			url : '?model=outsourcing_workorder_NULL&action=pageItemJson',
//			param : [{
//						paramId : 'mainId',
//						colId : 'id'
//					}],
//			colModel : [{
//						name : 'XXX',
//						display : '从表字段'
//					}]
//		},
        toAddConfig : {
			action : 'toAdd',
			formWidth : 1000,
			formHeight : 400
		},
		toEditConfig : {
			action : 'toEdit',
			formWidth : 800,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},
		searchitems : [{
					display : "外包立项编号",
					name : 'approvalCode'
					},{
						display : "外包供应商名称",
						name : 'suppName'
					},{
						display : "外包供应商编号",
						name : 'suppCode'
					},{
						display : "项目名称",
						name : 'projectName'
					},{
						display : "项目编号",
						name : 'projectCode'
					},{
						display : "外包类型",
						name : 'suppTypeName'
					},{
						display : "项目性质",
						name : 'natureName'
					},{
						display : "项目经理",
						name : 'projectManager'
					}]
 		});
 });