var show_page = function(page) {
	$("#applyOrderGrid").yxgrid("reload");
};
$(function() {
	$("#applyOrderGrid").yxgrid({
		model : 'projectmanagent_order_order',
//		action : 'pageJsonMyProject',
		title : '在审批的合同',
		param : { 'createId' : $('#UserId').val()},
//		isToolBar : false,
		isDelAction : false,
	    isToolBar : true, //是否显示工具栏
	    showcheckbox : false,
	    /**
		 * 是否显示添加按钮/菜单
		 *
		 * @type Boolean
		 */
		isAddAction : true,
		/**
		 * 是否显示查看按钮/菜单
		 *
		 * @type Boolean
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 *
		 * @type Boolean
		 */
		isEditAction : false,

		//列信息

		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
 					display : 'createId',
 					name : 'createId',
 					sortable : true,
 					hide : true
			  },{
					name : 'orderCode',
  					display : '合同编号',
  					sortable : true
              },{
					name : 'orderName',
  					display : '合同名称',
  					sortable : true
              },{
					name : 'customerName',
  					display : '客户名称',
  					sortable : true
              },{
					name : 'customerType',
  					display : '客户类型',
  					sortable : true,
  					datacode : 'KHLX'
              },{
					name : 'prinvipalName',
  					display : '合同负责人',
  					sortable : true
              },{
					name : 'deliveryDate',
  					display : '交货日期',
  					sortable : true
              },{
					name : 'state',
  					display : '合同状态',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "未提交";
  						}else if(v == '1'){
  							return "保存";
  						}else if(v == '2'){
  							return "执行中";
  						}else if(v == '3'){
  							return "关闭";
  						}else if(v == '4'){
  							return "已生成合同";
  						}else if(v == '5'){
  							return "已签合同";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '审批状态',
  					sortable : true,
  					width : 90
              },{
					name : 'ExaDT',
  					display : '审批时间',
  					sortable : true
              }
          ],

          menusEx : [

          	{
			text : '查看',
			icon : 'view',
			action: function(row){
                showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
						+ row.id
                        + '&perm=view'
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		   }
		   ,{
				text : '编辑',
				icon : 'edit',
	            showMenuFn : function (row){
				  if((row.ExaStatus=='保存' || row.ExaStatus=='打回') && (row.state == '0' || row.state == '1'|| row.state =='4' ||row.state == '5')){
				       return true;
				   }
				       return false;
				},
				action: function(row){
                showOpenWin('?model=projectmanagent_order_order&action=init&id='
						+ row.id
                        + '&perm=edit'
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			          }
	        }
	        ,{

			text : '录入合同',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '3') {
									return false;
								}
								return true;
							},


			action : function(row) {

				self.location = "?model=projectmanagent_order_order&action=toSales&id="
						+ row.id;

			}
		},{
				text : '提交审批',
				icon : 'add',
	            showMenuFn : function (row){
				   if((row.ExaStatus=='保存' || row.ExaStatus=='打回') && (row.state == '0' || row.state == '1'|| row.state =='4' ||row.state == '5')){
				       return true;
				   }
				       return false;
				},
				action: function(row){
					location = 'controller/projectmanagent/order/ewf_index.php?actTo=ewfSelect&formName=销售合同审批&examCode=oa_sale_order&billId='
							+ row.id
			        }
	        }
//	        ,{
//			text : '删除',
//			icon : 'delete',
//			 showMenuFn : function(row) {
//				 if ( row.ExaStatus == '完成' || row.ExaStatus == '部门审批' || row.ExaStatus == '打回'|| row.status == '3') {
//					 return false;
//				 }
//				 return true;
//			 },
//			action : function(row) {
//				if(confirm("确定要删除吗？")){
//					showThickboxWin('?model=projectmanagent_order_order&action=deletesInfo&id='
//						+ row.id
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
//				}
//
//			}
//
//		}
		],
           /**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		},{
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		}],
         sortname : "createTime",
          //设置编辑页面宽度
          toEditConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //设置查看页面宽度
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          },

	     toAddConfig : {
			text : '新建',
			icon : 'add',
			/**
			 * 默认点击新增按钮触发事件
			 */

			toAddFn : function(p) {
               self.location ="?model=projectmanagent_order_order&action=toadd";
			}
		}

	});
});