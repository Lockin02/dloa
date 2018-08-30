/**到款列表**/

var show_page=function(page){
   $("#contractGrid").yxgrid("reload");
};

$(function(){
	//模式匹配
	if($("#modeType").val() == "1"){
		var showCheckBox = false;
	}else{
		var showCheckBox = true;
	}

    $("#contractGrid").yxgrid({
    	model:'contract_contract_receiptplan',
    	action : 'selectPageJson',
    	param : {"contractIdArr" : $("#contractId").val() ,"isDel" : 0 ,"isTemp" : 0},
    	title:'合同付款条件',
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
								alert("此数据不允许选择。");
								$("#chk_"+rid).attr('checked',false);
								event.preventDefault();
								event.stopPropagation();
							});
						}else if(Number(nIncomMoney) <=  0){
							$("#row"+row.id).click(function(){
								alert("未到款金额为0的不允许选择。");
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
				display : '合同编号',
				name : 'contractCode'
			},{
				display : '付款条件',
				name : 'paymentterm'
			},{
				display : '付款百分比',
				name : 'paymentPer',
				process : function(v){
					return v + " %";
				},
				width : 80
			},{
				display : '付款金额',
				name : 'money',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : '预计付款日期',
				name : 'payDT',
				width : 80
			},{
				display : '财务确认T日',
				name : 'Tday',
				width : 80
			},{
				display : '已开票金额',
				name : 'invoiceMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : '已到款金额',
				name : 'incomMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : '未到款金额',
				name : 'unIncomMoney',
				align: 'right',
				width : 80,
				process : function(v,row){
					var tempMoney = row.money - row.deductMoney - row.incomMoney;
					return moneyFormat2(tempMoney);
				}
			},{
				display : '扣款金额',
				name : 'deductMoney',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				display : '是否完成',
				name : 'isCom',
				process : function(v,row){
					switch(v){
						case '0' : return '未完成';break;
						case '1' : return '已完成';break;
						default : return '未完成';
					}
				},
				width : 70
			},{
				display : '是否财务',
				name : 'isfinance',
				width : 70
			}
		],
        buttonsEx : [{
			name : 'Add',
			text : "确认选择",
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
					//关闭窗口
					window.close();
				}else{
					alert('请选择一行数据');
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
		//过滤数据
		comboEx : [{
			text : '完成状态',
			key : 'isCom',
			value : '0',
			data : [{
				text : '已完成',
				value : '1'
			}, {
				text : '未完成',
				value : '0'
			}]
		}],
		//快速搜索
		searchitems : [{
			display : "合同编号",
			name : 'conContractCodeSearch'
		}, {
			display : "合同名称",
			name : 'conContractNameSearch'
		}],
		sortorder:'ASC'
    });
    $(".sDiv2").append("<span class='blue'>&nbsp&nbsp&nbsp**蓝色字体为不可选择的数据**</span>");
});

