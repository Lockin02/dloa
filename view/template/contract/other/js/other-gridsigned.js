var show_page = function(page) {
	$("#otherGrid").yxgrid("reload");
};

$(function() {
	$("#otherGrid").yxgrid({
		model : 'contract_other_other',
		action : 'pageJsonFinanceInfo',
        param : { "signedStatus" : '1','statuses' : '2,3,4' },
		title : '已签收的其他合同',
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'otherGridSigned',
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
			}, {
				name : 'fundTypeName',
				display : '款项性质',
				sortable : true,
				width : 70,
				process : function(v,row){
					if(row.fundType == 'KXXZB'){
						return '<span style="color:blue">' + v  +'</span>';
					}else if( row.fundType == 'KXXZA'){
						return '<span style="color:green">' + v  +'</span>';
					}else{
						return v;
					}
				}
			}, {
				name : 'orderCode',
				display : '鼎利合同号',
				sortable : true,
				width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='变更中的合同' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id+ "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						return "<a href='#' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType+ '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
	            }
			}, {
				name : 'orderName',
				display : '合同名称',
				sortable : true,
				width : 130
			}, {
				name : 'signCompanyName',
				display : '签约公司',
				sortable : true,
				width : 150
			}, {
				name : 'proName',
				display : '公司省份',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'address',
				display : '联系地址',
				sortable : true,
				hide : true
			}, {
				name : 'phone',
				display : '联系电话',
				sortable : true,
				hide : true
			}, {
				name : 'linkman',
				display : '联系人',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'signDate',
				display : '签约日期',
				sortable : true,
				width : 80
			}, {
				name : 'orderMoney',
				display : '合同总金额',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'payApplyMoney',
				display : '申请付款',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款申请金额为：' + moneyFormat2(row.countPayApplyMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'payedMoney',
				display : '已付金额',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB' ){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款金额为：' + moneyFormat2(row.countPayMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'returnMoney',
				display : '返款金额',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'invotherMoney',
				display : '已收发票',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							var thisTitle = '其中初始导入收票金额为: ' + moneyFormat2(row.initInvotherMoney) + ',后期收票金额为：' + moneyFormat2(row.countInvotherMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'applyInvoice',
				display : '申请开票',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA' ){
						if(row.id == 'noId'){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'invoiceMoney',
				display : '已开发票',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						if(row.id == 'noId'){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'incomeMoney',
				display : '收款金额',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						if(row.id == 'noId'){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'principalName',
				display : '合同负责人',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '部门名称',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(v == '0'){
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
			},{
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 60
			},{
				name : 'objCode',
				display : '业务编号',
				sortable : true,
				width : 120,
				hide : true
			},{
	            name : 'isNeedStamp',
	            display : '已申请盖章',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v=="0"){
						return "否";
					}else if( v== "1"){
						return "是";
					}
				},
				hide : true
	        },{
	            name : 'isStamp',
	            display : '是否已盖章',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v=="0"){
						return "否";
					}else if( v== "1"){
						return "是";
					}
	            }
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
		toAddConfig : {
			formWidth : 1000,
			formHeight : 500
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
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
					showModalWin("?model=contract_other_other&action=viewTab&id="
							+ row.id
							+ "&fundType="
							+ row.fundType
							+ "&skey=" + row.skey_
							);
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '签收合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.status == '4') {
						alert('合同处于变更状态，不能进行签收操作');
						return false;
					}
					showOpenWin("?model=contract_other_other&action=toSign&id="
						+ row.id
						+ "&skey=" + row.skey_
					);
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			name : 'file',
			text : '上传附件',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=contract_other_other&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		}],
		// 高级搜索
		advSearchOptions : {
			modelName : 'otherGrid',
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
				name : '款项性质',
				value : 'c.fundType',
				type:'select',
	            datacode : 'KXXZ'
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
			text : "款项性质",
			key : 'fundType',
			datacode : 'KXXZ'
		},{
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
		}]
	});
});