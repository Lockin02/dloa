var show_page = function(page) {
	$("#MyBorrowGrid").yxgrid("reload");
};
$(function() {
			$("#MyBorrowGrid").yxgrid({
			    model : 'projectmanagent_borrow_borrow',
//			    action : 'listForChance' ,
                param : {"chanceId" : $("#chanceId").val()},
               	title : '我的借试用',
               	//按钮
				isViewAction : false,
				isAddAction : false,
				isEditAction : false,
				isDelAction : false,

						//列信息
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
									name : 'ExaDT',
				  					display : '审批时间',
				  					sortable : true
				              },{
				                    name : 'createName',
				                    display : '创建人',
				                    sortable : true
				              }],
                               		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id=" + row.id + "&skey="+row['skey_']);
//					showThickboxWin("?model=projectmanagent_borrow_borrow&action=init&id=" + row.id
//									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
//					);
				}
			}

		},{
			text : '编辑',
			icon : 'edit',
            showMenuFn : function(row){
				if(row.ExaStatus == '未审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {

					showOpenWin("?model=projectmanagent_borrow_borrow&action=init&id="
							+ row.id + "&skey="+row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '未审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row,rows,grid){
				if (row) {
					showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}]
 		});



 });