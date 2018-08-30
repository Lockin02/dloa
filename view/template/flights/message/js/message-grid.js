var show_page = function(page) {
	$("#messageGrid").yxgrid("reload");
};
$(function() {
	var auditState = $("#auditState").val();

	//表头按键处理
	var buttonsArr = [];
	if(auditState == "0"){
		buttonsArr.push({
			icon : 'edit',
			text : '核单',
			action : function(row, rows, rowIds, g) {
				if (row) {
					// 判断主表是否有勾选数据
					var idArr = [];
					for(var i=0;i<rows.length;i++){
						if (rows[i].auditState != '0'){
							alert( "车次/航班号【" + rows[i].flightNumber + "】不能进行核单操作");
							return false;
						}
						//载入可用明细id
						idArr.push(rows[i].id);
					}

					if (confirm("确认进行核单操作？")){
						$.post(
							"?model=flights_message_message&action=confirm",
							{ id: idArr.toString() },
							function (data){
								if (data=='1'){
									alert("核单成功");
									show_page();
								}
							}
						);
					}
				}else{
					alert("请选择一条数据");
				}
			}
		});
	}else if(auditState == "1"){
		buttonsArr.push({
			icon : 'edit',
			text : '反核单',
			action : function(row, rows, rowIds, g) {
				if (row) {
					// 判断主表是否有勾选数据
					var idArr = [];
					for(var i=0;i<rows.length;i++){
						if (rows[i].auditState != '1'){
							alert( "车次/航班号【" + rows[i].flightNumber + "】不能进行反核单操作");
							return false;
						}
						//载入可用明细id
						idArr.push(rows[i].id);
					}

					if (confirm("确认进行反核单操作？")){
						$.post(
							"?model=flights_message_message&action=unconfirm",
							{ id: idArr.toString() },
							function (data){
								if (data=='1'){
									alert("反核单成功");
									show_page();
								}
							}
						);
					}
				}else{
					alert("请选择一条数据");
				}
			}
		});
		buttonsArr.push({
			icon: 'view',
			text: '生成结算单',
			action: function(row, rows, rowIds, g) {
				if (row) {
					var idArr = [];
					for(var i=0;i<rows.length;i++){
						if (rows[i].auditState!='1'){
							alert( "车次/航班号【" + rows[i].flightNumber + "】不能进行生成结算单操作");
							return false;
						}
						//载入可用明细id
						idArr.push(rows[i].id);
					}
					showModalWin('?model=flights_balance_balance&action=toAddBatch&msgId=' + idArr.toString(),1,'batch');
				}else{
					alert("请选择一条数据");
				}
			}
		});
	}

	$("#messageGrid").yxgrid( {
		model : 'flights_message_message',
		title : '订票信息',
		param : {'auditState' : auditState},
		// 列信息
		isOpButton : false,
		isDelAction : false,
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'msgType',
			display : '结算类型',
			width : 50,
			sortable : true,
			process : function(v, row) {
				switch(v){
					case "0" : return "<span style='color: green;'>正常</span>";break;
					case "1" : return "<span style='color: red;'>改签</span>";break;
					case "2" : return "<span style='color: gray;'>退票</span>";break;
					default : return "<span style='color: green;'>正常</span>";
				}
			},
			hide : true
		}, {
			name : 'businessState',
			display : '业务状态',
			width : 50,
			sortable : true,
			process : function(v, row) {
				switch(v){
					case "0" : return "<span style='color: green;'>正常</span>";break;
					case "1" : return "<span style='color: red;'>已改签</span>";break;
					case "2" : return "<span style='color: gray;'>已退票</span>";break;
					case "3" : return "<span style='color: red;'>改签</span>";break;
					case "4" : return "<span style='color: gray;'>退票</span>";break;
					default : return "<span style='color: green;'>正常</span>";
				}
			}
		}, {
			name : 'auditState',
			display : '核单状态',
			sortable : true,
			width : 50,
			process : function(v, row) {
				if (v == "0" || v == "") {
					return "未核对";
				} else if(v == '1') {
					return "已核对";
				} else if(v == '2'){
					return "已结算";
				} else if(v == '3'){
					return "<span style='color: gray;'>不需结算</span>";
				}
			},
			hide : true
		}, {
			name : 'auditDate',
			display : '核算日期',
			sortable : true,
			width : 85
		}, {
			name : 'airName',
			display : '乘机人',
			sortable : true,
			width : 85
		}, {
			name : 'airline',
			display : '航空公司',
			sortable : true
		}, {
			name : 'flightNumber',
			display : '车次/航班号',
			sortable : true
		},{
			name : 'flightTime',
			display : '乘机时间',
			sortable : true,
			width : 130
		}, {
			name : 'arrivalTime',
			display : '到达时间',
			sortable : true,
			width : 130
		}, {
			name : 'departPlace',
			display : '出发地点',
			sortable : true,
			width : 75
		}, {
			name : 'arrivalPlace',
			display : '到达地点',
			sortable : true,
			width : 75
		}, {
			name: 'ticketType',
			display: '机票类型',
			sortable: true,
			hide : true,
			process: function(v) {
				if (v == "10") {
					return '单程';
				} else if (v == "11") {
					return '往返';
				} else if (v == "12") {
					return '联程';
				}
			},
			width : 80
		},
		{
			name: 'startPlace',
			display: '出发城市',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'middlePlace',
			display: '中转城市',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'endPlace',
			display: '到达城市',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'startDate',
			display: '出发日期',
			sortable: true,
			width : 80,
			hide : true
		},
		{
			name: 'twoDate',
			display: '第二天中转日期',
			sortable: true,
			width : 85,
			hide : true
		},
		{
			name: 'comeDate',
			display: '返回时间',
			sortable: true,
			width : 80,
			hide : true
		}, {
			name : 'costPay',
			display : '实际需支付',
			sortable : true,
			width : 70,
			process : function(v){
				if(v*1 >= 0){
					return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
				}else{
					return "<span class='red'>"+ moneyFormat2(v) + "</span>";
				}
			}
		}, {
			name : 'actualCost',
			display : '实际订票额',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'beforeCost',
			display : '原订票额',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'costDiff',
			display : '票价差异',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'fullFare',
			display : '票面价格',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'constructionCost',
			display : '机场建设费',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'fuelCcharge',
			display : '燃油附加费',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'serviceCharge',
			display : '服务费',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'feeChange',
			display : '改签手续费',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'feeBack',
			display : '退票手续费',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='blue'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'costBack',
			display : '退票金额',
			sortable : true,
			width : 70,
			process : function(v){
				return "<span class='red'>"+ moneyFormat2(v) + "</span>";
			}
		}, {
			name : 'requireNo',
			display : '订票需求号',
			sortable : true,
			width : 120
		}, {
			name : 'requireId',
			display : '订票需求号ID',
			sortable : true,
			hide : true
		} , {
			name : 'createName',
			display : '录入人',
			sortable : true,
			width : 80
		}, {
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			width : 120
		}, {
			name : 'isLow',
			display : '当天最低价',
			sortable : true,
			process : function(v, row) {
				if (v == "1") {
					return "是";
				} else {
					return "<span class='red'>"+ "否" + "</span>";
				}
			},
			width : 60
		}],
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("index1.php?model=flights_message_message&action=toAdd");
			}
		},
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if(row.auditState == '0' && row.businessState == "0" ){
					return true;
				}
				return false;
			},
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500
		},
		buttonsEx : buttonsArr,
		menusEx:[{// 在右键栏目添加一个操作图标
			icon:'edit',
			text:'改签机票',
			showMenuFn : function(row) {
				if(row.businessState == "0" || row.businessState == "3"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toChange&id='+rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850');
			}
		},
		{// 在右键栏目添加一个操作图标
			icon:'edit',
			text:'修改改签',
			showMenuFn : function(row) {
				if(row.businessState == "3" && row.auditState == "0"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toEditChange&id='+ rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850');
			}
		},
		{// 在右键栏目添加一个操作图标
			icon:'delete',
			text:'退还机票',
			showMenuFn : function(row) {
				if(row.businessState == "0" || row.businessState == "3"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toBack&id='+ rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=850');
			}
		},
		{// 在右键栏目添加一个操作图标
			icon:'edit',
			text:'修改退票',
			showMenuFn : function(row) {
				if(row.businessState == "4" && row.auditState == "0"){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				showThickboxWin('?model=flights_message_message&action=toEditBack&id='+ rowData.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=850');
			}
		},
		{// 在右键栏目添加一个操作图标
			icon:'delete',
			text:'删除',
			showMenuFn : function(row) {
				if(row.auditState == '0' && row.businessState == "0" ){
					return true;
				}
				return false;
			},
			action: function(rowData) {
				if(confirm('确认删除？')){
					$.post('?model=flights_message_message&action=ajaxdeletes',{id:rowData.id},function (data){
						if (data=='1'){
							alert('删除成功');
							show_page();
						}
					});
				}
			}
		}],
		comboEx : [ {
//			text : '核单状态',
//			key : 'auditState',
//			data : [ {
//				text : '未核对',
//				value : '0'
//			}, {
//				text : '已核对',
//				value : '1'
//			}, {
//				text : '已结算',
//				value : '2'
//			} ]
//		},{
			text : '业务状态',
			key : 'businessState',
			data : [ {
				text : '正常',
				value : '0'
			}, {
				text : '改签',
				value : '1'
			}, {
				text : '退票',
				value : '2'
			} ]
		}],
		searchitems : [{
			display : "乘机人",
			name : 'airNameSearch'
		},{
			display : "航班号",
			name : 'flightNumberSearch'
		},{
			display : "录入人",
			name : 'createNameSearch'
		}],
		sortname : "c.createTime"
	});
});