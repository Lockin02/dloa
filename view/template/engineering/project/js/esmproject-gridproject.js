var show_page = function(page) {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {

	var attributeVal =  $("#attribute").val();

	//表头按钮数组
	buttonsArr = [];

	//表头按钮数组
	excelOutArr = [{
			name : 'exportOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				window.open(
					"?model=engineering_project_esmproject&action=exportExcel&attribute=" + attributeVal,
					"", "width=200,height=200,top=200,left=200");
			}
		}
	];

	$.ajax({
		type : 'POST',
		url : '?model=engineering_project_esmproject&action=getLimits',
		data : {
			'limitName' : '导入导出权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr = excelOutArr;
			}
		}
	});

    $("#esmprojectGrid").yxgrid({
        model : 'engineering_project_esmproject',
        title : '试用项目汇总表',
        param : {'attribute' : attributeVal },
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		showcheckbox : false,
		customCode : 'esmAttGrid',
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'officeId',
            display : '区域ID',
            sortable : true,
            hide : true
        },{
            name : 'officeName',
            display : '区域',
            sortable : true
        },{
            name : 'country',
            display : '国家',
            sortable : true,
            width : 70,
            hide : true
        },{
            name : 'province',
            display : '省份',
            sortable : true,
            width : 70
        },{
            name : 'city',
            display : '城市',
            sortable : true,
            width : 70,
            hide : true
        },{
            name : 'attributeName',
            display : '项目属性',
            width : 80,
			process : function(v,row){
				switch(row.attribute){
					case 'GCXMSS-01' : return "<span class='red'>" + v + "</span>";break;
					case 'GCXMSS-02' : return "<span class='blue'>" + v + "</span>";break;
					case 'GCXMSS-03' : return "<span class='green'>" + v + "</span>";break;
					default : return v;
				}
			}
        },{
            name : 'projectName',
            display : '项目名称',
            sortable : true,
            width : 150,
            process : function(v,row){
				if((row.contractId == "0"  || row.contractId == "")&& row.contractType != 'GCXMYD-04'){
					return "<span style='color:blue' title='未关联合同号的项目'>" + v + "</span>";
				}else{
					return v;
				}
            }
        },{
            name : 'projectCode',
            display : '项目编号',
            sortable : true,
            width : 120,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
			}
        },{
            name : 'status',
            display : '项目状态',
            sortable : true,
			datacode : 'GCXMZT',
            width : 80
        },{
            name : 'budgetAll',
            display : '总预算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetField',
            display : '现场预算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetPerson',
            display : '人力预算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetEqu',
            display : '设备预算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetOther',
            display : '其他预算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'budgetOutsourcing',
            display : '外包预算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeAll',
            display : '总决算(财务确认)',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
        },{
            name : 'feeAllCount',
            display : '总决算(实时)',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
        },{
            name : 'feeFieldCount',
            display : '现场决算(实时)',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
        },{
            name : 'feePerson',
            display : '人力决算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeEqu',
            display : '设备决算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeOutsourcing',
            display : '外包决算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeOther',
            display : '其他决算',
            sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
            width : 80
        },{
            name : 'feeAllProcess',
            display : '费用进度(财务确认)',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 120
        },{
            name : 'feeAllProcessCount',
            display : '费用进度(实时)',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 120
        },{
            name : 'feeFieldProcessCount',
            display : '现场费用进度(实时)',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 120
        },{
//            name : 'feeFieldProcess',
//            display : '现场费用进度',
//            sortable : true,
//			process : function(v,row){
//				if( row.id == 'noId') return '';
//				return v + ' %';
//			},
//            width : 80,
//            hide : true
//        },{
            name : 'projectProcess',
            display : '工程进度',
            sortable : true,
			process : function(v,row){
				if( row.id == 'noId') return '';
				return v + ' %';
			},
            width : 80
        },{
            name : 'contractTypeName',
            display : '源单类型',
            sortable : true,
            hide : true
        },{
            name : 'contractId',
            display : '鼎利合同id',
            sortable : true,
            hide : true
        },{
            name : 'contractCode',
            display : '鼎利合同编号(源单编号)',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'contractTempCode',
            display : '临时合同编号',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'rObjCode',
            display : '业务编号',
            sortable : true,
            width : 120,
            hide : true
        },{
            name : 'contractMoney',
            display : '合同金额',
            sortable : true,
            process : function(v){
            	return moneyFormat2(v);
            }
        },{
            name : 'customerId',
            display : '客户id',
            sortable : true,
            hide : true
        },{
            name : 'customerName',
            display : '客户名称',
            sortable : true,
            hide : true
        },{
//            name : 'proName',
//            display : '所属省份',
//            sortable : true,
//            hide : true
//        },{
            name : 'depName',
            display : '所属部门',
            sortable : true,
            hide : true
        },{
            name : 'planBeginDate',
            display : '预计启动日期',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80,
            hide : true
        },{
            name : 'planEndDate',
            display : '预计结束日期',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80
        },{
            name : 'actBeginDate',
            display : '实际开始时间',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80
        },{
            name : 'actEndDate',
            display : '实际完成时间',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			},
            width : 80
        },{
            name : 'managerName',
            display : '项目经理',
            sortable : true
        },{
            name : 'ExaStatus',
            display : '审批状态',
            sortable : true,
            width : 80
        },{
            name : 'ExaDT',
            display : '审批日期',
            sortable : true,
            hide : true,
            width : 80
        },{
            name : 'peopleNumber',
            display : '总人数',
            sortable : true,
            width : 80
        },{
            name : 'natureName',
            display : '性质1',
            sortable : true
        },{
            name : 'nature2Name',
            display : '性质2',
            sortable : true
        },{
            name : 'outsourcingName',
            display : '外包类型',
            sortable : true,
            width : 80
        },{
            name : 'cycleName',
            display : '长/短期',
            sortable : true,
            width : 80
        },{
            name : 'categoryName',
            display : '项目类别',
            sortable : true,
            width : 80
        },{
            name : 'platformName',
            display : '方案及平台',
            sortable : true,
            width : 80
        },{
            name : 'netName',
            display : '网络',
            sortable : true,
            width : 80
        },{
            name : 'createTypeName',
            display : '建立方式',
            sortable : true,
            width : 80
        },{
            name : 'signTypeName',
            display : '签订方式',
            sortable : true,
            width : 80
        },{
            name : 'toolType',
            display : '工具类型',
            sortable : true,
            width : 80
        },{
            name : 'updateTime',
            display : '最近更新',
            sortable : true,
            width : 120
        }],
		subGridOptions : {
			model:'finance_invoice_invoice',
    		action:'pageJsonInfoList',
			// 显示的列
			colModel : [{
					display : '项目名称',
					name : 'projectName'
				},{
					display : '项目编号',
					name : 'projectCode'
				}
			]
		},
		lockCol:['projectName','projectCode'],//锁定的列名
		toEditConfig : {
			formWidth : 1100,
			formHeight : 550,
			showMenuFn : function(row) {
//				暂时关闭项目编辑功能
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
		// 扩展右键菜单
		menusEx : [{
				text : '查看项目',
				icon : 'view',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
				},
				action : function(row, rows, grid) {
					showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
							+ row.id);
				}
			},{
				text : '编辑项目',
				icon : 'edit',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
					if ((row.ExaStatus == "完成")) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=engineering_project_esmproject&action=toEditRight&id="
							+ row.id
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1100");
				}
			},{
				name : 'aduit',
				text : '审批情况',
				icon : 'view',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
					if ((row.ExaStatus == "完成" || row.ExaStatus == "打回")) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						showThickboxWin("controller/common/readview.php?itemtype=oa_esm_project&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
					}
				}
			},
			{
				text: "删除",
				icon: 'delete',
				showMenuFn : function(row) {
					if ((row.id == "noId")) {
						return false;
					}
				},
				action: function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_project_esmproject&action=ajaxdeletes",
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
			}
		],
		buttonsEx : buttonsArr,
      // 高级搜索
//		advSearchOptions : {
//			modelName : 'esmprojectSearch',
//			// 选择字段后进行重置值操作
//			selectFn : function($valInput) {
//				$valInput.yxselect_user("remove");
//				$valInput.yxcombogrid_office("remove");
//			},
//			searchConfig : [{
//					name : '项目名称',
//					value : 'c.projectName'
//				},{
//					name : '项目编号',
//					value : 'c.projectCode'
//				},{
//					name : '办事处',
//					value : 'c.officeName',
//					changeFn : function($t, $valInput, rowNum) {
//						if (!$("#officeId" + rowNum)[0]) {
//							$hiddenCmp = $("<input type='hidden' id='officeId" + rowNum + "'/>");
//							$valInput.after($hiddenCmp);
//						}
//						$valInput.yxcombogrid_office({
//							hiddenId : 'officeId' + rowNum,
//							height : 200,
//							width : 550,
//							gridOptions : {
//								showcheckbox : false
//							}
//						});
//					}
//				},{
//					name : '项目经理',
//					value : 'c.managerName',
//					changeFn : function($t, $valInput, rowNum) {
//						if (!$("#managerId" + rowNum)[0]) {
//							$hiddenCmp = $("<input type='hidden' id='managerId"+ rowNum + "'/>");
//							$valInput.after($hiddenCmp);
//						}
//						$valInput.yxselect_user({
//							hiddenId : 'managerId' + rowNum,
//							height : 200,
//							width : 550,
//							gridOptions : {
//								showcheckbox : false
//							}
//						});
//					}
//				},{
//					name : '项目状态',
//					value : 'c.status',
//					type:'select',
//					datacode : 'GCXMZT'
//				},{
//		            name : '网络性质',
//      				value : 'nature',
//					type:'select',
//		            datacode : 'GCXMXZ'
//		        },{
//		            name : '外包类型',
//		            value : 'outsourcing',
//					type:'select',
//					datacode : 'WBLX'
//		        },{
//		            name : '长/短期',
//		            value : 'cycle',
//					type:'select',
//		            datacode : 'GCCDQ'
//		        },{
//		            name : '项目类别',
//		            value : 'category',
//					type:'select',
//		            datacode : 'XMLB'
//		        }
//			]
//		},
		searchitems : [{
			display : '办事处',
			name : 'officeName'
		}, {
			display : '项目编号',
			name : 'projectCodeSearch'
		}, {
			display : '项目名称',
			name : 'projectName'
		}, {
			display : '项目经理',
			name : 'managerName'
		},	{
			display : '业务编号',
			name : 'rObjCodeSearch'
		}, {
			display : '鼎利合同号',
			name : 'contractCodeSearch'
		},	{
			display : '临时合同号',
			name : 'contractTempCodeSearch'
		}],
		// 审批状态数据过滤
		comboEx : [{
			text: "审批状态",
			key: 'ExaStatus',
			type : 'workFlow'
		},{
			text: "项目状态",
			key: 'status',
			datacode : 'GCXMZT',
			value : 'GCXMZT02'
		}],
		// 默认搜索字段名
		sortname : "c.updateTime",
		// 默认搜索顺序 降序
		sortorder : "DESC"

    });
});