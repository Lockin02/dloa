var show_page = function(page) {
	$("#certifyresultGrid").yxgrid("reload");
};
$(function() {
	$("#certifyresultGrid").yxgrid({
		model : 'hr_certifyapply_certifyresult',
		action : 'myPageJson',
		title : '我的任职资格审核表',
		isAddAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
					display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'periodName',
				display : '周期名称',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirection',
				display : '职业发展通道',
				sortable : true,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '通道名称',
				sortable : true
			}, {
				name : 'formDate',
				display : '填报日期',
				sortable : true
			}, {
				name : 'formUserId',
				display : '填表人',
				sortable : true,
				hide : true
			}, {
				name : 'formUserName',
				display : '填表人',
				sortable : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true
			}, {
				name : 'status',
				display : '单据状态',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return '保存';break;
						case '1' : return '审批中';break;
						case '2' : return '完成';break;
						default : return v;
					}
				}
			}, {
				name : 'createId',
				display : '创建人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 130
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//判断
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig : {
			action : 'toView',
			showMenuFn : function(row) {
				return true;
			},
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//判断
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		menusEx : [{
			name : 'audit',
			text : "提交审批",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('controller/hr/certifyapply/ewf_certifyresult.php?actTo=ewfSelect&billId='
					+ row.id + '&billDept=' + row.deptId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			}
		},{
			name : 'delete',
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_certifyapply_certifyresult&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							}else{
								alert("删除失败! ");
							}
						}
					});
				}
			}
		}],
        //过滤数据
		comboEx:[{
		     text:'审批状态',
		     key:'ExaStatus',
		     type : 'workFlow'
	   }],
		searchitems : [{
			display : "职业发展通道",
			name : 'careerDirectionNameSearch'
		}]
	});
});