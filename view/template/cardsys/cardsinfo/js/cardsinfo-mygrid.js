var show_page = function(page) {
	$("#cardsinfoGrid").yxgrid("reload");
};
$(function() {
	$("#cardsinfoGrid").yxgrid({
		model : 'cardsys_cardsinfo_cardsinfo',
		action : 'myPageJson',
		title : '测试卡信息',
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'cardNo',
				display : '卡号',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=cardsys_cardsinfo_cardsinfo&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'pinNo',
				display : '密码',
				width : 80
			}, {
				name : 'openerId',
				display : '开卡员工Id',
				sortable : true,
				hide : true
			}, {
				name : 'openerName',
				display : '开卡员工',
				sortable : true
			}, {
				name : 'idCardNo',
				display : '身份证',
				sortable : true,
				width : 150
			}, {
				name : 'cardTypeName',
				display : '类型',
				sortable : true,
				width : 70
			}, {
				name : 'location',
				display : '归属地',
				sortable : true,
				width : 70
			}, {
				name : 'packageType',
				display : '套餐',
				sortable : true
			}, {
				name : 'operators',
				display : '运营商',
				sortable : true,
				hide : true
			}, {
				name : 'netType',
				display : '网络类型',
				sortable : true,
				datacode : 'WLLX',
				hide : true
			}, {
				name : 'ratesOf',
				display : '资费描述',
				sortable : true,
				width : 150
			}, {
				name : 'openDate',
				display : '开卡日期',
				sortable : true,
				width : 80
			}, {
				name : 'closeDate',
				display : '销卡日期',
				sortable : true,
				width : 80
			}, {
				name : 'allMoney',
				display : '累计消费',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				name : 'cityName',
				display : '归属地(市)',
				width : 80,
				hide : true
			}, {
				name : 'cardType',
				display : '卡型',
				width : 80,
				hide : true
			}, {
				name : 'status',
				display : '状态',
				width : 80,
				datacode : 'CSKZT',
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 180,
				hide : true
			}, {
				name : 'ownerId',
				display : '持卡员工Id',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : '持卡人',
				sortable : true,
				hide : true
			}, {
				name : 'openMoney',
				display : '开卡金额',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'initMoney',
				display : '初始金额',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}
		],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		menusEx : [{
				text : '删除',
				icon : 'delete',
				action : function(rowData, rows, rowIds, g) {
					if (rowData.allMoney*1 == 0) {
						if(confirm('确定要删除测试卡吗？')){
							$.ajax({
								type : "POST",
								url : "?model=cardsys_cardsinfo_cardsinfo&action=ajaxdeletes",
								data : {
									id : rowData.id
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
					} else {
						alert("卡号已经被使用，不能删除！");
					}
				}
			}
		],
		isDelAction : false,
		searchitems : [{
				display : "开卡人",
				name : 'openerNameSearch'
			}, {
				display : "卡号",
				name : 'cardNoSearch'
			}
		]
	});
});