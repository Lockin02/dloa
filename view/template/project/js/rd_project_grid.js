function createGrid(gridName, gridUrl, subGridUrl){
	$('#' + gridName).datagrid({
		view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
		},onExpandRow: function(index,row){
			 $('#ddv-'+index).datagrid({
					 url:subGridUrl + '&product_str='+row.product_id_str,
					 fitColumns:true,
					 singleSelect:true,
					 rownumbers:true,
					 loadMsg:'读取中...',
					 height:'auto',
					 columns:[[
						 {field:'typeid',title:'产品类型',width:100},
						 {field:'product_name',title:'中文名称',width:100,align:'right'},
						 {field:'en_product_name',title:'英文名称',width:100,align:'right'},
						 {field:'state',title:'产品状态',width:100,align:'right'},
						 {field:'status',title:'审批状态',width:100,align:'right'},
						 {field:'manager',title:'产品经理',width:100,align:'right'},
						 {field:'assistant',title:'产品助理',width:100,align:'right'}
					 ]],
					 onResize:function(){
						 $('#' + gridName).datagrid('fixDetailRowHeight',index);
					 },
					 onLoadSuccess:function(){
						 setTimeout(function(){
							 $('#' + gridName).datagrid('fixDetailRowHeight',index);
						 },0);
					 }
				 });
				 $('#' + gridName).datagrid('fixDetailRowHeight',index);
			
		},
		
        title: '双击列表中的记录可以查看项目详细信息',
        iconCls: 'icon-edit',
        pagination: true,
		pageSize:20,
        singleSelect: true,
        idField: 'id',
        url: gridUrl,
        columns: [[ {
            field: 'name',
            title: '项目名称',
            width: 200,
            align: 'left',
            editor: 'text'
        },{
            field: 'number',
            title: '项目编码',
            width: 60,
            align: 'left',
            editor: 'text'
        }, {
            field: 'stage',
            title: '项目阶段',
            width: 60,
            align: 'left',
            editor: 'text',
            formatter: function(value, row, index){
				return stageStatus(value);
            }
        },{
            field: 'ipo_name',
            title: '募投项目',
            width: 110,
            align: 'left',
            editor: 'text'
        },{
            field: 'zf_name',
            title: '政府项目',
            width: 100,
            align: 'left',
            editor: 'text'
        },{
            field: 'project_type_name',
            title: '项目类型',
            width: 60,
            align: 'left',
            editor: 'text'
        },{
            field: 'begin_date',
            title: '立项时间',
            width: 80,
            align: 'left',
            editor: 'text'
        },{
            field: 'manager_name',
            title: '项目经理',
            width: 60,
            align: 'left',
            editor: 'text'
        },{
            field: 'dept_name',
            title: '所属部门',
            width: 100,
            align: 'left',
            editor: 'text'
        },{
            field: 'status',
            title: '项目状态',
            width: 50,
            align: 'left',
            editor: 'text',
            formatter: function(value, row, index){
            	return getStatus(value);
            }
        },{
            field: 'action',
            title: '操作',
            width: 100,
            align: 'center',
            formatter: function(value, row, index){
            	var str = '';
            	str += '<a href="#" onclick="show_info(' + index + ')">查看</a> ';
            	if(admin =='true'){
            		str += '<a href="#" onclick="edit(' + index + ')">修改</a> ';
            		str += '<a href="#" onclick="del(' + index + ')">删除</a>';
            	}
            	return str;
            }
        }]],
        /*
		onBeforeEdit:function(index,row){
			row.editing = true;
			updateActions();
		},
		onAfterEdit:function(index,row){
			row.editing = false;
			updateActions();
		},
		onCancelEdit:function(index,row){
			row.editing = false;
			updateActions();
		},
		*/
		onDblClickRow:function(index,row){
			show_info(index);
		}
    });
}

function showButton(gridName, isAdmin, canImport, canExport){
	button = new Array();
	if(isAdmin == 'true'){
		button.push({iconCls: 'icon-add',text: '添加项目',handler: function(){insert();}});
	}
	
	if(isAdmin == 'true' || canImport == 'true'){
		button.push({iconCls: 'icon-tip',text: '导入',handler: function(){import_file();}});
	}
	
	if(isAdmin == 'true' || canExport == 'true'){
		button.push({iconCls: 'icon-tip',text: '导出',handler: function(){export_file();}});
	}
	return button;
}


