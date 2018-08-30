var show_page = function(page) {
    $("#payablesapplyGrid").yxgrid("reload");
};

$(function() {
	var thisId = $("#thisId").val();
	var skey = "&skey=" + $("#skey").val();

    $("#payablesapplyGrid").yxgrid({
        model: 'finance_payablesapply_payablesapply',
        action : 'historyJson',
        param : {"dObjId" : $("#objId").val(),"dObjType" : $("#objType").val()},
        title: '付款申请历史 -- 采购订单 :' + $("#objCode").val(),
        isAddAction : false,
        isEditAction : false,
        isDelAction :false,
        isViewAction : false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            width : 50,
            process : function(v){
				if(v != 'noId' && v != 'noId2'){
					return v;
				}
            }
        },{
            name: 'formNo',
            display: '申请单编号',
            sortable: true,
            width : 150,
            process : function(v,row){
				if(thisId != row.id){
					return v;
				}else{
					return "<span style='color: blue' title='当前申请单'>" + v + "</span>" ;
				}
            }
        },{
            name: 'formDate',
            display: '单据日期',
            sortable: true,
            hide: true,
            width : 80
        },{
            name: 'payDate',
            display: '期望付款日期',
            sortable: true,
            width : 80
        },{
            name: 'actPayDate',
            display: '实际单据日期',
            sortable: true,
            width : 80
        },{
            name: 'payFor',
            display: '申请类型',
            sortable: true,
            datacode : 'FKLX',
            width : 70
        },{
            name: 'supplierName',
            display: '供应商名称',
            sortable: true,
            width : 160
        },{
            name: 'payMoney',
            display: '申请金额',
            sortable: true,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            width : 80
        },{
            name: 'payedMoney',
            display: '已付金额',
            sortable: true,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            width : 80
        },
        {
            name: 'money',
            display: '结算金额',
            sortable: true,
            process : function(v){
            	if(v >= 0){
					return moneyFormat2(v);
            	}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
            	}
            },
            hide :true,
            width : 80
        },
        {
            name: 'status',
            display: '状态',
            sortable: true,
            datacode: 'FKSQD',
            width : 80
        },
        {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width : 80
        },
        {
            name: 'ExaDT',
            display: '审批时间',
            sortable: true,
            width : 80
        },
        {
            name: 'deptName',
            display: '申请部门',
            sortable: true,
            hide : true
        },
        {
            name: 'salesman',
            display: '申请人',
            sortable: true,
            width : 80
        },
        {
            name: 'createName',
            display: '创建人',
            sortable: true,
            hide : true
        },
        {
            name: 'createTime',
            display: '创建日期',
            sortable: true,
            width : 120,
            hide : true
        }],
        buttonsEx : [
        	{
				text : '付款申请历史',
				icon : 'edit',
				action : function(row) {
					location="?model=finance_payablesapply_payablesapply&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + skey ;
				}
			},{
				text : '付款记录历史',
				icon : 'view',
				action : function(row) {
					location="?model=finance_payables_payables&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + skey ;
				}
			}
			,{
				text : '采购发票记录',
				icon : 'view',
				action : function(row) {
					location="?model=finance_invpurchase_invpurchase&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + skey ;
				}
			}
			,{
				text : '收料记录',
				icon : 'view',
				action : function(row) {
					location="?model=purchase_arrival_arrival&action=toListByOrder"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + skey ;
				}
			}
        ],
        menusEx : [
        	{
				text : '查看付款申请',
				icon : 'view',
				showMenuFn : function(row) {
					if(row.id == 'noId' || row.id == 'noId2'){
						return false;
					}
				},
				action : function(row) {
					showModalWin('?model=finance_payablesapply_payablesapply&action=init&perm=view&id='
							+ row.id
							+ '&skey=' + row['skey_'] ,1);
				}
			},{
				text : '提交财务支付',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == 'FKSQD-00' && row.ExaStatus == '完成' && row.createId == $("#userId").val()) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(row.payDate ==""){
						showThickboxWin('?model=finance_payablesapply_payablesapply&action=toComfirm&id='
								+ row.id
								+ '&supplierName=' + row['supplierName']
								+'&payMoney=' + row['payMoney']
								+ '&skey=' + row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=800");
//						if(confirm('确认提交支付吗？')){
//							$.ajax({
//								type : "POST",
//								url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
//								data : {
//									id : row.id
//								},
//								success : function(msg) {
//									if (msg == 1) {
//										alert('提交成功！');
//										show_page();
//									}else{
//										alert('提交失败！');
//									}
//								}
//							});
//						}
					}else{
						var thisDate = formatDate(new Date());
						var s = DateDiff(thisDate,row.payDate);
						// if(s>0){
						// 	alert('距离期望付款日期还有 '+ s +" 天，暂不能提交财务支付");
						// }else{
							showThickboxWin('?model=finance_payablesapply_payablesapply&action=toComfirm&id='
									+ row.id
									+ '&supplierName=' + row['supplierName']
									+'&payMoney=' + row['payMoney']
									+ '&skey=' + row['skey_']
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=800");
//							if(confirm('确认提交支付吗？')){
//								$.ajax({
//									type : "POST",
//									url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
//									data : {
//										id : row.id
//									},
//									success : function(msg) {
//										if (msg == 1) {
//											alert('提交成功！');
//											show_page();
//										}else{
//											alert('提交失败！');
//										}
//									}
//								});
//							}
// 						}
					}
				}
			}
        ],
        event : {
	        row_check : function(p1,p2,p3,row){
	        	if(row.id != 'noId' && row.id != 'noId2'){
	        		var allData = $("#payablesapplyGrid").yxgrid('getCheckedRows');

        			var payMoneyObj = $("#rownoId2 td[namex='payMoney'] div");
        			var payedMoneyObj = $("#rownoId2 td[namex='payedMoney'] div");

    				var payMoney = 0;
    				var payedMoney = 0;
        			if(allData.length > 0){
						for(var i = 0;i < allData.length ; i++){
							payMoney = accAdd(payMoney,allData[i].payMoney,2);
							payedMoney = accAdd(payedMoney,allData[i].payedMoney,2);
						}
        			}
        			payMoneyObj.text(moneyFormat2(payMoney));
        			payedMoneyObj.text(moneyFormat2(payedMoney));
	        	}
	        }
	    },

        toAddConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
        toViewConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
        toEditConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
		sortname : 'updateTime'
    });
});