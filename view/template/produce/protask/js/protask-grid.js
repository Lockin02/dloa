var show_page = function(page) {
	$("#protaskGrid").yxgrid("reload");
};
$(function() {
	$("#protaskGrid").yxgrid({
		model : 'produce_protask_protask',
		title : '生产任务书',
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'protaskGrid',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'taskCode',
			display : '生产任务编号',
			width : '120',
			hide : true,
			sortable : true
		}, {
			name : 'issuedDate',
			display : '下达日期',
			width : '75',
			sortable : true
		}, {
			name : 'referDate',
			display : '交货日期',
			width : '75',
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : '180',
			sortable : true
		}, {
			name : 'relDocId',
			display : '源单id',
			sortable : true,
			hide : true
		}, {
			name : 'rObjCode',
			display : '关联业务编号',
			width : '120',
			sortable : true
		}, {
			name : 'relDocCode',
			display : '合同/借试用编号',
			width : '180',
			hide : true,
			sortable : true
		}, {
			name : 'relDocType',
			display : '源单类型',
			width : '60',
			sortable : true,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "销售合同";
				}else if (v == 'oa_sale_lease') {
					return "租赁合同";
				}else if (v == 'oa_sale_service'){
				    return "服务合同";
				}else if (v == 'oa_sale_rdproject'){
				    return "研发合同";
				}else if (v == 'oa_borrow_borrow'){
				    return "借用合同";
				}else if (v == 'oa_present_present'){
				    return "赠送申请";
				}
			}
		}, {
			name : 'relDocName',
			display : '源单名称',
			width : '180',
			hide : true,
			sortable : true
		}, {
			name : 'proStatus',
			display : '单据状态',
			width : '60',
			sortable : true,
			process : function(v){
				( v=='YWC' ) ? (v='已完成') : ( v='未完成' );
				return v;
			}
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			width : '60',
			sortable : true,
			process : function(v){
				( v==0 ) ? (v='未下达') : ( v='已下达' );
				return v;
			}
		}, {
			name : 'issuedDeptName',
			display : '下单部门',
			hide : true,
			sortable : true
		}, {
			name : 'execDeptName',
			display : '执行部门',
			hide : true,
			sortable : true
		}, {
			name : 'qualityType',
			display : '验证类型',
			sortable : true,
			hide : true
		}, {
			name : 'taskType',
			display : '性质',
			sortable : true,
			hide : true
		}, {
			name : 'issuedman',
			display : '下达人',
			hide : true,
			sortable : true
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_protask_protask&action=toView&id='
						+ row.id
						+ '&relDocType='
						+ row.relDocType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		} ,{
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if( row.issuedStatus != '1' ){
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=produce_protask_protask&action=toEdit&id='
						+ row.id
						+ '&relDocType='
						+ row.relDocType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		},{
            text: "完成",
			icon : 'delete',
			showMenuFn : function(row) {
				if( row.proStatus != 'YWC'){
					return true;
				}
				return false;
			},
            action: function(row){
            	if(confirm('确认生产任务已完成？')){
					 $.ajax({
						type : 'POST',
						url : '?model=produce_protask_protask&action=finishTask&skey='+row['skey_'],
						data : {
							id : row.id
						},
	//				    async: false,
						success : function(data) {
//							if( data == 2 ){
//								alert( '没有权限,需要开通权限请联系oa管理员' );
//							}else{
								alert("任务已完成");
								show_page();
//							}
							return false;
						}
					});
            	}
            }
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '生产任务编号',
			name : 'taskCode'
		}, {
			display : '关联业务单编号',
			name : 'rObjCode'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '源单号',
			name : 'relDocCode'
		}],
		sortname : 'issuedDate',
		sortorder : 'DESC'
	});
});