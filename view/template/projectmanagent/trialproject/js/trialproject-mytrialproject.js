var show_page = function(page) {
	$("#myprojectGrid").yxgrid("reload");
};
$(function() {
	$("#myprojectGrid").yxgrid({
		model : 'projectmanagent_trialproject_trialproject',
		action : 'mypagejson',
		title : '试用项目',
		param : {'applyNameId' : $("#userId").val()},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mytrialprojectGrid',
//		buttonsEx : [{
//			name : 'Add',
//			// hide : true,
//			text : "新增",
//			icon : 'add',
//			action : function(row) {
//				showModalWin('?model=projectmanagent_trialproject_trialproject&action=toAdd');
//			}
//		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true
		},{
		    name : 'serCon',
		    display : '提交状态',
		    sortable : true,
		    process : function(v,row){
		       if(row.id == "noId"){
					return '';
				}
				switch(v){
					case '0' : return '未提交';break;
					case '1' : return '已提交';break;
					case '2' : return '打回';break;
					case '3' : return '延期申请';break;
					case '4' : return '延期申请打回';break;
					case '5' : return '延期申请审批';break;
					default : return v;
				}
		    }
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'status',
			display : '项目状态',
			sortable : true,
			process : function(v,row){
				if(row.id == "noId"){
					return '';
				}
				switch(v){
					case '0' :
					  if (row.serCon == '1'){
					     return '成本确认中';break;
					  }else{
					     return '未提交';break;
					  }
					case '1' : return '审批中';break;
					case '2' : return '待执行';break;
					case '3' : return '执行中';break;
					case '4' : return '已完成';break;
					case '5' : return '已关闭';break;
					default : return v;
				}
			}
		}, {
			name : 'beginDate',
			display : '试用开始时间',
			sortable : true
		}, {
			name : 'closeDate',
			display : '试用结束时间',
			sortable : true
		}, {
			name : 'budgetMoney',
			display : '预计金额',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'applyName',
			display : '申请人',
			sortable : true
		}, {
			name : 'applyNameId',
			display : '申请人ID',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'customerType',
			display : '客户类型Type',
			sortable : true,
			hide : true
		}, {
			name : 'customerTypeName',
			display : '客户类型',
			sortable : true
		}, {
			name : 'customerWay',
			display : '客户联系方式',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'province',
			display : '省份',
			sortable : true,
			hide : true
		}, {
			name : 'city',
			display : '城市',
			sortable : true,
			hide : true
		}, {
			name : 'areaName',
			display : '归属区域',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '区域负责人',
			sortable : true
		}, {
			name : 'areaPrincipalId',
			display : '区域负责人Id',
			sortable : true,
			hide : true
		}, {
			name : 'areaCode',
			display : '区域编号（ID）',
			sortable : true,
			hide : true
		}, {
			name : 'projectDescribe',
			display : '试用要求描述',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人名称',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人Id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人ID',
			sortable : true,
			hide : true
		}, {
			name : 'isFail',
			display : '是否生效',
			sortable : true,
			process : function(v,row){
				switch(v){
					case '0' : return '生效'; break;
					case '1' : return '已转合同'; break;
					case '2' : return '手工关闭'; break;
					default : return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>"
						+ '</a>';
			}
		}, {
			name : 'contractId',
			display : '合同id',
			sortable : true,
			hide : true
		}],
        // 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_trialproject&action=viewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.serCon == '0' || row.serCon == '2') && row.isFail == '0' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_trialproject&action=init&id='
						+ row.id
						+ '&perm=edit'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '提交',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.serCon == '0' || row.serCon == '2') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
			  if(row.expand == '1'){
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=ajaxUpdateSer",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								showThickboxWin('controller/projectmanagent/trialproject/ewf_index1.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			  }else{
			    if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=subConproject",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			  }
			}

		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (( row.serCon == '0' || row.serCon == '2') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}, {
			text : '延期申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && (row.serCon == '1' || row.serCon == '4') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_trialproject_extension&action=toAdd&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '关闭',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && (row.serCon == '1' || row.serCon == '4') && row.isFail == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要关闭?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_trialproject_trialproject&action=ajaxCloseTr",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('关闭成功！');
								$("#myprojectGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],
		comboEx : [
			{
				text : '审批状态',
				key : 'ExaStatus',
				data : [{
					text : '未审批',
					value : '未审批'
				}, {
					text : '部门审批',
					value : '部门审批'
				},{
					text : '打回',
					value : '打回'
				}, {
					text : '完成',
					value : '完成'
				}]
			}
		],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '项目编号',
					name : 'projectCode'
				}, {
					display : '项目名称',
					name : 'projectName'
				}, {
					display : '客户名称',
					name : 'customerName'
				}]
	});
});