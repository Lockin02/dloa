/**�����б�**/

var show_page=function(page){
   $("#contractGrid").yxgrid("reload");
};

$(function(){
	//ģʽƥ��
	if($("#modeType").val() == "1"){
		var showCheckBox = false;
	}else{
		var showCheckBox = true;
	}

    $("#contractGrid").yxgrid({
    	model:'contract_contract_receiptplan',
    	action : 'selectPageJson',
    	param : {"contractIdArr" : $("#contractId").val() ,"isDel" : 0 ,"isTemp" : 0},
    	title:'��ͬ��������',
    	isToolBar:true,
    	isAddAction:false,
    	isViewAction : false,
    	isEditAction : false,
    	isDelAction : false,
		isOpButton : false,
		showcheckbox : showCheckBox,
		event : {
			"afterloaddata" : function(e, data){
				$.each($("#contractGrid_hTable .cth"),function(i,item){
					if($(item).attr('isch') == 'true'){
						$(item).children('div').children('input').hide();
					}
				});
                if (data) {
                	$.each(data.collection,function(i,item){
                		var row = data.collection[i];
						var nIncomMoney = (row.unIncomMoney_name == undefined)? 0 : row.unIncomMoney_name;
						nIncomMoney = nIncomMoney.replaceAll(",","");
						if(row.isfinance==1){
							var rid = data.collection[i].id;
							$('#row' + data.collection[i].id).css('color', 'blue').click(function(){
								alert("�����ݲ�����ѡ��");
								$("#chk_"+rid).attr('checked',false);
								event.preventDefault();
								event.stopPropagation();
							});
						}else if(Number(nIncomMoney) <=  0){
							$("#row"+row.id).click(function(){
								alert("δ������Ϊ0�Ĳ�����ѡ��");
								$("#chk_"+row.id).attr('checked',false);
								event.preventDefault();
								event.stopPropagation();
							});
						}
					})
				}
			}
		},
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '��ͬ���',
				name : 'contractCode'
			},{
				display : '��������',
				name : 'paymentterm'
			},{
				display : '����ٷֱ�',
				name : 'paymentPer',
				process : function(v){
					return v + " %";
				},
				width : 80
			},{
				display : '������',
				name : 'money',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : 'Ԥ�Ƹ�������',
				name : 'payDT',
				width : 80
			},{
				display : '����ȷ��T��',
				name : 'Tday',
				width : 80
			},{
				display : '�ѿ�Ʊ���',
				name : 'invoiceMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : '�ѵ�����',
				name : 'incomMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : 'δ������',
				name : 'unIncomMoney',
				align: 'right',
				width : 80,
				process : function(v,row){
					var tempMoney = row.money - row.deductMoney - row.incomMoney;
					return moneyFormat2(tempMoney);
				}
			},{
				display : '�ۿ���',
				name : 'deductMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : '�Ƿ����',
				name : 'isCom',
				process : function(v,row){
					switch(v){
						case '0' : return 'δ���';break;
						case '1' : return '�����';break;
						default : return 'δ���';
					}
				},
				width : 70
			},{
				display : '�Ƿ����',
				name : 'isfinance',
				width : 70
			}
		],
        buttonsEx : [{
			name : 'Add',
			text : "ȷ��ѡ��",
			icon : 'add',
			action: function(row,rows,idArr ) {
				if(row){
					if(window.opener){
						if(showCheckBox == true){
							window.opener.setDatas(rows);
						}else{
							window.opener.setDatas(row);
						}
					}
					//�رմ���
					window.close();
				}else{
					alert('��ѡ��һ������');
				}
			}
        }],
		toViewConfig : {
			formWidth : 900,
			formHeight : 500
		},
		toAddConfig : {
			formWidth : 1000,
			formHeight : 600
		},
		//��������
		comboEx : [{
			text : '���״̬',
			key : 'isCom',
			value : '0',
			data : [{
				text : '�����',
				value : '1'
			}, {
				text : 'δ���',
				value : '0'
			}]
		}],
		//��������
		searchitems : [{
			display : "��ͬ���",
			name : 'conContractCodeSearch'
		}, {
			display : "��ͬ����",
			name : 'conContractNameSearch'
		}],
		sortorder:'ASC'
    });
    $(".sDiv2").append("<span class='blue'>&nbsp&nbsp&nbsp**��ɫ����Ϊ����ѡ�������**</span>");
});

