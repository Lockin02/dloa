var show_page = function(page) {
	$("#deductGrid").yxgrid("reload");};
$(function() {
		$("#deductGrid").yxgrid({
				model : 'outsourcing_deduct_deduct',
               	title : '外包扣款单',
				isEditAction:false,
				isDelAction:false,
				showcheckbox:fals
				param:{'createId':$("#createId").val()},
				bodyAlign:'center',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'formCode',
                  					display : '单据编号',
                  					width:140,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showThickboxWin(\"?model=outsourcing_deduct_deduct&action=toView&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"+ v + "</a>";
									}
                              },{
                    					name : 'formDate',
                  					display : '单据时间',
                  					width:70,
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '审批状态',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'outsourceSupp',
                  					display : '外包供应商',
                  					width:120,
                  					sortable : true
                              },{
                    					name : 'projectCode',
                  					display : '项目编号',
                  					width:120,
                  					sortable : true
                              },{
                    					name : 'projecttName',
                  					display : '项目名称',
                  					width:120,
                  					sortable : true
                              }, {
                    					name : 'outsourceContractCode',
                  					display : '外包合同编号',
                  					sortable : true
                              }  ,{
                    					name : 'deductTotal',
                  					display : '扣款金额',
                  					width:70,
                  					sortable : true
                              }],

                              					// 扩展右键菜单

		menusEx : [{
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.ExaStatus == '未提交'||row.ExaStatus == '打回') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin("?model=outsourcing_deduct_deduct&action=toEdit&id=" +row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");

				}

			},{
				text : '提交审批',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '未提交'||row.ExaStatus == '打回') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin("controller/outsourcing/deduct/ewf_index.php?actTo=ewfSelect&billId=" +row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}

			},{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '未提交'||row.ExaStatus == '打回') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_deduct_deduct&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#deductGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
					name : 'aduit',
					text : '审批情况',
					icon : 'view',
					showMenuFn : function(row) {
						if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_deduct&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
				}],

		comboEx:[{
			text:'审批状态',
			key:'ExaStatus',
			data:[{
			   text:'未提交',
			   value:'未提交'
			},{
			   text:'部门审批',
			   value:'部门审批'
			},{
			   text:'打回',
			   value:'打回'
			},{
			   text:'完成',
			   value:'完成'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},

		toAddConfig : {
			formHeight:600
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "单据编号",
					name : 'formCode'
				},{
					display : "单据时间",
					name : 'formDate'
				},{
					display : "外包供应商",
					name : 'outsourceSupp'
				},{
					display : "项目编号",
					name : 'projectCode'
				},{
					display : "项目名称",
					name : 'projecttName'
				},{
					display : "外包合同编号",
					name : 'outsourceContractCode'
				}]
 		});
 });