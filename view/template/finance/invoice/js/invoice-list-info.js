var show_page=function(page){
   $("#invoiceGrid").yxgrid("reload");
};

$(function(){
	// 初始化表头按钮数组
	buttonsArr = [
        {
			name : 'listInfo',
			text : "打开列表",
			icon : 'view',
			action : function() {
				showModalWin("?model=finance_invoice_invoice&action=toListInfo");
			}
        },{
			name : 'view',
			text : "高级查询",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=finance_invoice_invoice&action=toSearchInfoList&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        },{
			name : 'close',
			text : "开票额预览",
			icon : 'view',
			action : function() {
				showOpenWin('?model=finance_invoice_invoice&action=toInvoicePerview');
			}
        }
    ];

    excelOutArr = {
		name : 'excOut',
		text : "导出开票",
		icon : 'excel',
		items : [{
			text : 'EXCEL2003',
			icon : 'excel',
			action : function() {
				$thisGrid = $("#invoiceGrid").data('yxgrid');
				if(confirm('导出时是否要对开票明细进行合并？')){

					url = "?model=finance_invoice_invoice&action=toInvoiceExcOut&excelType=05"
							+ '&invoice[beginYear]=' + filterUndefined( $thisGrid.options.extParam.beginYear )
							+ '&invoice[beginMonth]=' + filterUndefined( $thisGrid.options.extParam.beginMonth )
							+ '&invoice[endYear]=' + filterUndefined( $thisGrid.options.extParam.endYear )
							+ '&invoice[endMonth]=' + filterUndefined( $thisGrid.options.extParam.endMonth )

							+ '&invoice[invoiceUnitProvince]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitProvince )
							+ '&invoice[areaName]=' + filterUndefined( $thisGrid.options.extParam.areaName )
							+ '&invoice[invoiceUnitId]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitId )
							+ '&invoice[invoiceUnitType]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitType )
							+ '&invoice[invoiceNo]=' + filterUndefined( $thisGrid.options.extParam.invoiceNo )
							+ '&invoice[salesmanId]=' + filterUndefined( $thisGrid.options.extParam.salesmanId )
							+ '&invoice[objCodeSearch]=' + filterUndefined( $thisGrid.options.extParam.objCodeSearch )
							+ '&invoice[signSubjectName]=' + filterUndefined( $thisGrid.options.extParam.signSubjectName )
							;
					window.open(url,"", "width=200,height=200,top=200,left=200");
				}else{
					url = "?model=finance_invoice_invoice&action=toInvoiceExcOutNotMerge&excelType=05"
							+ '&invoice[beginYear]=' + filterUndefined( $thisGrid.options.extParam.beginYear )
							+ '&invoice[beginMonth]=' + filterUndefined( $thisGrid.options.extParam.beginMonth )
							+ '&invoice[endYear]=' + filterUndefined( $thisGrid.options.extParam.endYear )
							+ '&invoice[endMonth]=' + filterUndefined( $thisGrid.options.extParam.endMonth )

							+ '&invoice[invoiceUnitProvince]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitProvince )
							+ '&invoice[areaName]=' + filterUndefined( $thisGrid.options.extParam.areaName )
							+ '&invoice[invoiceUnitId]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitId )
							+ '&invoice[invoiceUnitType]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitType )
							+ '&invoice[invoiceNo]=' + filterUndefined( $thisGrid.options.extParam.invoiceNo )
							+ '&invoice[salesmanId]=' + filterUndefined( $thisGrid.options.extParam.salesmanId )
							+ '&invoice[objCodeSearch]=' + filterUndefined( $thisGrid.options.extParam.objCodeSearch )
							+ '&invoice[signSubjectName]=' + filterUndefined( $thisGrid.options.extParam.signSubjectName )
							;
					window.open(url,"", "width=200,height=200,top=200,left=200");
				}
			}
		},{
			text : 'EXCEL2007',
			icon : 'excel',
			action : function() {
				$thisGrid = $("#invoiceGrid").data('yxgrid');
				if(confirm('导出时是否要对开票明细进行合并？')){

					url = "?model=finance_invoice_invoice&action=toInvoiceExcOut"
							+ '&invoice[beginYear]=' + filterUndefined( $thisGrid.options.extParam.beginYear )
							+ '&invoice[beginMonth]=' + filterUndefined( $thisGrid.options.extParam.beginMonth )
							+ '&invoice[endYear]=' + filterUndefined( $thisGrid.options.extParam.endYear )
							+ '&invoice[endMonth]=' + filterUndefined( $thisGrid.options.extParam.endMonth )

							+ '&invoice[invoiceUnitProvince]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitProvince )
							+ '&invoice[areaName]=' + filterUndefined( $thisGrid.options.extParam.areaName )
							+ '&invoice[invoiceUnitId]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitId )
							+ '&invoice[invoiceUnitType]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitType )
							+ '&invoice[invoiceNo]=' + filterUndefined( $thisGrid.options.extParam.invoiceNo )
							+ '&invoice[salesmanId]=' + filterUndefined( $thisGrid.options.extParam.salesmanId )
							+ '&invoice[objCodeSearch]=' + filterUndefined( $thisGrid.options.extParam.objCodeSearch )
							+ '&invoice[signSubjectName]=' + filterUndefined( $thisGrid.options.extParam.signSubjectName )
							;
					window.open(url,"", "width=200,height=200,top=200,left=200");
				}else{
					url = "?model=finance_invoice_invoice&action=toInvoiceExcOutNotMerge"
							+ '&invoice[beginYear]=' + filterUndefined( $thisGrid.options.extParam.beginYear )
							+ '&invoice[beginMonth]=' + filterUndefined( $thisGrid.options.extParam.beginMonth )
							+ '&invoice[endYear]=' + filterUndefined( $thisGrid.options.extParam.endYear )
							+ '&invoice[endMonth]=' + filterUndefined( $thisGrid.options.extParam.endMonth )

							+ '&invoice[invoiceUnitProvince]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitProvince )
							+ '&invoice[areaName]=' + filterUndefined( $thisGrid.options.extParam.areaName )
							+ '&invoice[invoiceUnitId]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitId )
							+ '&invoice[invoiceUnitType]=' + filterUndefined( $thisGrid.options.extParam.invoiceUnitType )
							+ '&invoice[invoiceNo]=' + filterUndefined( $thisGrid.options.extParam.invoiceNo )
							+ '&invoice[salesmanId]=' + filterUndefined( $thisGrid.options.extParam.salesmanId )
							+ '&invoice[objCodeSearch]=' + filterUndefined( $thisGrid.options.extParam.objCodeSearch )
							+ '&invoice[signSubjectName]=' + filterUndefined( $thisGrid.options.extParam.signSubjectName )
							;
					window.open(url,"", "width=200,height=200,top=200,left=200");
				}
			}
		}]
    };

    $.ajax({
		type : 'POST',
		url : '?model=finance_invoice_invoice&action=getLimits',
		data : {
			'limitName' : '发票导出'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});
    $("#invoiceGrid").yxgrid({
    	model:'finance_invoice_invoice',
    	action:'pageJsonInfoList',
    	title:'开票汇总表',
    	isToolBar:true,
    	isAddAction:false,
    	isDelAction :false,
    	isEditAction : false,
    	isViewAction:false,
    	showcheckbox:false,

		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '地区',
				name : 'thisAreaName',
				process : function(v,row){
					if(v == ""){
						if( row.id == "noId" ){
							return '所有合计';
						}else if(row.id == "noId2"){
							return '单页小计';
						}
					}else
						return v;
				},
				width : 80
			},{
				display : '负责人',
				name : 'prinvipalName',
				sortable:true,
				width : 80
			},{
				display : '主管',
				name : 'areaPrincipal',
				width : 80
			},{
				display : '开票日期',
				name : 'invoiceTime',
				sortable:true,
				width : 80
			},{
				display : '归属公司',
				name :'businessBelongName',
				width:100
			},{
				display : '公司',
				name :'invoiceUnitName',
				width:130
			},{
				display : '公司id',
				name :'invoiceUnitId',
				hide : true
			},{
				display : '省份',
				name : 'invoiceUnitProvince',
				width : 60
			},{
				display : '销售区域',
				name : 'areaName',
				width : 60
			},{
				display : '客户类型',
				name : 'invoiceUnitTypeName'
			},{
				display : '单据编号',
				name : 'invoiceCode',
				sortable : true,
				width : 140,
				hide : true
			},{
				display : '发票号码',
				name : 'invoiceNo',
				sortable : true,
				process : function(v,row){
					if(row.isRed == 0){
						return "<a href='javascript:void(0);' onclick='showOpenWin(\"?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,600,1100,"+row.id+")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0);' style='color:red' onclick='showOpenWin(\"?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,600,1100,"+row.id+")'>" + v + "</a>";
					}
				}
			},{
				display : '销售员',
				name : 'salesman',
				width : 80,
				hide : true
			},{
				display : '销售员帐号',
				name : 'salesmanId',
				width : 80,
				hide : true
			},{
				display : '合同类型',
				name : 'objTypeCN',
				sortable : true,
				width : 80
			},{
				display : '合同类型',
				name : 'objType',
				sortable : true,
				datacode : 'KPRK',
				width : 80,
				hide : true
			},{
				display : '鼎利合同号',
				name : 'orderCode',
				width:140
			},{
				display : '签约公司',
				name : 'signSubjectName',
				width:80
			},{
				display : '业务编号',
				name : 'rObjCode',
				width:120,
				hide : true
			},{
				display : '发票类型',
				name : 'invoiceTypeName',
				width : 80
			},
			{
				display : '数量',
				name : 'allAmount',
				width:50
			},
			{
				display : '合同属性',
				name : 'contractNatureName',
				width:80
			},
			{
				display : '项目',
				name : 'productName',
				width:120
			},
			{
				display : '产品/服务类型',
				name : 'psType',
				width:120
			},
			{
				display : '软件金额',
				name : 'softMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '硬件金额',
				name : 'hardMoney',
				process : function(v,row){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '维修金额',
				name : 'repairMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '服务金额',
				name : 'serviceMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '设备租赁金额',
				name : 'equRentalMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '场地租赁金额',
				name : 'spaceRentalMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '其他金额',
				name : 'otherMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '代收电费总金额',
				name : 'dsEnergyCharge',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '代收水费总金额',
				name : 'dsWaterRateMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '房屋出租总金额',
				name : 'houseRentalFee',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '安装服务总金额',
				name : 'installationCost',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '合计',
				name : 'invoiceMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '备注',
				name : 'remark',
				width:120
			},
			{
				display : '创建时间',
				name : 'createTime',
				width:120,
				hide :true
			},
			{
				display : '租赁开始日期',
				name : 'rentBeginDate',
				width:80
			},
			{
				display : '租赁结束日期',
				name : 'rentEndDate',
				width:80
			},
			{
				display : '租赁天数',
				name : 'rentDays',
				width:60
			}
		],
		subGridOptions : {
			model:'finance_invoice_invoice',
    		action:'pageJsonInfoList',
			// 显示的列
			colModel : [{
					display : '地区',
					name : 'district'
				},{
					display : '负责人',
					name : 'prinvipalName'
				},{
					display : '主管',
					name : 'leader'
				},{
					display : '开票日期',
					name : 'invoiceTime'
				}
			]
		},
		lockCol:['prinvipalName','thisAreaName','areaPrincipal','invoiceTime'],//锁定的列名
		//扩展右键菜单
		menusEx : [
			{
				text : '查看开票记录',
				icon : 'view',
				showMenuFn : function(row){
					if(row.id == 'noId' || row.id == 'noId2'){
						return false;
					}
					return true;
				},
				action : function(row){
					showOpenWin("?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + "&skey="+ row.skey_ ,1,600,1100,row.id);
				}
			}
		],
        buttonsEx : buttonsArr,
		searchitems:[
	        {
	            display:'合同编号',
	            name:'objCodeSearch'
	        },
	        {
	            display:'发票号码',
	            name:'invoiceNo'
	        },
	        {
	            display:'开票日期',
	            name:'invoiceTime'
	        },
	        {
	            display:'销售员',
	            name:'salesman'
	        },
	        {
	            display:'签约主体',
	            name:'conSignSubjectNameSearch'
	        }
        ],
        sortname : 'c.invoiceTime desc,c.createTime',
        sortorder : 'DESC'
    });
});