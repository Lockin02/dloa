var show_page = function(page) {
	$("#messageGrid").yxgrid("reload");
};
$(function() {
	$("#messageGrid").yxgrid({
		model : 'flights_message_message',
		title : '订票信息',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		showcheckbox : false,
		param : {
			requireId : $("#id").val()
		},
		//列信息
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
		// 扩展右键菜单
		menusEx : [{
            text: '查看',
            icon: 'view',
            action: function(row, rows) {
	            showThickboxWin("?model=flights_message_message&action=toView&id="
	            	+ row.id
	            	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }],
		searchitems : [{
			display : "乘机人",
			name : 'airNameSearch'
		},{
			display : "航班号",
			name : 'flightNumberSearch'
		}]
	});
});