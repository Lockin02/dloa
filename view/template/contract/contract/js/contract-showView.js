$(function() {

    if( $("#paperContract").val()=='��'){
        $("#paperContractView").html("*�˺�ͬ��ֽ�ʺ�ͬ");
    }else{
        $("#paperContractView").hide("");
    }
	//�¿�Ʊ���Ϳ���
	var dataCode = $("#dataCode").val();
	var itemArr = dataCode.split(',');
	var itemLength = itemArr.length;
    for(i=0;i<itemLength;i++){
		if($("#"+itemArr[i]+"V").val()==1){
			$("#"+itemArr[i]).before("��");
			$("#"+itemArr[i]+"Hide").show();
		}else{
			$("#"+itemArr[i]).before("��");
			$("#"+itemArr[i]).css("color","#969696");
		}
	}
	//�ж�Ԥ��ë����ɫ
	  var exgross = $("#exgross").html();
	  var exgrossVal = $("#exgrossVal").val();
	  if(exgross < exgrossVal){
	     $("#exgross").attr('style',"color:red");
	  }else{
	     $("#exgross").attr('style',"color:black");
	  }
	/**
	 * ת�ɱ����ɫ����
	 */
	var tranToChangeShow = function(oldVal, newVal) {
		if($.trim(oldVal)==$.trim(newVal))return oldVal;
		var newHtml = "<font color='red'><span class='oldValue' >"
				+ oldVal
				+ "</span><span class='compare'>=></span><span class='newValue'>"
				+ newVal + "</span></font>";
		return newHtml;
	}

	/**
	 * ���ɱ༭�����ϱ����ʶͼ��
	 */
	var addGridChangeShow = function(g, rowNum, colName, rowData,tr) {
		var $cmp = g.getCmpByRowAndCol(rowNum, colName);
		if (rowData.changeTips == '1') {
			// tr.css("background-color", "#F8846B");
			$cmp.prepend('<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {

			$cmp.prepend('<img title="��������Ĳ�Ʒ" src="images/new.gif" />');
		} else if (rowData.changeTips == '3' || rowData.isDel == '1') {
			$cmp.prepend('<img title="���ɾ���Ĳ�Ʒ" src="images/changedelete.gif" />');
			 tr.css("color", "#8B9CA4");
		}
	}

	/**
	 * ��������ϸ
	 */
	var beforeAddRow=function(e, rowNum, rowData, g, tr) {
				var key = rowData.id;
				if (isTemp == 1) {
					key = rowData.originalId;
				}
				var detail = changeDetailObj["d" + key];
				if (detail) {
					for (var i = 0; i < detail.length; i++) {
						var changeField = detail[i].changeField;
						rowData[changeField] = tranToChangeShow(
								detail[i].oldValue,
								rowData[changeField]);
					}
				}
			}

	$("#selectChange").change(function() {
				var v = $(this).val();
				$(".oldValue").hide();
				$(".newValue").hide();
				$(".compare").hide();
				if (v == 1) {
					$(".newValue").show();
				} else if (v == 2) {
					$(".oldValue").show();
				} else {
					$(".oldValue").show();
					$(".newValue").show();
					$(".compare").show();
				}

			});

	var contractId = $("#contractId").val();
	var prinvipalId = $("#prinvipalId").val();
	var createId = $("#createId").val();
	var areaPrincipalId = $("#areaPrincipalId").val();
	var isTemp = $("#isTemp").val();
	var originalId = $("#originalId").val();
	var objId = contractId;
	if (isTemp == 1) {
		objId = originalId;
		var paramtoEqu = {
			  'contractId' : contractId,
			  'prinvipalId' : prinvipalId,
			  'createId' : createId,
			  'areaPrincipalId' : areaPrincipalId,
			  'isBorrowToorder' : '0'
			}
	    var paramtoBow = {
			  'contractId' : contractId,
			  'prinvipalId' : prinvipalId,
			  'createId' : createId,
			  'areaPrincipalId' : areaPrincipalId,
			  'isBorrowToorder' : '1'
			}
	}else{
        var paramtoEqu = {
			  'contractId' : contractId,
			  'prinvipalId' : prinvipalId,
			  'createId' : createId,
			  'areaPrincipalId' : areaPrincipalId,
			  'isTemp' : isTemp,
			  'isBorrowToorder' : '0'
			}
		var paramtoBow = {
			  'contractId' : contractId,
			  'prinvipalId' : prinvipalId,
			  'createId' : createId,
			  'areaPrincipalId' : areaPrincipalId,
			  'isTemp' : isTemp,
			  'isBorrowToorder' : '1'
			}
	}
	var param = {
		'contractId' : contractId,
		'prinvipalId' : prinvipalId,
		'createId' : createId,
		'areaPrincipalId' : areaPrincipalId,
		'isTemp' : isTemp
	};
	// ��ȡ�����ϸ��¼
	var data = $.ajax({

				url : '?model=common_changeLog&action=pageJsonDetail',
				type : 'POST',
				data : {
					logObj : 'contract',
					objId : objId,
					// detailType : "product",
					isLast : true,
					isGetUpdate : true,
					isTemp : '9'
					// ֻ��ȡ�༭����ϸ
				},
				async : false,
				dataType : 'json'
			}).responseText;
	 //alert(data)
	data = eval("(" + data + ")");
	var changeDetailObj = {};
	var key = "detailId";
	// if (isTemp = 1) {
	// key = "dtempId";
	// }
	if (data.collection) {
		for (var i = 0; i < data.collection.length; i++) {
			var c = data.collection[i];
			if (c.detailId != 0) {
				var detailId = "d" + c[key];
				if (!changeDetailObj[detailId]) {
					changeDetailObj[detailId] = [];
				}
				changeDetailObj[detailId].push(c);
			} else {
				if(c.changeField == 'signSubjectName'){// �ų��޸Ĺ�����˾ʱ,��ʾ�������� (���Ͷ���=>br)
					var $cf = $("#signSubject");
				}else{
					var $cf = $("#" + c.changeField);
				}

				var oldHtml = $cf.html();
				if(c.changeField=="shipCondition"){
					c.oldValue=(c.oldValue==0 && c.oldValue!='' ? "��������" : "������");
					c.newValue=(c.newValue==0 && c.newValue!='' ? "��������" : "������");
//					oldHtml=(oldHtml==0?"��������":"֪ͨ����");
				}
				//ǩԼ��λ����鿴
				if(c.changeField=="signSubject"){
					c.oldValue=getDataByCode(c.oldValue);
				}
				//�¿�Ʊ����
				if(c.changeField=="invoiceValue"){
					var dataCode = $("#dataCode").val();
					var itemArr = dataCode.split(',');
					var oldInvoice = c.oldValue.split(',');
					var newInvoice = c.newValue.split(',');
					for(j=0;j<oldInvoice.length;j++){
						if(oldInvoice[j]!==newInvoice[j]){
							if(oldInvoice[j]==''){
							   oldInvoice[j] = '0';
							}
							var invoiceHtml = tranToChangeShow(parseFloat(oldInvoice[j]).toFixed(2), parseFloat(newInvoice[j]).toFixed(2));
							$("#"+itemArr[j]+"Money").html(invoiceHtml);
						}
					}
				}

				if(c.changeField=="exgross"){
					c.oldValue = c.oldValue+"%";
					c.newValue = c.newValue+"%";
				}
				var newHtml = tranToChangeShow(c.oldValue, c.newValue);
//				if(oldHtml != '')
				  $cf.html(newHtml);
			}
		}
	}
	// �ͻ���ϵ��
	$("#linkmanListInfo").yxeditgrid({
		objName : 'contract[linkman]',
		url : '?model=contract_contract_linkman&action=listJsonLimit',
		type : 'view',
		param : param,
		tableClass : 'form_in_table',
		colModel : [{
					display : '�ͻ���ϵ��',
					name : 'linkmanName',
					tclass : 'txt'
				}, {
					display : '��ϵ��ID',
					name : 'linkmanId',
					type : 'hidden'
				}, {
					display : '�绰',
					name : 'telephone',
					tclass : 'txt'
				}, {
					display : 'QQ',
					name : 'QQ',
					tclass : 'txt'
				}, {
					display : '����',
					name : 'Email',
					tclass : 'txt'
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'linkmanName', rowData,tr);
			}
		}
	});

      proInfoList();
	//�տ��ƻ�
	$("#financialplanInfo").yxeditgrid({
		objName : 'contract[financialplan]',
		url : '?model=contract_contract_financialplan&action=listJsonLimit',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'contractId' : $("#contractId").val()
		},
		colModel : [{
						display : '����',
						name : 'planDate',
						type : 'date'
					}, {
						display : '��Ʊ���',
						name : 'invoiceMoney',
						tclass : 'txtshort',
						type : 'money'
					},{
						display : '�տ���',
						name : 'incomeMoney',
						tclass : 'txtshort',
						type : 'money'
					},{
						display : '��ע',
						name : 'remark',
						tclass : 'txtlong'
					}]
	});
	//������ת����
	$("#borrowConEquInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=listJson',
		type : 'view',
		param : paramtoBow,
		isAddOneRow : false,
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
			display : '�ӱ�Id',
			name : 'id',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '����Id',
			name : 'productId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '������',
			name : 'warrantyPeriod',
			tclass : 'txtshort'
		}],
		event : {
//			'reloadData' : function(e) {
//				initCacheInfo();
//			},
			beforeAddRow : function(e, rowNum, rowData, g, tr) {
				var key = rowData.id;
				if (isTemp == 1) {
					key = rowData.originalId;
				}
				var detail = changeDetailObj["d" + key];
				if (detail) {
					for (var i = 0; i < detail.length; i++) {
						var changeField = detail[i].changeField;
						if (changeField == "number") {
							rowData[changeField] = tranToChangeShow(
									detail[i].oldValue,
									rowData[detail[i].changeField]);
						}
						if (changeField == "money" || changeField == "price") {
							rowData[changeField] = tranToChangeShow(
									moneyFormat2(detail[i].oldValue),
									moneyFormat2(rowData[detail[i].changeField]));
						}
					}
				}
			},
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productCode', rowData,tr);
			}
		}

	});
	// �����嵥
	$("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : paramtoEqu,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '��ִ������',
			name : 'blueNum',
			tclass : 'txtshort'
		}, {
			display : '���˿�����',
			name : 'redNum',
			tclass : 'txtshort'
		}, {
			display : 'ʵ��ִ������',
			name : 'actNum'
		}, {
			display : '����',
			name : 'price',
			type : 'hidden',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'money',
			type : 'hidden',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '��������',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='#' onclick='showLicense(\"" + row.license
							+ "\")'>�鿴</a>";
				}
			}
		}],
		event : {
			beforeAddRow : function(e, rowNum, rowData, g, tr) {
				var key = rowData.id;
				if (isTemp == 1) {
					key = rowData.originalId;
				}
				var detail = changeDetailObj["d" + key];
				if (detail) {
					for (var i = 0; i < detail.length; i++) {
						var changeField = detail[i].changeField;
						if (changeField == "number") {
							rowData[changeField] = tranToChangeShow(
									detail[i].oldValue,
									rowData[detail[i].changeField]);
						}
						if (changeField == "money" || changeField == "price") {
							rowData[changeField] = tranToChangeShow(
									moneyFormat2(detail[i].oldValue),
									moneyFormat2(rowData[detail[i].changeField]));
						}
					}
				}
			},
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData,tr);
			}
		}
	});


});
// ������
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}
$(function() {
			var currency = $("#currency").html();
			if (currency != '�����' && currency != '') {
				document.getElementById("currencyRate").style.display = "";
			}
		});
$(function(){

	   //��ͬ��������
	    var advance = $("#advance").html();
	    if( advance != ""){
            $("#advance").html("<span style='color:#0080FF'>Ԥ����</span> : "+advance+"%&nbsp&nbsp|&nbsp");
	    }
	    var delivery = $("#delivery").html();
	    if( delivery != ""){
            $("#delivery").html("<span style='color:#0080FF'>��������</span> : "+delivery+"%&nbsp&nbsp|&nbsp");
	    }
	    var initialpayment = $("#initialpayment").html();
	    if( initialpayment != ""){
            $("#initialpayment").html("<span style='color:#0080FF'>����ͨ������</span> : "+initialpayment+"%&nbsp&nbsp|&nbsp");
	    }
	    var finalpayment = $("#finalpayment").html();
	    if( finalpayment != ""){
            $("#finalpayment").html("<span style='color:#0080FF'>����ͨ������</span> : "+finalpayment+"%&nbsp&nbsp|&nbsp");
	    }
       //�����ȸ���
	    var progresspayment = $("#progresspayment").html();
	    if( progresspayment != ""){
            $("#progresspayment").html("<span style='color:#0080FF'>�����ȸ���</span> :");
            var progressArr = progresspayment.split(',');
		    $.each(progressArr,function(i,n){
		    	var str = '<tr>' +
					'<td id="progresspaymentterm'+i+'"></td>' +
					'<td>'+n+'%</td>' +
					'</tr>';
		    	$("#progresspayment").append(str);
		    });
		    var progresspaymentterm = $("#progresspaymentterm").val();
		    var progresspaymenttermArr = progresspaymentterm.split(',');
		    $.each(progresspaymenttermArr,function(i,n){
		    	$("#progresspaymentterm"+i).html(n);
		    });
	    }
	    //��������
        var otherpayment = $("#otherpayment").html();
	    if( otherpayment != ""){
            $("#otherpayment").html("<span style='color:#0080FF'>������������</span> :");
            var otherpaymentArr = otherpayment.split(',');
		    $.each(otherpaymentArr,function(i,n){
		    	var str = '<tr>' +
					'<td id="otherpaymentterm'+i+'"></td>' +
					'<td>'+n+'%</td>' +
					'</tr>';
		    	$("#otherpayment").append(str);
		    });
		    var otherpaymentterm = $("#otherpaymentterm").val();
		    var otherpaymenttermArr = otherpaymentterm.split(',');
		    $.each(otherpaymenttermArr,function(i,n){
		    	$("#otherpaymentterm"+i).html(n);
		    });
	    }
    })


    // ��ϸ���ϳɱ�
function equCoseView() {
	var isTemp = $("#isTemp").val();
    if(isTemp == '1'){
        var istemp = "1";
    }else{
        var istemp = "0";
    }
	showThickboxWin('?model=contract_contract_contract&action=equCoseView&contractId='
			+ $("#contractId").val() + '&istemp=' + istemp
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}


//��Ʒ�嵥
function proInfoList(){
	var currency = $("#currency").html();
   if (currency != '�����') {
   	var rate = $("#rateV").html();
    //��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		url : '?model=contract_contract_product&action=listJsonLimit',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'contractId' : $("#contractId").val(),
			'dir' : 'ASC',
			'prinvipalId' : $("#prinvipalId").val(),
			'createId' : $("#createId").val(),
			'areaPrincipalId' : $("#areaPrincipalId").val(),
			//			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
            name: 'newProLineName',
            display: '��Ʒ��',
            sortable : true,
            width: 100
        }, {
            name: 'exeDeptName',
            display: 'ִ������',
			sortable : true,
            width: 100
        }, {
			name : 'proTypeId',
			display : '��Ʒ����',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == "11") {
					return "�������Ʒ";
				} else if (v == "17") {
					return "�������Ʒ";
				} else if (v == "18") {
					return "�з����Ʒ";
				} else {
					return "--";
				}
			}
		}, {
            name: 'goodsClassName',
            display: '��Ʒ����',
            width: 80
        }, {
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
				return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
						+ row.id
						+ '&contractId='
						+ $("#contractId").val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '��ƷId',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v,6);
			}
		}, {
			display : '����('+currency+')',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v*rate);
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
		    }
		}, {
			display : '���('+currency+')',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v*rate);
			}
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '��������',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='javascript:void(0)' onclick='showLicense(\""
							+ row.license + "\")'>��������</a>";
				}
			}
		}, {
			display : '��Ʒ����Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '��Ʒ����',
			process : function(v, row) {
				if (row.deploy != "") {
					return "<a href='javascript:void(0)' onclick='showGoods(\""
							+ row.deploy
							+ "\",\""
							+ row.conProductName
							+ "\")'>��Ʒ����</a>";
				}
			}
		}],
		event : {
			'reloadData' : function(e) {
				initCacheInfo();
			}
		}
	});
   }else{
	//��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		url : '?model=contract_contract_product&action=listJsonLimit',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'contractId' : $("#contractId").val(),
			'dir' : 'ASC',
			'prinvipalId' : $("#prinvipalId").val(),
			'createId' : $("#createId").val(),
			'areaPrincipalId' : $("#areaPrincipalId").val(),
			//			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
            name: 'newProLineName',
            display: '��Ʒ��',
            sortable : true,
            width: 100
        }, {
            name: 'exeDeptName',
            display: 'ִ������',
			sortable : true,
            width: 100
        }, {
			name : 'proTypeId',
			display : '��Ʒ����',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == "11") {
					return "�������Ʒ";
				} else if (v == "17") {
					return "�������Ʒ";
				} else if (v == "18") {
					return "�з����Ʒ";
				} else {
					return "--";
				}
			}
		}, {
            name: 'goodsClassName',
            display: '��Ʒ����',
            width: 80
        }, {
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
				return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
						+ row.id
						+ '&contractId='
						+ $("#contractId").val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '��ƷId',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v,6);
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
				//		}, {
				//			display : '������',
				//			name : 'warrantyPeriod',
				//			tclass : 'txtshort'
				}, {
					display : '��������Id',
					name : 'license',
					type : 'hidden'
				}, {
					name : 'licenseButton',
					display : '��������',
					process : function(v, row) {
						if (row.license != "") {
							return "<a href='javascript:void(0)' onclick='showLicense(\""
									+ row.license + "\")'>��������</a>";
						}
					}
				}, {
					display : '��Ʒ����Id',
					name : 'deploy',
					type : 'hidden'
				}, {
					name : 'deployButton',
					display : '��Ʒ����',
					process : function(v, row) {
						if (row.deploy != "") {
							return "<a href='javascript:void(0)' onclick='showGoods(\""
									+ row.deploy
									+ "\",\""
									+ row.conProductName
									+ "\")'>��Ʒ����</a>";
						}
					}
				}],
		event : {
			'reloadData' : function(e) {
				initCacheInfo();
			}
		}
	});
   }
}