/**到款列表**/

var show_page=function(page){
   $("#invoiceGrid").yxsubgrid("reload");
};

$(function(){
        $("#invoiceGrid").yxsubgrid({
        	model:'finance_invoice_invoice',
        	title:'发票归类',
        	isAddAction:false,
        	isEditAction : false,
        	isDelAction : false,

			colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : '申请单号',
					name : 'applyNo',
					hide : true
				}, {
					display : '发票号',
					name : 'invoiceNo',
					sortable : true
				}, {
					display : '单据编号',
					name : 'invoiceCode',
					sortable : true,
					width : 140,
					process : function(v,row){
						if(row.isRed == 0){
							return v;
						}else{
							return "<span class='red'>" + v + "</span>";
						}
					},
					hide : true
				},
				{
					display : '处理状态',
					name : 'status',
					width:70,
					process : function(v){
						if(v == 1){
							return '已处理';
						}else{
							return '未处理';
						}
					}
				},
				{
					display : '开票类型',
					name : 'invoiceType',
					datacode:'FPLX',
					hide : true,
					width:80
				},{
					display : '关联编号',
					name : 'objCode',
					width:125,
					hide : true
				},{
					display : '关联类型',
					name : 'objType',
					datacode : 'KPRK',
					width:70,
					hide : true
				},
				{
					display : '开票单位',
					name :'invoiceUnitName',
					width:150
				},
				{
					display : '开票日期',
					name : 'invoiceTime',
					sortable:true,
					width:80
				},
				{
					display : '总金额',
					name : 'invoiceMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							return '-' + moneyFormat2(v);
						}
					},
					width:80
				},
				{
					display : '软件金额',
					name : 'softMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							return '-' + moneyFormat2(v);
						}
					},
					width:80
				},
				{
					display : '硬件金额',
					name : 'hardMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							return '-' + moneyFormat2(v);
						}
					},
					width:80
				},
				{
					display : '维修金额',
					name : 'repairMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							return '-' + moneyFormat2(v);
						}
					},
					width:80
				},
				{
					display : '服务金额',
					name : 'serviceMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							return '-' + moneyFormat2(v);
						}
					},
					width:80
				},{
					display : '设备租赁金额',
					name : 'equRentalMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							if(v*1 != 0){
								return '<span class="red">-' + moneyFormat2(v) + "</span>";
							}else{
								return moneyFormat2(v);
							}
						}
					},
					width:80
				},{
					display : '场地租赁金额',
					name : 'spaceRentalMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							if(v*1 != 0){
								return '<span class="red">-' + moneyFormat2(v) + "</span>";
							}else{
								return moneyFormat2(v);
							}
						}
					},
					width:80
				},
				{
					display : '业务员',
					name : 'salesman'
				},
				{
					display : '开票人',
					name : 'createName',
					process : function(v,row){
						return v + "<input type='hidden' id='hasRed"+ row.id +"' value='unde'/>";
					}
				},
				{
					display : '是否红字',
					name : 'isRed',
					width:80,
					hide : true,
					process : function(v){
						if(v == 1){
							return '是';
						}else{
							return '否';
						}
					}
				}
			],
			// 主从表格设置
			subGridOptions : {
				url : '?model=finance_invoice_invoiceDetail&action=pageJson',// 获取从表数据url
				// 传递到后台的参数设置数组
				param : [
					{
						paramId : 'invoiceId',// 传递给后台的参数名称
						colId : 'id'// 获取主表行数据的列名称
					}
				],
				// 显示的列
				colModel : [{
						name : 'productName',
						display : '物料名称',
						width : 140
					},{
						name : 'productModel',
						display : '产品类型',
						width : 120,
						datacode : 'CWCPLX'
					}, {
					    name : 'amount',
					    display : '数量',
					    width : 70
					},{
						name : 'softMoney',
						display : '软件金额',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
						name : 'hardMoney',
						display : '硬件金额',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
					    name : 'repairMoney',
					    display : '维修金额',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
					    name : 'serviceMoney',
					    display : '服务金额',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
					    name : 'psType',
					    display : '产品/服务类型',
					    datacode : 'CPFWLX'
					}
				]
			},
			toViewConfig : {
				formWidth : 900,
				formHeight : 500
			},
	        buttonsEx : [{
				name : 'edit',
				text : "批量处理",
				icon : 'edit',
				action : function(row,rows,idArr) {
					if(row){
						idStr = idArr.toString();
						showThickboxWin("?model=finance_invoice_invoice"
							+ "&action=batchDeal"
							+ "&ids="
							+ idStr
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
							+ "&width=900");
					}else{
						alert('请先选择记录');
					}
				}
			}],
	        comboEx : [
        		{
					text : '处理状态',
					key : 'status',
					value : '0',
					data : [{
						text : '未处理',
						value : 0
					},{
						text : '已处理',
						value : 1
					}]
	        	}
	        ],
			searchitems:[
		        {
		            display:'发票号',
		            name:'invoiceNo'
		        },
		        {
		            display:'关联编号',
		            name:'objCodeSearch'
		        },
		        {
		            display:'开票单位',
		            name:'invoiceUnitNameSearch'
		        },
		        {
		            display:'业务员',
		            name:'salesman'
		        }
	        ],
	        sortname : 'invoiceTime',
			sortorder:'DESC'
        });
});