// license类型
// var licenseTypeArr = [];
var productLineArr = [];// 产品线数组
var invoiceTypeArr = [];
var licensetypeStore = null;// license类型store
var licensetypeRecords = [];// license类型记录数组
Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();

	var countNum = $('#EquNum').val();

	var renderProductsInfo = function() {
		for (var i = 1; i <= countNum; i++) {
			var productGrid = {
				xtype : 'productinfocombogrid',
				rowNum : i,
				listeners : {
					dblclick : function(e) {
						var record = this.getSelectionModel().getSelected();
						$("#EquId" + this.rowNum).val(record.get('sequence'));
						$("#EquModel" + this.rowNum).val(record.get('pattern'));
					}
				}
			}
			new Ext.ux.combox.MyGridComboBox({
				applyTo : 'EquName' + i,
				gridName : 'productName',// 下拉表格显示的属性
				gridValue : 'id',
				hiddenFieldId : 'ProductId' + i,
				myGrid : productGrid
			})

			var productGrid = {
				xtype : 'productinfocombogrid',
				rowNum : i,
				listeners : {
					dblclick : function(e) {
						var record = this.getSelectionModel().getSelected();
						$("#EquName" + this.rowNum).val(record
								.get('productName'));
						$("#EquModel" + this.rowNum).val(record.get('pattern'));
					}
				}
			}
			new Ext.ux.combox.MyGridComboBox({
				applyTo : 'EquId' + i,
				gridName : 'sequence',// 下拉表格显示的属性
				gridValue : 'id',
				hiddenFieldId : 'ProductId' + i,
				myGrid : productGrid
			})

			var productGrid = {
				xtype : 'productinfocombogrid',
				rowNum : i,
				listeners : {
					dblclick : function(e) {
						var record = this.getSelectionModel().getSelected();
						$("#EquName" + this.rowNum).val(record
								.get('productName'));
						$("#EquId" + this.rowNum).val(record.get('sequence'));
					}
				}
			}
			new Ext.ux.combox.MyGridComboBox({
				applyTo : 'EquModel' + i,
				gridName : 'pattern',// 下拉表格显示的属性
				gridValue : 'id',
				hiddenFieldId : 'ProductId' + i,
				myGrid : productGrid
			})

			var elistNum = $('#elist_' + i).val();
			for (var j = 2; j <= elistNum; j++) {
				var productGrid = {
					xtype : 'productinfocombogrid',
					rowNumElist : j,
					rowNum : i,
					listeners : {
						dblclick : function(e) {
							var record = this.getSelectionModel().getSelected();
							$('#elist_' + this.rowNum + "_EquId"
									+ this.rowNumElist).val(record
									.get('sequence'));
							$('#elist_' + this.rowNum + "_EquModel"
									+ this.rowNumElist).val(record
									.get('pattern'));
						}
					}
				}
				new Ext.ux.combox.MyGridComboBox({
							applyTo : 'elist_' + i + "_EquName" + j,
							gridName : 'productName',// 下拉表格显示的属性
							gridValue : 'id',
							hiddenFieldId : 'elist_' + i + "_ProductId" + j,
							myGrid : productGrid
						})
			}

		};
	}
	var customerLinkManGrid = {
		id : 'linkmanGrid',
		xtype : 'customerlinkcombogrid',
		listeners : {
			dblclick : function(e) {
				var record = this.getSelectionModel().getSelected();
				$("#customerTel").val(record.get('phone'));
				$("#customerEmail").val(record.get('email'));
			}
		}
	}
	new Ext.ux.combox.MyGridComboBox({
				applyTo : 'customerLinkman',
				gridName : 'linkmanName',// 下拉表格显示的属性
				myGrid : customerLinkManGrid
			})
	var customerGrid = {
		linkmanGrid : customerLinkManGrid,
		xtype : 'customercombogrid',
		listeners : {
			dblclick : function(e) {
				var record = this.getSelectionModel().getSelected();
				$("#customerId").val(record.get('id'));
						$("#customerType").val(record.get('TypeOne'));
//						alert( record.get('TypeOne') )
//						alert( "111111111" );
						$("#provincecity").val(record.get('Prov'));
			}
		}
	}
	new Ext.ux.combox.MyGridComboBox({
				applyTo : 'customerName',
				gridName : 'Name',// 下拉表格显示的属性
				myGrid : customerGrid
			})
	renderProductsInfo();


	// 产品线数据
	productLineArr = getData('CPX');
	if (!$("#contNumber").val()) {
		addDataToSelect(productLineArr, 'productLine1');
		addDataToSelect(productLineArr, 'lProductLine1');
	}

	//开票类型
	invoiceTypeArr = getData('FPLX');
	if (!$("#contNumber").val()) {
		addDataToSelect(invoiceTypeArr, 'invoiceType');
		addDataToSelect(invoiceTypeArr, 'invoiceListType1');
	}

	//客户类型
	customerTypeArr = getData('KHLX');
	if (!$("#contNumber").val()) {
		addDataToSelect(customerTypeArr, 'customerType');
		addDataToSelect(customerTypeArr, 'customerListTypeArr1');
	}

	var reader = new Ext.data.JsonReader({
				totalProperty : 'totalSize',
				root : 'collection'
			}, [{
						name : 'id'
					}, {
						name : 'typeName'
					}]);
	licensetypeStore = new Ext.data.Store({
		proxy : new Ext.data.HttpProxy({
			url : 'index1.php?model=product_licensetype_licensetype&action=pageJson'
		}),
		autoLoad : true,
		reader : reader,
		listeners : {
			load : function(t, r) {
				// 如果没有licensetype数量，为新增页面
				if (!$("#contNumber").val()) {
					var licensetypeRecords = [];
					licensetypeStore.each(function(record) {
								licensetypeRecords.push(record.copy());
							});
					var ls = new Ext.data.Store();
					ls.add(licensetypeRecords);
					new Ext.ux.form.MultiSelect({
								applyTo : 'licenseType1',
								hiddenFieldId : 'licenseinput1',
								store : ls,
								displayField : 'typeName',
								valueField : "id",
								mode : 'local',
								triggerAction : 'all',
								listeners : {
									collapse : function() {
										// $("#licenseNodeName1").val('');
									}
								}
							})
				} else {
					var licensetypenum = parseInt($("#licensetypenum").val());
					for (var i = 1; i < licensetypenum + 1; i++) {
						if (document.getElementById('licenseType' + i)) {
							var licensetypeRecords = [];
							licensetypeStore.each(function(record) {
										licensetypeRecords.push(record.copy());
									});
							var ls = new Ext.data.Store();
							ls.add(licensetypeRecords);
							new Ext.ux.form.MultiSelect({
										applyTo : 'licenseType' + i,
										hiddenFieldId : 'licenseinput' + i,
										store : ls,
										displayField : 'typeName',
										valueField : "id",
										mode : 'local',
										triggerAction : 'all'
									})
						}
					}
				}
			}
		}
	});

});

/*
 * 动态添加license类型
 *
 * function addLicenseType(licenseTypeId, licenseTypeArr) { for (var i = 0, l =
 * licenseTypeArr.length; i < l; i++) { $("#" + licenseTypeId).append("<option
 * value='" + licenseTypeArr[i].id + "'>" + licenseTypeArr[i].typeName + "</option>"); } }
 */


// **************开票计划******************

function inv_add(myinv, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myinv = document.getElementById(myinv);
	i = myinv.rows.length;
	oTR = myinv.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[invoice][" + i
			+ "][money]' id='InvMoney" + mycount
			+ "' size='10' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[invoice][" + i
			+ "][softM]' id='InvSoftM" + mycount
			+ "' size='10' maxlength='40'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtshort' name='sales[invoice]["
			+ i
			+ "][iType]' id='invoiceListType"+ mycount +"'></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='sales[invoice][" + i
			+ "][invDT]' id='InvDT" + mycount
			+ "' size='12' onfocus='WdatePicker()'>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtlong' type='text' name='sales[invoice][" + i
			+ "][remark]' id='InvRemark" + mycount
			+ "' size='60' maxlength='100'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ myinv.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	addDataToSelect(invoiceTypeArr, 'invoiceListType' + mycount);

	createFormatOnClick('InvMoney'+mycount);
	createFormatOnClick('InvSoftM'+mycount);
}

// **********************自定义清单******************
function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][productLine]' id='cproductLine" + mycount
			+ "' value='' size='9' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][productnumber]' id='PequID" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][name]' id='PequName" + mycount
			+ "' value='' size='15' maxlength='20'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][prodectmodel]' id='PreModel" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][amount]' id='PreAmount" + mycount
			+ "' onblur='FloatMul(\"PreAmount" + mycount + "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount
			+ "\")' size='8' maxlength='40'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][price]' id='PrePrice" + mycount
			+ "' onblur='FloatMul(\"PreAmount" + mycount + "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount
			+ "\")' size='8' maxlength='40'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][countMoney]' id='CountMoney" + mycount
			+ "' size='8' maxlength='40'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][projArraDT]' id='PreDeliveryDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input class='txt' type='text' name='sales[customizelist][" + mycount
			+ "][remark]' id='PRemark" + mycount
			+ "' value='' size='18' maxlength='100'/>";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='sales[customizelist][" + mycount
			+ "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	createFormatOnClick('PrePrice'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
	createFormatOnClick('CountMoney'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
}

// ************************收款计划*************************
function pay_add(mypay, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[receiptplan][" + mycount
			+ "][money]' id='PayMoney" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[receiptplan][" + mycount
			+ "][payDT]' id='PayDT" + mycount
			+ "' size='12' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtshort' name='sales[receiptplan]["
			+ mycount
			+ "][pType]'><option value='电汇'>电汇</option><option value='现金'>现金</option><option value='银行汇票'>银行汇票</option><option value='商业汇票'>商业汇票</option></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='sales[receiptplan][" + mycount
			+ "][collectionTerms]' id='collectionTerms" + mycount
			+ "' size='70' />";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	createFormatOnClick('PayMoney'+mycount);
}

// **********培训计划***********************

function train_add(mytra, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mytra = document.getElementById(mytra);
	i = mytra.rows.length;
	oTR = mytra.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[trainingplan][" + mycount
			+ "][beginDT]' id='TraDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[trainingplan][" + mycount
			+ "][endDT]' id='TraEndDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='sales[trainingplan][" + mycount
			+ "][traNum]' value='' size='8' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='sales[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='sales[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='sales[trainingplan][" + mycount
			+ "][trainerDemand]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

// *****************删除和序号排列******************************
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}

// *****************删除和序号排列-软件套装列表******************************
function listdel(obj, numt) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = obj.parentNode.parentNode.parentNode.parentNode;
		var cnum = obj.parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[0].childNodes[0].childNodes[0].innerHTML;
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = cnum + "-" + (i);

		}
	}
}

// *****************产品清单删除和序号排列******************************
function equmydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		$.each($(':lable[id^="equlabel"]'),function(i,n){
			i = i + 1;
			$(this).html(i);
		})
	}
}

// *****************产品清单添加软件示例1******************************
function soft_add(myequ, countNum) {
	deliveryDate = $('#deliveryDate').val();
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myequ = document.getElementById(myequ);
	i = myequ.rows.length;
	oTR = myequ.insertRow([i]);
	oTR.align = "center";
	oTR.height = "28px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i ;
    oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<select class='txtshort' name='sales[equipment]["
			+ mycount
			+ "][productLine]' id='productLine"
			+ mycount
			+ "'></select>";
	addDataToSelect(productLineArr, 'productLine' + mycount);
    oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][productNumber]' id='EquId"
			+ mycount
			+ "' /><input  type='hidden' name='sales[equipment]["
			+ mycount
			+ "][ptype]' value='soft'/>";
    oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = "<input  type='text' name='sales[equipment]["
			+ mycount
			+ "][productName]' id='EquName"
			+ mycount
			+ "'size='20'>"
			+ "<input class='txtshort' type='hidden' name='sales[equipment]["
			+ mycount
			+ "][productId]' id='ProductId"
			+ mycount
			+ "'>";
    oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][productModel]' id='EquModel"
			+ mycount
			+ "' size='9'>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][amount]' id='EquAmount"
			+ mycount
			+ "' onblur='FloatMul(\"EquAmount"
			+ mycount
			+ "\",\"EquPrice"
			+ mycount
			+ "\",\"EquAllMoney"
			+ mycount
			+ "\",2)' size='8' maxlength='40'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][price]' id='EquPrice"
			+ mycount
			+ "' onblur='FloatMul(\"EquAmount"
			+ mycount
			+ "\",\"EquPrice"
			+ mycount
			+ "\",\"EquAllMoney"
			+ mycount
			+ "\",2)' size='8' maxlength='40'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][countMoney]' id='EquAllMoney"
			+ mycount
			+ "' size='8' maxlength='40'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][projArraDate]' id='EquDeliveryDT"
			+ mycount
			+ "' size='10' value='"+ deliveryDate +"' onfocus='WdatePicker()'/>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<select class='txtshort' name='sales[equipment]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>"
			+ "<option value='半年'>半年</option>"
			+ "<option value='一年'>一年</option>"
			+ "<option value='两年'>两年</option>"
			+ "<option value='三年'>三年</option>"
			+ "</select>";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='sales[equipment]["
			+ mycount
			+ "][isSell]' checked='checked'>";
	oTL11 = oTR.insertCell([11]);
    oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ myequ.id + "\")' title='删除行'>";

	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	addDataToSelect(productLineArr, 'productLine' + mycount);

	createFormatOnClick('EquPrice'+mycount,'EquAmount'+mycount,'EquPrice'+mycount,'EquAllMoney'+mycount);
	createFormatOnClick('EquAllMoney'+mycount,'EquAmount'+mycount,'EquPrice'+mycount,'EquAllMoney'+mycount);

	var countNum = $('#EquNum').val();
	var renderProductsInfo = function() {
		var productGrid = {
			xtype : 'productinfocombogrid',
			listeners : {
				dblclick : function(e) {
					var record = this.getSelectionModel().getSelected();
					$("#EquId" + countNum).val(record.get('sequence'));
					$("#EquModel" + countNum).val(record.get('pattern'));
				}
			}
		};
		new Ext.ux.combox.MyGridComboBox({
					applyTo : 'EquName' + countNum,
					gridName : 'productName',// 下拉表格显示的属性
					gridValue : 'id',
					hiddenFieldId : 'ProductId' + countNum,
					myGrid : productGrid
				})

		var productGrid = {
			xtype : 'productinfocombogrid',
			listeners : {
				dblclick : function(e) {
					var record = this.getSelectionModel().getSelected();
					$("#EquName" + countNum).val(record.get('productName'));
					$("#EquModel" + countNum).val(record.get('pattern'));
				}
			}
		};
		new Ext.ux.combox.MyGridComboBox({
					applyTo : 'EquId' + countNum,
					gridName : 'sequence',// 下拉表格显示的属性
					gridValue : 'id',
					hiddenFieldId : 'ProductId' + countNum,
					myGrid : productGrid
				})

		var productGrid = {
			xtype : 'productinfocombogrid',
			rowNum : i,
			listeners : {
				dblclick : function(e) {
					var record = this.getSelectionModel().getSelected();
					$("#EquName" + countNum).val(record.get('productName'));
					$("#EquId" + countNum).val(record.get('sequence'));
				}
			}
		}
		new Ext.ux.combox.MyGridComboBox({
			applyTo : 'EquModel' + countNum,
			gridName : 'pattern',// 下拉表格显示的属性
			gridValue : 'id',
			hiddenFieldId : 'ProductId' + countNum,
			myGrid : productGrid
		})
	}
	renderProductsInfo();
}

// *****************软件添加套装列表******************************
function list_add(objparent, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myequ = objparent.parentNode.parentNode.parentNode.parentNode;
	var numt = objparent.parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[0].childNodes[0].childNodes[0].innerHTML;
	if (numt % 2 == 1)
		myclass = "TableLine2";
	else
		myclass = "TableLine1";
	i = myequ.rows.length;
	oTR = myequ.insertRow([i]);
	oTR.className = myclass;
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = numt + "-" + (i);
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][productNumber]' id='" + countNum + "_EquId"
			+ mycount + "' size='11' maxlength='40'/>"
			+ "<input type='hidden' name='sales[equipment][" + countNum + "-"
			+ mycount + "][ptype]' value='suit'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][productName]' id='" + countNum + "_EquName"
			+ mycount + "' size='24' maxlength='20' />"
			+ "<input type='hidden' name='sales[equipment][" + countNum + "-"
			+ mycount + "][productId]' id='" + countNum + "_ProductId"
			+ mycount + "' />";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][productModel]' id='" + countNum + "_EquModel"
			+ mycount + "' size='10' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][amount]' id='" + countNum + "_EquAmount"
			+ mycount + "' size='8' maxlength='40'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][price]' id='" + countNum + "_EquPrice"
			+ mycount + "' onblur='FloatMul(\"" + countNum + "_EquAmount"
			+ mycount + "\",\"" + countNum + "_EquPrice" + mycount + "\",\""
			+ countNum + "_EquAllMoney" + mycount
			+ "\")' size='8' maxlength='40'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][countMoney]' id='" + countNum + "_EquAllMoney"
			+ mycount + "' onblur='FloatMul(\"" + countNum + "_EquAmount"
			+ mycount + "\",\"" + countNum + "_EquPrice" + mycount + "\",\""
			+ countNum + "_EquAllMoney" + mycount
			+ "\")' size='8' maxlength='40'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input type='text' name='sales[equipment][" + countNum
			+ "-" + mycount + "][projArraDate]' id='" + countNum + "_ListDT"
			+ mycount + "' size='12' onfocus='WdatePicker()'>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<select name='sales[equipment][" + countNum + "-"
			+ mycount + "][warrantyPeriod]' id='" + countNum
			+ "_warrantyPeriod" + mycount + "'>"
			+ "<option value='半年'>半年</option>"
			+ "<option value='一年'>一年</option>"
			+ "<option value='两年'>两年</option>"
			+ "<option value='三年'>三年</option></select>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='sales[equipment]["
			+ countNum + "-" + mycount + "][isSell]' id='" + countNum
			+ "_isSell" + mycount + "' checked='checked'>";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='listdel(this,"
			+ numt + ")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	var renderProductsInfo = function() {
		var productGrid = {
			xtype : 'productinfocombogrid',
			rowsNumber : mycount,
			listeners : {
				dblclick : function(e) {
					var record = this.getSelectionModel().getSelected();
					$("#" + countNum + "_EquId" + this.rowsNumber).val(record
							.get('sequence'));
					$("#" + countNum + "_EquModel" + this.rowsNumber)
							.val(record.get('pattern'));
				}
			}
		};
		new Ext.ux.combox.MyGridComboBox({
					applyTo : countNum + '_EquName' + mycount,
					gridName : 'productName',// 下拉表格显示的属性
					gridValue : 'id',
					hiddenFieldId : countNum + '_ProductId' + mycount,
					myGrid : productGrid
				})

		var productGrid = {
			xtype : 'productinfocombogrid',
			rowsNumber : mycount,
			listeners : {
				dblclick : function(e) {
					var record = this.getSelectionModel().getSelected();
					$("#" + countNum + "_EquName" + this.rowsNumber).val(record
							.get('productName'));
					$("#" + countNum + "_EquModel" + this.rowsNumber)
							.val(record.get('pattern'));
				}
			}
		};
		new Ext.ux.combox.MyGridComboBox({
					applyTo : countNum + '_EquId' + mycount,
					gridName : 'sequence',// 下拉表格显示的属性
					gridValue : 'id',
					hiddenFieldId : countNum + '_ProductId' + mycount,
					myGrid : productGrid
				})
	}
	renderProductsInfo();

}

/** **********************************加密信息**************************** */
function license_add(mylicense, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mylicense = document.getElementById(mylicense);
	i = mylicense.rows.length;
	oTR = mylicense.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "55px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<select class='txtshort' name='sales[licenselist][" + mycount
			+ "][productLine]' id='lproductLine" + mycount + "'></select>";
	addDataToSelect(productLineArr, 'lproductLine' + mycount);
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<select class='txtshort' name='sales[licenselist][" + mycount
			+ "][softdogType]' id='softdogType" + mycount
			+ "'><option value='HASP'>HASP</option></select>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='sales[licenselist][" + mycount
			+ "][amount]' id='softdogAmount" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' value='' id='licenseType" + mycount
			+ "' name='sales[licenselist][" + mycount
			+ "][licenseType]'/><input type='hidden' value='' id='licenseinput"
			+ mycount + "' name='sales[licenselist][" + mycount
			+ "][licenseTypeIds]'/>";

	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='hidden' name='sales[licenselist]["
			+ mycount
			+ "][nodeId]' id='licenseNodeId"
			+ mycount
			+ "'><textarea name='sales[licenselist]["
			+ mycount
			+ "][nodeName]' onclick='openDia("
			+ mycount
			+ ")' id='licenseNodeName"
			+ mycount
			+ "' rows='3' cols='20' onmouseover='this.style.cursor=\"pointer\"' title='选择加密信息' ></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<select class='txtshort' name='sales[licenselist]["
			+ mycount
			+ "][validity]' id='validity"
			+ mycount
			+ "'><option value='半年'>半年</option><option value='一年'>一年</option><option value='两年'>两年</option></select>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txt' type='text' name='sales[licenselist][" + mycount
			+ "][remark]' id='licenseRemark" + mycount
			+ "' value='' size='25' maxlength='100'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input  type='checkbox' name='sales[licenselist][" + mycount
			+ "][isSell]' id='licenseisSell" + mycount
			+ "' checked='checked' />";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mylicense.id + "\")' title='删除行'>";

	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	// var tree = new Ext.ux.tree.MyTree({
	// url :
	// 'index1.php?model=product_licensetree_licensetree&action=listByParent&parentId=',
	// rootVisible : false,
	// checkModel : 'cascade'
	// });
	// new Ext.ux.combox.ComboBoxCheckTree({
	// applyTo : 'licenseNodeName' + mycount,
	// hiddenField : 'licenseNodeId' + mycount,
	// listWidth : 300,
	// tree : tree
	// // keyUrl : c.keyUrl
	// });

	// license类型
	var licensetypeRecords = [];
	licensetypeStore.each(function(record) {
				licensetypeRecords.push(record.copy());
			});
	var ls = new Ext.data.Store();
	ls.add(licensetypeRecords);
	new Ext.ux.form.MultiSelect({
				applyTo : 'licenseType' + mycount,
				hiddenFieldId : 'licenseinput' + mycount,
				store : ls,
				displayField : 'typeName',
				valueField : "id",
				mode : 'local',
				triggerAction : 'all'
			})
}

/** ***************产品清单添加硬件示例1***************************** */
function hard_add(myequ, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myequ = document.getElementById(myequ);
	i = myequ.rows.length;
	oTR = myequ.insertRow([i]);
	oTR.align = "center";
	oTR.height = "28px";
	oTL0 = oTR.insertCell([0]);
	oTL0.colspan = "11";
	if (i % 2 == 1)
		myclass = "TableLine2";
	else
		myclass = "TableLine1";
	oTL0.innerHTML = "<table bgcolor='#9FBFE3' width='100%' id='equT"
			+ mycount
			+ "' cellspacing='1' cellpadding='0'>"
			+ "<tr height='28' class='"
			+ myclass
			+ "'>"
			+ "\n<td nowrap align='center' width='5%'>"
			+ "<label id='equlabel"
			+ mycount
			+ "' >"
			+ i
			+ "</label><input type='hidden' id='elist_"
			+ mycount
			+ "' name='sales[equipment]["
			+ mycount
			+ "][countNumber]' value='1'/>"
			+ "</td>"
			+ "<td nowrap align='center' width='10%'>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][productNumber]' id='EquId"
			+ mycount
			+ "' value='' size='11'><input type='hidden' name='sales[equipment]["
			+ mycount
			+ "][ptype]' value='hard'/>"
			+ "</td>"
			+ "<td nowrap align='center' ondblClick=\"LoadWindowProPre('"
			+ mycount
			+ "')\">"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][productName]' id='EquName"
			+ mycount
			+ "' size='24'>"
			+ "</td>"
			+ "<td nowrap align='center' width='10%'>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][productModel]' id='EquModel"
			+ mycount
			+ "' value='' size='11'>"
			+ "</td>"
			+ "<td nowrap align='center' width='6%'>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][amount]' id='EquAmount"
			+ mycount
			+ "' value='' size='5' maxlength='40'/>"
			+ "</td>"
			+ "<td nowrap align='center'  width='6%'>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][price]' id='EquPrice"
			+ mycount
			+ "' value='' size='5' maxlength='40'/>"
			+ "</td>"
			+ "<td nowrap align='center' width='6%'>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][countMoney]' id='EquAllMoney"
			+ mycount
			+ "' value='' size='5' maxlength='40'/>"
			+ "</td>"
			+ "<td align='center' width='13%'>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][projArraDate]' id='EquDeliveryDT"
			+ mycount
			+ "' size='12' />"
			+ "</td>"
			+ "<td nowrap title='点击查看License申请单' align='center' width='10%' onclick='onclickMission(this);'>"
			+ "<input type='hidden' name='sales[licenselist]["
			+ mycount
			+ "][licenseNodeId]' value='' />"
			+ "<select name='sales[equipment]["
			+ mycount
			+ "][licenseType]' id='selector"
			+ mycount
			+ "' onchange='lissel(this,"
			+ mycount
			+ ")'>"
			+ "<option value=''>无</option>"
			+ "<option value='Pioneer'>Pioneer</option>"
			+ "<option value='Navigator'>Navigator</option>"
			+ "<option value='Walktour'>Walktour</option>"
			+ "<option value='RCU'>RCU</option>"
			+ "</select>"
			+ "<input type='text' name='sales[equipment]["
			+ mycount
			+ "][licenseId]' id='selInput"
			+ mycount
			+ "' class='BigInput' size='12' style='display:none' />"
			+ "</td>"
			+ "<td nowrap align='center' width='10%'>"
			+ "<select name='sales[equipment]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>"
			+ "<option value='半年'>半年</option>"
			+ "<option value='一年'>一年</option>"
			+ "<option value='两年'>两年</option>"
			+ "</select>"
			+ "</td>"
			+ "<td nowrap align='center' width='5%'>"
			+ "<img src='images/closeDiv.gif' onclick=\"equmydel(this,'myequ')\" title='删除行'>"
			+ "</td>" + "</tr>" + "</table>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

// *******************隐藏计划********************************
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

