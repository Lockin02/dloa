$(function() {

    if( $("#paperContract").val()=='无'){
        $("#paperContractView").html("*此合同无纸质合同");
    }else{
        $("#paperContractView").hide("");
    }
	//新开票类型控制
	var dataCode = $("#dataCode").val();
	var itemArr = dataCode.split(',');
	var itemLength = itemArr.length;
    for(i=0;i<itemLength;i++){
		if($("#"+itemArr[i]+"V").val()==1){
			$("#"+itemArr[i]).before("√");
			$("#"+itemArr[i]+"Hide").show();
		}else{
			$("#"+itemArr[i]).before("×");
			$("#"+itemArr[i]).css("color","#969696");
		}
	}
	//判断预计毛利颜色
	  var exgross = $("#exgross").html();
	  var exgrossVal = $("#exgrossVal").val();
	  if(exgross < exgrossVal){
	     $("#exgross").attr('style',"color:red");
	  }else{
	     $("#exgross").attr('style',"color:black");
	  }
	/**
	 * 转成变更颜色提醒
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
	 * 给可编辑表格加上变更标识图标
	 */
	var addGridChangeShow = function(g, rowNum, colName, rowData,tr) {
		var $cmp = g.getCmpByRowAndCol(rowNum, colName);
		if (rowData.changeTips == '1') {
			// tr.css("background-color", "#F8846B");
			$cmp.prepend('<img title="变更编辑的产品" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {

			$cmp.prepend('<img title="变更新增的产品" src="images/new.gif" />');
		} else if (rowData.changeTips == '3' || rowData.isDel == '1') {
			$cmp.prepend('<img title="变更删除的产品" src="images/changedelete.gif" />');
			 tr.css("color", "#8B9CA4");
		}
	}

	/**
	 * 处理变更明细
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
	// 获取变更明细记录
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
					// 只获取编辑的明细
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
				if(c.changeField == 'signSubjectName'){// 排除修改归属公司时,显示编码问题 (世纪鼎利=>br)
					var $cf = $("#signSubject");
				}else{
					var $cf = $("#" + c.changeField);
				}

				var oldHtml = $cf.html();
				if(c.changeField=="shipCondition"){
					c.oldValue=(c.oldValue==0 && c.oldValue!='' ? "立即发货" : "不发货");
					c.newValue=(c.newValue==0 && c.newValue!='' ? "立即发货" : "不发货");
//					oldHtml=(oldHtml==0?"立即发货":"通知发货");
				}
				//签约单位变更查看
				if(c.changeField=="signSubject"){
					c.oldValue=getDataByCode(c.oldValue);
				}
				//新开票类型
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
	// 客户联系人
	$("#linkmanListInfo").yxeditgrid({
		objName : 'contract[linkman]',
		url : '?model=contract_contract_linkman&action=listJsonLimit',
		type : 'view',
		param : param,
		tableClass : 'form_in_table',
		colModel : [{
					display : '客户联系人',
					name : 'linkmanName',
					tclass : 'txt'
				}, {
					display : '联系人ID',
					name : 'linkmanId',
					type : 'hidden'
				}, {
					display : '电话',
					name : 'telephone',
					tclass : 'txt'
				}, {
					display : 'QQ',
					name : 'QQ',
					tclass : 'txt'
				}, {
					display : '邮箱',
					name : 'Email',
					tclass : 'txt'
				}, {
					display : '备注',
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
	//收开计划
	$("#financialplanInfo").yxeditgrid({
		objName : 'contract[financialplan]',
		url : '?model=contract_contract_financialplan&action=listJsonLimit',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'contractId' : $("#contractId").val()
		},
		colModel : [{
						display : '日期',
						name : 'planDate',
						type : 'date'
					}, {
						display : '开票金额',
						name : 'invoiceMoney',
						tclass : 'txtshort',
						type : 'money'
					},{
						display : '收款金额',
						name : 'incomeMoney',
						tclass : 'txtshort',
						type : 'money'
					},{
						display : '备注',
						name : 'remark',
						tclass : 'txtlong'
					}]
	});
	//借试用转销售
	$("#borrowConEquInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=listJson',
		type : 'view',
		param : paramtoBow,
		isAddOneRow : false,
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
			display : '从表Id',
			name : 'id',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '物料Id',
			name : 'productId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '保修期',
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
	// 发货清单
	$("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : paramtoEqu,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '需求数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '已执行数量',
			name : 'blueNum',
			tclass : 'txtshort'
		}, {
			display : '已退库数量',
			name : 'redNum',
			tclass : 'txtshort'
		}, {
			display : '实际执行数量',
			name : 'actNum'
		}, {
			display : '单价',
			name : 'price',
			type : 'hidden',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'money',
			type : 'hidden',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '加密配置',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='#' onclick='showLicense(\"" + row.license
							+ "\")'>查看</a>";
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
// 表单收缩
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
			if (currency != '人民币' && currency != '') {
				document.getElementById("currencyRate").style.display = "";
			}
		});
$(function(){

	   //合同付款条件
	    var advance = $("#advance").html();
	    if( advance != ""){
            $("#advance").html("<span style='color:#0080FF'>预付款</span> : "+advance+"%&nbsp&nbsp|&nbsp");
	    }
	    var delivery = $("#delivery").html();
	    if( delivery != ""){
            $("#delivery").html("<span style='color:#0080FF'>货到付款</span> : "+delivery+"%&nbsp&nbsp|&nbsp");
	    }
	    var initialpayment = $("#initialpayment").html();
	    if( initialpayment != ""){
            $("#initialpayment").html("<span style='color:#0080FF'>初验通过付款</span> : "+initialpayment+"%&nbsp&nbsp|&nbsp");
	    }
	    var finalpayment = $("#finalpayment").html();
	    if( finalpayment != ""){
            $("#finalpayment").html("<span style='color:#0080FF'>终验通过付款</span> : "+finalpayment+"%&nbsp&nbsp|&nbsp");
	    }
       //按进度付款
	    var progresspayment = $("#progresspayment").html();
	    if( progresspayment != ""){
            $("#progresspayment").html("<span style='color:#0080FF'>按进度付款</span> :");
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
	    //其他付款
        var otherpayment = $("#otherpayment").html();
	    if( otherpayment != ""){
            $("#otherpayment").html("<span style='color:#0080FF'>其他付款条件</span> :");
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


    // 详细物料成本
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


//产品清单
function proInfoList(){
	var currency = $("#currency").html();
   if (currency != '人民币') {
   	var rate = $("#rateV").html();
    //产品清单
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
            display: '产品线',
            sortable : true,
            width: 100
        }, {
            name: 'exeDeptName',
            display: '执行区域',
			sortable : true,
            width: 100
        }, {
			name : 'proTypeId',
			display : '产品类型',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == "11") {
					return "销售类产品";
				} else if (v == "17") {
					return "服务类产品";
				} else if (v == "18") {
					return "研发类产品";
				} else {
					return "--";
				}
			}
		}, {
            name: 'goodsClassName',
            display: '产品分类',
            width: 80
        }, {
			display : '产品名称',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
				return '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
						+ row.id
						+ '&contractId='
						+ $("#contractId").val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '产品Id',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '产品描述',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v,6);
			}
		}, {
			display : '单价('+currency+')',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v*rate);
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
		    }
		}, {
			display : '金额('+currency+')',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v*rate);
			}
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '加密配置',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='javascript:void(0)' onclick='showLicense(\""
							+ row.license + "\")'>加密配置</a>";
				}
			}
		}, {
			display : '产品配置Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '产品配置',
			process : function(v, row) {
				if (row.deploy != "") {
					return "<a href='javascript:void(0)' onclick='showGoods(\""
							+ row.deploy
							+ "\",\""
							+ row.conProductName
							+ "\")'>产品配置</a>";
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
	//产品清单
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
            display: '产品线',
            sortable : true,
            width: 100
        }, {
            name: 'exeDeptName',
            display: '执行区域',
			sortable : true,
            width: 100
        }, {
			name : 'proTypeId',
			display : '产品类型',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == "11") {
					return "销售类产品";
				} else if (v == "17") {
					return "服务类产品";
				} else if (v == "18") {
					return "研发类产品";
				} else {
					return "--";
				}
			}
		}, {
            name: 'goodsClassName',
            display: '产品分类',
            width: 80
        }, {
			display : '产品名称',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
				return '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
						+ row.id
						+ '&contractId='
						+ $("#contractId").val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '产品Id',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '产品描述',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v,6);
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
				//		}, {
				//			display : '保修期',
				//			name : 'warrantyPeriod',
				//			tclass : 'txtshort'
				}, {
					display : '加密配置Id',
					name : 'license',
					type : 'hidden'
				}, {
					name : 'licenseButton',
					display : '加密配置',
					process : function(v, row) {
						if (row.license != "") {
							return "<a href='javascript:void(0)' onclick='showLicense(\""
									+ row.license + "\")'>加密配置</a>";
						}
					}
				}, {
					display : '产品配置Id',
					name : 'deploy',
					type : 'hidden'
				}, {
					name : 'deployButton',
					display : '产品配置',
					process : function(v, row) {
						if (row.deploy != "") {
							return "<a href='javascript:void(0)' onclick='showGoods(\""
									+ row.deploy
									+ "\",\""
									+ row.conProductName
									+ "\")'>产品配置</a>";
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