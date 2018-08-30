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
					 loadMsg:'��ȡ��...',
					 height:'auto',
					 columns:[[
						 {field:'typeid',title:'��Ʒ����',width:100},
						 {field:'product_name',title:'��������',width:100,align:'right'},
						 {field:'en_product_name',title:'Ӣ������',width:100,align:'right'},
						 {field:'state',title:'��Ʒ״̬',width:100,align:'right'},
						 {field:'status',title:'����״̬',width:100,align:'right'},
						 {field:'manager',title:'��Ʒ����',width:100,align:'right'},
						 {field:'assistant',title:'��Ʒ����',width:100,align:'right'}
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
		
        title: '˫���б��еļ�¼���Բ鿴��Ŀ��ϸ��Ϣ',
        iconCls: 'icon-edit',
        pagination: true,
		pageSize:20,
        singleSelect: true,
        idField: 'id',
        url: gridUrl,
        columns: [[ {
            field: 'name',
            title: '��Ŀ����',
            width: 200,
            align: 'left',
            editor: 'text'
        },{
            field: 'number',
            title: '��Ŀ����',
            width: 60,
            align: 'left',
            editor: 'text'
        }, {
            field: 'stage',
            title: '��Ŀ�׶�',
            width: 60,
            align: 'left',
            editor: 'text',
            formatter: function(value, row, index){
				return stageStatus(value);
            }
        },{
            field: 'ipo_name',
            title: 'ļͶ��Ŀ',
            width: 110,
            align: 'left',
            editor: 'text'
        },{
            field: 'zf_name',
            title: '������Ŀ',
            width: 100,
            align: 'left',
            editor: 'text'
        },{
            field: 'project_type_name',
            title: '��Ŀ����',
            width: 60,
            align: 'left',
            editor: 'text'
        },{
            field: 'begin_date',
            title: '����ʱ��',
            width: 80,
            align: 'left',
            editor: 'text'
        },{
            field: 'manager_name',
            title: '��Ŀ����',
            width: 60,
            align: 'left',
            editor: 'text'
        },{
            field: 'dept_name',
            title: '��������',
            width: 100,
            align: 'left',
            editor: 'text'
        },{
            field: 'status',
            title: '��Ŀ״̬',
            width: 50,
            align: 'left',
            editor: 'text',
            formatter: function(value, row, index){
            	return getStatus(value);
            }
        },{
            field: 'action',
            title: '����',
            width: 100,
            align: 'center',
            formatter: function(value, row, index){
            	var str = '';
            	str += '<a href="#" onclick="show_info(' + index + ')">�鿴</a> ';
            	if(admin =='true'){
            		str += '<a href="#" onclick="edit(' + index + ')">�޸�</a> ';
            		str += '<a href="#" onclick="del(' + index + ')">ɾ��</a>';
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
		button.push({iconCls: 'icon-add',text: '�����Ŀ',handler: function(){insert();}});
	}
	
	if(isAdmin == 'true' || canImport == 'true'){
		button.push({iconCls: 'icon-tip',text: '����',handler: function(){import_file();}});
	}
	
	if(isAdmin == 'true' || canExport == 'true'){
		button.push({iconCls: 'icon-tip',text: '����',handler: function(){export_file();}});
	}
	return button;
}


