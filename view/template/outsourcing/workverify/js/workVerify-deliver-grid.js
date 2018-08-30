var show_page = function(page) {
	$("#workVerifyGrid").yxgrid("reload");
};
$(function() {
			$("#workVerifyGrid").yxgrid({
				model : 'outsourcing_workverify_workVerify',
				isEditAction:false,
				isAddAction:false,
				isDelAction:false,
				showcheckbox:false,
				bodyAlign:'center',
				param:{'statusArr':'2,3,4'},
               	title : '工作量确认单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'formCode',
                  					display : '单据编号',
                  					width:150,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_workVerify&action=toDeliverView&id=" + row.id +"\")'>" + v + "</a>";
									}
                              },{
                    					name : 'status',
                  					display : '状态',
                  					width:70,
                  					sortable : true,
									process:function(v){
											if(v=="1"){
												return "提交审批";
											}else if(v=="2"){
												return "交付确认";
											}else if(v=="3"){
												return "已确认";
											}else if(v=="4"){
												return "关闭";
											}else if(v=="5"){
												return "审批完成";
											}else {
												return "未提交";
											}
									}
                              },{
                    					name : 'formDate',
                  					display : '单据时间',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '周期开始日期',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '周期结束日期',
                  					sortable : true
                              } ,{
                    					name : 'createName',
                  					display : '创建人',
                  					width:70,
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					width:450,
                  					sortable : true
                              }],

                              //下拉过滤
			comboEx : [{
				text : '状态',
				key : 'status',
				data : [{
						text : '交付确认',
						value : '2'
					},{
						text : '已确认',
						value : '3'
					},{
						text : '关闭',
						value : '4'
					}]
				}
			],
					// 扩展右键菜单

		menusEx : [{
				text : '工作量确认',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '2') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_workVerify&action=toDeliverEdit&id=" +row.id);

				}

			},
			{
				text : '导出',
				icon : 'excel',
				action :function(row,rows,grid) {
					if(row){
						location="?model=outsourcing_workverify_workVerify&action=exportWorkVerify&id="+row.id+"&skey="+row['skey_'];
					}else{
						alert("请选中一条数据");
					}
				}

			}],
		toViewConfig : {
//			action : 'toView',
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=outsourcing_workverify_workVerify&action=toDeliverView&id=" + get[p.keyField]);
				}
			}
		},
		searchitems : [{
					display : "单据编号",
					name : 'formCode'
				},{
					display : "创建人",
					name : 'createName'
				}]
 		});
 });