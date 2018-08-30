var show_page = function(page) {
	$("#workVerifyGrid").yxgrid("reload");
};
$(function() {
			$("#workVerifyGrid").yxgrid({
				model : 'outsourcing_workverify_workVerify',
				isEditAction:false,
				isDelAction:false,
				showcheckbox:false,
				param:{'createId':$("#createId").val()},
				bodyAlign:'center',
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
											return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_workVerify&action=toView&id=" + row.id +"\")'>" + v + "</a>";
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
						text : '未提交',
						value : '0'
					},{
						text : '提交审批',
						value : '1'
					},{
						text : '审批完成',
						value : '5'
					},{
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
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_workVerify&action=toEdit&id=" +row.id);

				}

			},{
				text : '提交审批',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要提交?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_workVerify&action=changeState",
							data : {
								id : row.id,
								status:1
							},
							success : function(msg) {
								if (msg == 1) {
									alert('提交成功！');
									$("#workVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '1') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_workVerify&action=toAuditEdit&id=" +row.id);

				}

			},{
				text : '提交交付审批',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '5') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要提交?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_workVerify&action=changeState",
							data : {
								id : row.id,
								status:2
							},
							success : function(msg) {
								if (msg == 1) {
									alert('提交成功！');
									$("#workVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_workVerify&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#workVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
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

          toAddConfig : {
			formWidth : 1000,
			formHeight : 500,
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_workverify_workVerify&action=toAdd");
			}
		},


		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			toEditFn : function(p, g) {
					var get = g.getSelectedRow().data('data');
				showModalWin("?model=outsourcing_workverify_workVerify&action=toEdit&id=" + get[p.keyField]);
			}
		},
		toViewConfig : {
//			action : 'toView',
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=outsourcing_workverify_workVerify&action=toView&id=" + get[p.keyField]);
				}
			}
		},
		searchitems : [{
					display : "单据编号",
					name : 'formCode'
				}]
 		});
 });