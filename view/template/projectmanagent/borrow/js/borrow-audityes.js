/**收票管理列表**/

var show_page=function(page){
   $("#borrowGrid").yxgrid("reload");
};

$(function(){
        $("#borrowGrid").yxgrid({

        	model:'projectmanagent_borrow_borrow',
        	action:'pageJsonAuditYes',
        	title:'已审批销售订单',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,
        	isAddAction:false,
        	isEditAction:false,
        	isDelAction:false,

			colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'chanceId',
                  					display : '商机Id',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'Code',
                  					display : '编号',
                  					sortable : true
                              },{
                    					name : 'Type',
                  					display : '类型',
                  					sortable : true
                              },{
                    					name : 'customerName',
                  					display : '客户名称',
                  					sortable : true
                              },{
                    					name : 'limits',
                  					display : '范围',
                  					sortable : true
                              },{
                    					name : 'beginTime',
                  					display : '开始日期',
                  					sortable : true
                              },{
                    					name : 'closeTime',
                  					display : '截止日期',
                  					sortable : true
                              },{
                    					name : 'salesName',
                  					display : '销售负责人',
                  					sortable : true
                              },{
                    					name : 'scienceName',
                  					display : '技术负责人',
                  					sortable : true
                              },{
									name : 'ExaStatus',
				  					display : '审批状态',
				  					sortable : true,
				  					width : 90
				              },{
										display : '审批时间',
										name : 'ExaDT',
										width:80
									}],


					//扩展右键菜单
					menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id="+ row.borrowId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					}
					],
			searchitems:[
			        {
			            display:'编号',
			            name:'Code'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});