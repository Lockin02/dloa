var show_page=function(page){
   $("#invoiceGrid").yxgrid("reload");
};

$(function(){
	// ��ʼ����ͷ��ť����
	buttonsArr = [
        {
			name : 'listInfo',
			text : "���б�",
			icon : 'view',
			action : function() {
				showModalWin("?model=finance_invoice_invoice&action=toListInfo");
			}
        },{
			name : 'view',
			text : "�߼���ѯ",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=finance_invoice_invoice&action=toSearchInfoList&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        },{
			name : 'close',
			text : "��Ʊ��Ԥ��",
			icon : 'view',
			action : function() {
				showOpenWin('?model=finance_invoice_invoice&action=toInvoicePerview');
			}
        }
    ];

    excelOutArr = {
		name : 'excOut',
		text : "������Ʊ",
		icon : 'excel',
		items : [{
			text : 'EXCEL2003',
			icon : 'excel',
			action : function() {
				$thisGrid = $("#invoiceGrid").data('yxgrid');
				if(confirm('����ʱ�Ƿ�Ҫ�Կ�Ʊ��ϸ���кϲ���')){

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
				if(confirm('����ʱ�Ƿ�Ҫ�Կ�Ʊ��ϸ���кϲ���')){

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
			'limitName' : '��Ʊ����'
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
    	title:'��Ʊ���ܱ�',
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
				display : '����',
				name : 'thisAreaName',
				process : function(v,row){
					if(v == ""){
						if( row.id == "noId" ){
							return '���кϼ�';
						}else if(row.id == "noId2"){
							return '��ҳС��';
						}
					}else
						return v;
				},
				width : 80
			},{
				display : '������',
				name : 'prinvipalName',
				sortable:true,
				width : 80
			},{
				display : '����',
				name : 'areaPrincipal',
				width : 80
			},{
				display : '��Ʊ����',
				name : 'invoiceTime',
				sortable:true,
				width : 80
			},{
				display : '������˾',
				name :'businessBelongName',
				width:100
			},{
				display : '��˾',
				name :'invoiceUnitName',
				width:130
			},{
				display : '��˾id',
				name :'invoiceUnitId',
				hide : true
			},{
				display : 'ʡ��',
				name : 'invoiceUnitProvince',
				width : 60
			},{
				display : '��������',
				name : 'areaName',
				width : 60
			},{
				display : '�ͻ�����',
				name : 'invoiceUnitTypeName'
			},{
				display : '���ݱ��',
				name : 'invoiceCode',
				sortable : true,
				width : 140,
				hide : true
			},{
				display : '��Ʊ����',
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
				display : '����Ա',
				name : 'salesman',
				width : 80,
				hide : true
			},{
				display : '����Ա�ʺ�',
				name : 'salesmanId',
				width : 80,
				hide : true
			},{
				display : '��ͬ����',
				name : 'objTypeCN',
				sortable : true,
				width : 80
			},{
				display : '��ͬ����',
				name : 'objType',
				sortable : true,
				datacode : 'KPRK',
				width : 80,
				hide : true
			},{
				display : '������ͬ��',
				name : 'orderCode',
				width:140
			},{
				display : 'ǩԼ��˾',
				name : 'signSubjectName',
				width:80
			},{
				display : 'ҵ����',
				name : 'rObjCode',
				width:120,
				hide : true
			},{
				display : '��Ʊ����',
				name : 'invoiceTypeName',
				width : 80
			},
			{
				display : '����',
				name : 'allAmount',
				width:50
			},
			{
				display : '��ͬ����',
				name : 'contractNatureName',
				width:80
			},
			{
				display : '��Ŀ',
				name : 'productName',
				width:120
			},
			{
				display : '��Ʒ/��������',
				name : 'psType',
				width:120
			},
			{
				display : '������',
				name : 'softMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : 'Ӳ�����',
				name : 'hardMoney',
				process : function(v,row){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : 'ά�޽��',
				name : 'repairMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '������',
				name : 'serviceMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '�豸���޽��',
				name : 'equRentalMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '�������޽��',
				name : 'spaceRentalMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '�������',
				name : 'otherMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '���յ���ܽ��',
				name : 'dsEnergyCharge',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '����ˮ���ܽ��',
				name : 'dsWaterRateMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '���ݳ����ܽ��',
				name : 'houseRentalFee',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},{
				display : '��װ�����ܽ��',
				name : 'installationCost',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '�ϼ�',
				name : 'invoiceMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width:75
			},
			{
				display : '��ע',
				name : 'remark',
				width:120
			},
			{
				display : '����ʱ��',
				name : 'createTime',
				width:120,
				hide :true
			},
			{
				display : '���޿�ʼ����',
				name : 'rentBeginDate',
				width:80
			},
			{
				display : '���޽�������',
				name : 'rentEndDate',
				width:80
			},
			{
				display : '��������',
				name : 'rentDays',
				width:60
			}
		],
		subGridOptions : {
			model:'finance_invoice_invoice',
    		action:'pageJsonInfoList',
			// ��ʾ����
			colModel : [{
					display : '����',
					name : 'district'
				},{
					display : '������',
					name : 'prinvipalName'
				},{
					display : '����',
					name : 'leader'
				},{
					display : '��Ʊ����',
					name : 'invoiceTime'
				}
			]
		},
		lockCol:['prinvipalName','thisAreaName','areaPrincipal','invoiceTime'],//����������
		//��չ�Ҽ��˵�
		menusEx : [
			{
				text : '�鿴��Ʊ��¼',
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
	            display:'��ͬ���',
	            name:'objCodeSearch'
	        },
	        {
	            display:'��Ʊ����',
	            name:'invoiceNo'
	        },
	        {
	            display:'��Ʊ����',
	            name:'invoiceTime'
	        },
	        {
	            display:'����Ա',
	            name:'salesman'
	        },
	        {
	            display:'ǩԼ����',
	            name:'conSignSubjectNameSearch'
	        }
        ],
        sortname : 'c.invoiceTime desc,c.createTime',
        sortorder : 'DESC'
    });
});