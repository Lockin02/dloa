var show_page = function(page) {
	$("#outsourcingGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#outsourcingGrid").yxgrid({
        model : 'contract_outsourcing_outsourcing',
        param : { "outsourceType" : 'HTWB03' },
        title : '工程项目外包汇总表',
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		customCode : 'outsourcingESMGrid',
        //列信息
        colModel : [{
	            display : 'id',
	            name : 'id',
	            sortable : true,
	            hide : true
	        }, {
				name : 'createDate',
				display : '录入日期',
				width : 70
			},{
	            name : 'projectName',
	            display : '项目名称',
	            sortable : true,
	            width : 150
	        },{
	            name : 'projectCode',
	            display : '项目编号',
	            sortable : true,
	            width : 130
	        },{
	            name : 'orderCode',
	            display : '鼎利合同编号',
	            sortable : true,
	            width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='变更中的合同' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						if(row.ExaStatus == '待提交' || row.ExaStatus == '部门审批'){
							return "<a href='#' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						}else{
							return "<a href='#' onclick='window.open(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						}
					}
	            }
	        },{
	            name : 'orderName',
	            display : '合同名称',
	            sortable : true,
	            width : 130,
	            hide : true
	        },{
	            name : 'signCompanyName',
	            display : '外包公司',
	            sortable : true,
	            width : 130
	        },{
	            name : 'outContractCode',
	            display : '外包合同号',
	            sortable : true,
	            width : 130
	        },{
	            name : 'projectTypeName',
	            display : '项目类型',
	            sortable : true,
	            width : 80
	        },{
	            name : 'outsourcingName',
	            display : '外包方式',
	            sortable : true,
	            width : 80
	        },{
	            name : 'payTypeName',
	            display : '付款方式',
	            sortable : true,
	            width : 80
	        },{
	            name : 'orderMoney',
	            display : '外包金额',
	            sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
	            width : 80
	        },{
	            name : 'beginDate',
	            display : '开始日期',
	            sortable : true,
	            width : 80
	        },{
	            name : 'endDate',
	            display : '结束日期',
	            sortable : true,
	            width : 80
	        },{
	            name : 'allCount',
	            display : '已收发票',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '其中初始导入收票金额为: ' + moneyFormat2(row.initInvoiceMoney) + ',后期收票金额为：' + moneyFormat2(row.orgAllCount);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'applyedMoney',
	            display : '已申请付款',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期申请付款金额为：' + moneyFormat2(row.orgApplyedMoney);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'payedMoney',
	            display : '付款合计',
	            sortable : true,
				process : function(v,row){
					if(v*1 != 0){
						var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款金额为：' + moneyFormat2(row.orgPayedMoney);
						return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
					}else{
						return 0;
					}
				},
	            width : 80
	        },{
	            name : 'signedStatus',
	            display : '合同签收',
	            sortable : true,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v=="1"){
						return "已签收";
					}else{
						return "未签收";
					}
				},
	            width : 70
	        },{
	            name : 'remark',
	            display : '备注说明',
	            sortable : true,
	            width : 150
	        },{
	            name : 'deptName',
	            display : '部门名称',
	            sortable : true,
	            width : 100,
	            hide : true
	        },{
	            name : 'principalName',
	            display : '合同负责人',
	            sortable : true,
	            hide : true
	        }, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v == 0){
						return "未提交";
					}else if(v == 1){
						return "审批中";
					}else if(v == 2){
						return "执行中";
					}else if(v == 3){
						return "已关闭";
					}else if(v == 4){
						return "变更中";
					}
				}
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
	            width : 60,
	            hide : true
			},{
	            name : 'objCode',
	            display : '业务编号',
	            sortable : true,
	            width : 110,
	            hide : true
	        },{
	            name : 'isNeedStamp',
	            display : '已申请盖章',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v=="1"){
						return "是";
					}else{
						return "否";
					}
				},
	            hide : true
	        },{
	            name : 'isStamp',
	            display : '是否已盖章',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v == 1){
						return "是";
					}else{
						return "否";
					}
	            },
	            hide : true
	        },{
	            name : 'stampType',
	            display : '盖章类型',
	            sortable : true,
	            width : 80,
	            hide : true
	        },{
	            name : 'createName',
	            display : '申请人',
	            sortable : true,
	            hide : true
	        },{
	            name : 'updateTime',
	            display : '更新时间',
	            sortable : true,
	            width : 130,
	            hide : true
	        }],
		// 扩展右键菜单
		menusEx : [{
			text : '查看合同',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.ExaStatus == '待提交' || row.ExaStatus == '部门审批'){
						showModalWin("?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ );
					}else{
						showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
					}
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '修改备注',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUpdateInfo&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		// 高级搜索
		advSearchOptions : {
			modelName : 'outsourcing',
			// 选择字段后进行重置值操作
			selectFn : function($valInput) {
				$valInput.yxselect_user("remove");
				$valInput.yxcombogrid_signcompany("remove");
			},
			searchConfig : [{
				name : '鼎利合同号',
				value : 'c.orderCode'
			},{
				name : '签约日期',
				value : 'c.signDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			},{
				name : '签约公司',
				value : 'c.signCompanyName',
				changeFn : function($t, $valInput, rowNum) {
					if (!$("#signCompanyId" + rowNum)[0]) {
						$hiddenCmp = $("<input type='hidden' id='signCompanyId" + rowNum + "'/>");
						$valInput.after($hiddenCmp);
					}
					$valInput.yxcombogrid_signcompany({
						hiddenId : 'signCompanyId' + rowNum,
						height : 250,
						width : 550,
						isShowButton : false
					});
				}
			},{
				name : '公司省份',
				value : 'c.proName'
			},{
				name : '合同金额',
				value : 'c.orderMoney'
			},{
				name : '外包性质',
				value : 'c.outsourceType',
				type:'select',
	            datacode : 'HTWB'
			},{
				name : '项目编号',
				value : 'c.projectCode'
			},{
				name : '外包方式',
				value : 'c.outsourcing',
				type:'select',
				datacode:'HTWBFS'
			},{
				name : '付款方式',
				value : 'c.payType',
				type:'select',
				datacode:'HTFKFS'
			}]
		},
		searchitems : [{
			display : '负责人',
			name : 'principalName'
		}, {
			display : '签约公司',
			name : 'signCompanyName'
		}, {
			display : '合同名称',
			name : 'orderName'
		}, {
			display : '合同编号',
			name : 'orderCodeSearch'
		},{
			display : '业务编号',
			name : 'objCodeSearch'
		}],
		// 默认搜索字段名
		sortname : "c.createTime",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC",
		// 审批状态数据过滤
		comboEx : [{
			text : '合同状态',
			key : 'status',
			data : [{
					text : '未提交',
					value : '0'
				},{
					text : '审批中',
					value : '1'
				}, {
					text : '执行中',
					value : '2'
				}, {
					text : '已关闭',
					value : '3'
				}, {
					text : '变更中',
					value : '4'
				}]
			}, {
				text : '审批状态',
				key : 'ExaStatus',
				data : [{
						text : '待提交',
						value : '待提交'
					}, {
						text : '部门审批',
						value : '部门审批'
					}, {
						text : '变更审批中',
						value : '变更审批中'
					}, {
						text : '打回',
						value : '打回'
					}, {
						text : '完成',
						value : '完成'
					}
				]
			}
		]
    });
});