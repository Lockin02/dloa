var show_page = function(page) {
	$("#cassessGrid").yxgrid("reload");
};
$(function() {
	$("#cassessGrid").yxgrid({
		model : 'hr_certifyapply_cassess',
		title : '我的任职资格等级认证评价表',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'modelName',
				display : '匹配模板名称',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true,
				hide : true
			}, {
				name : 'userAccount',
				display : '员工账号',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'deptName',
				display : '部门名称',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'jobName',
				display : '职位名称',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowDirectionName',
				display : '当前通道',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowLevelName',
				display : '当前级别',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowGradeName',
				display : '当前级等',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '申请通道',
				sortable : true,
				width : 80
			}, {
				name : 'baseLevelName',
				display : '申请级别',
				sortable : true,
				width : 70
			}, {
				name : 'baseGradeName',
				display : '申请级等',
				sortable : true,
				width : 70
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 80,
				process : function(v){
					switch(v){
						case '0' : return '保存';break;
						case '1' : return '认证准备中';break;
						case '2' : return '审批中';break;
						case '3' : return '完成待评分';break;
						case '4' : return '完成已评分';break;
						case '5' : return '确认审核中';break;
						case '6' : return '审核已完成';break;
						default : return v;
					}
				}
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 80
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true,
				width : 80
			}, {
				name : 'managerName',
				display : '主审评委',
				sortable : true,
				width : 80
			}, {
				name : 'memberName',
				display : '参与评委',
				sortable : true,
				width : 150
			}, {
				name : 'scoreAll',
				display : '评分',
				sortable : true,
				width : 70
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 120
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
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
			name : 'edit',
			text : "提交认证材料",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				//判断
				showModalWin("?model=hr_certifyapply_cassess&action=toEditDetail&id=" + row.id + "&skey=" + row.skey);
			}
		},{
			name : 'audit',
			text : "提交审批",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('controller/hr/certifyapply/ewf_index.php?actTo=ewfSelect&billId='
					+ row.id + '&billDept=' + row.deptId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			}
		}],
		searchitems : [{
			display : "员工姓名",
			name : 'userNameSearch'
		}]
	});
});