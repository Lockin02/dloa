var show_page = function(page) {
	$("#useStatusGrid").yxgrid("reload");
};
$(function() {
			$("#useStatusGrid").yxgrid({
				      model : 'asset_basic_useStatus',
               	title : '使用状态',
				//列信息
				colModel : [{
	         				display : 'id',
	         				name : 'id',
	         				sortable : true,
	         				hide : true
						},{
	                    	name : 'name',
	                  		display : '使用状态',
	                  		sortable : true,
			                // 特殊处理字段函数
			                process : function(v, row) {
			                	if(v=='1'){
			                	return "使用中";
			                	}
			                	if(v=='2'){
			                	return "闲置";
			                	}
			                	if(v=='3'){
			                	return "维修中";
			                	}
			                	if(v=='4'){
			                	return "已出租";
			                	}
			                	if(v=='5'){
			                	return "其它";
			                	}
			                }
	                    },{
	                    	name : 'deprFlag',
	                  		display : '是否计提折旧',
	                  		sortable : true,
			                // 特殊处理字段函数
			                process : function(v, row) {
			                	if(v=='y'){
			                	return "是";
			                	}
			                	if(v=='n'){
			                	return "否";
			                	}
			                }
	                    },{
	                    	name : 'remark',
	                  		display : '备注',
	                  		sortable : true,
	                  		width : 200
	                    }],
			toAddConfig : {
				formWidth : 700,
				/**
				 * 新增表单默认高度
				 */
				formHeight : 300
			},
			toViewConfig : {
				/**
				 * 查看表单默认宽度
				 */
				formWidth : 700,
				/**
				 * 查看表单默认高度
				 */
				formHeight : 300
			},
			toEditConfig : {
				/**
				 * 编辑表单默认宽度
				 */
				formWidth : 700,
				/**
				 * 编辑表单默认高度
				 */
				formHeight : 300
			}
 		});
 });