var show_page = function(page) {
    $("#payablesGrid").yxgrid("reload");
};

$(function() {
	var skey = "&skey=" + $("#skey").val();

    $("#payablesGrid").yxgrid({
        model: 'finance_payables_payables',
        action : 'historyJson',
        param : {"dObjId" : $("#objId").val(),"dObjType" : $("#objType").val()},
        title: '付款记录历史',
        isAddAction : false,
        isEditAction : false,
        isDelAction :false,
        isViewAction : false,
        showcheckbox :true,
        //列信息
        colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true,
			process : function(v){
				if(v != 'noId' && v != 'noId2'){
					return v;
				}
            }
		}, {
			display : '单据编号',
			name : 'formNo',
			sortable : true,
			width : 150
		}, {
			display : '单据日期',
			name : 'formDate',
			width : 80
		}, {
			display : '单据类型',
			name : 'formType',
			sortable : true,
			datacode : 'CWYF',
			width : 80
		}, {
			display : '供应商名称',
			name : 'supplierName',
			width : 160
		}, {
			display : '单据金额',
			name : 'amount',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '结算金额',
			name : 'money',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '结算方式',
			name : 'payType',
			datacode : 'CWFKFS',
			width : 80
		}, {
			display : '录入人',
			name : 'createName'
		},{
			display : '状态',
			name : 'status',
			datacode : 'YFDZT',
			width : 90,
			hide : true
		}, {
			display : '关联付款申请号',
			name : 'payApplyNo',
			width : 150
		}, {
			display : '归属公司',
			name : 'businessBelongName',
			width : 80
		}],
        menusEx : [
        	{
				text : '查看付款记录',
				icon : 'view',
				action : function(row) {
					showThickboxWin('?model=finance_payables_payables&action=init&perm=view&id='
							+ row.id + "&skey=" + row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
        ],
        event : {
	        row_check : function(p1,p2,p3,row){
	        	if(row.id != 'noId' && row.id != 'noId2'){
	        		var allData = $("#payablesGrid").yxgrid('getCheckedRows');
	        		var amountObj = $("#rownoId2 td[namex='amount'] div");
        			var moneyObj = $("#rownoId2 td[namex='money'] div");
        			var amount = 0;
    				var money = 0;
        			if(allData.length > 0){
						for(var i = 0;i < allData.length ; i++){
							amount = accAdd(amount,allData[i].amount,2);
							money = accAdd(money,allData[i].money,2);
						}
        			}
        			amountObj.text(moneyFormat2(amount));
        			moneyObj.text(moneyFormat2(money));
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