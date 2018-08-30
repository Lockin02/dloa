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
        title: '����������ʷ -- �ɹ����� :' + $("#objCode").val(),
        isAddAction : false,
        isEditAction : false,
        isDelAction :false,
        isViewAction : false,
        //����Ϣ
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
            display: '���뵥���',
            sortable: true,
            width : 150,
            process : function(v,row){
				if(thisId != row.id){
					return v;
				}else{
					return "<span style='color: blue' title='��ǰ���뵥'>" + v + "</span>" ;
				}
            }
        },{
            name: 'formDate',
            display: '��������',
            sortable: true,
            hide: true,
            width : 80
        },{
            name: 'payDate',
            display: '������������',
            sortable: true,
            width : 80
        },{
            name: 'actPayDate',
            display: 'ʵ�ʵ�������',
            sortable: true,
            width : 80
        },{
            name: 'payFor',
            display: '��������',
            sortable: true,
            datacode : 'FKLX',
            width : 70
        },{
            name: 'supplierName',
            display: '��Ӧ������',
            sortable: true,
            width : 160
        },{
            name: 'payMoney',
            display: '������',
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
            display: '�Ѹ����',
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
            display: '������',
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
            display: '״̬',
            sortable: true,
            datacode: 'FKSQD',
            width : 80
        },
        {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width : 80
        },
        {
            name: 'ExaDT',
            display: '����ʱ��',
            sortable: true,
            width : 80
        },
        {
            name: 'deptName',
            display: '���벿��',
            sortable: true,
            hide : true
        },
        {
            name: 'salesman',
            display: '������',
            sortable: true,
            width : 80
        },
        {
            name: 'createName',
            display: '������',
            sortable: true,
            hide : true
        },
        {
            name: 'createTime',
            display: '��������',
            sortable: true,
            width : 120,
            hide : true
        }],
        buttonsEx : [
        	{
				text : '����������ʷ',
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
				text : '�����¼��ʷ',
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
				text : '�ɹ���Ʊ��¼',
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
				text : '���ϼ�¼',
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
				text : '�鿴��������',
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
				text : '�ύ����֧��',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == 'FKSQD-00' && row.ExaStatus == '���' && row.createId == $("#userId").val()) {
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
//						if(confirm('ȷ���ύ֧����')){
//							$.ajax({
//								type : "POST",
//								url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
//								data : {
//									id : row.id
//								},
//								success : function(msg) {
//									if (msg == 1) {
//										alert('�ύ�ɹ���');
//										show_page();
//									}else{
//										alert('�ύʧ�ܣ�');
//									}
//								}
//							});
//						}
					}else{
						var thisDate = formatDate(new Date());
						var s = DateDiff(thisDate,row.payDate);
						// if(s>0){
						// 	alert('���������������ڻ��� '+ s +" �죬�ݲ����ύ����֧��");
						// }else{
							showThickboxWin('?model=finance_payablesapply_payablesapply&action=toComfirm&id='
									+ row.id
									+ '&supplierName=' + row['supplierName']
									+'&payMoney=' + row['payMoney']
									+ '&skey=' + row['skey_']
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=800");
//							if(confirm('ȷ���ύ֧����')){
//								$.ajax({
//									type : "POST",
//									url : "?model=finance_payablesapply_payablesapply&action=handUpPay",
//									data : {
//										id : row.id
//									},
//									success : function(msg) {
//										if (msg == 1) {
//											alert('�ύ�ɹ���');
//											show_page();
//										}else{
//											alert('�ύʧ�ܣ�');
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