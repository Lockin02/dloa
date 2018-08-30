var show_page = function(page) {
	$("#renewGrid").yxsubgrid("reload");
};
$(function() {
			$("#renewGrid").yxsubgrid({
				      model : 'projectmanagent_borrow_renew',
				      param : { 'borrowId' : $("#borrowId").val(),'ExaStatusArr' : '完成,打回'},
				      isRightMenu : false,
				      isAddAction : false,
				      isEditAction : false,
				      isViewAction : false,
				      isDelAction : false,
				      showcheckbox : false,
               	title : '员工借试用续借单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'borrowId',
                  					display : '源单ID',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'raendDate',
                  					display : '原截止日期',
                  					sortable : true
                              },{
                    					name : 'reendDate',
                  					display : '续借截止日期',
                  					sortable : true
                              },{
                    					name : 'renewremark',
                  					display : '续借原因',
                  					sortable : true,
                  					width : 200
                              },{
                    					name : 'renewdate',
                  					display : '续借时间',
                  					sortable : true
                              },{
                    					name : 'renewName',
                  					display : '续借人',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '审批状态',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '审批日期',
                  					sortable : true
                              }],
                              // 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_renewequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'renewId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'productNo',
						width : 100,
						display : '产品编号'
					},{
						name : 'productName',
						width : 100,
						display : '产品名称'
					},{
						name : 'productModel',
						width : 100,
						display : '产品型号'
					}, {
					    name : 'number',
					    display : '续借数量',
						width : 80
					}, {
					    name : 'serialName',
					    display : '序列号',
						width : 400
					}]
		   }
 		});
 });