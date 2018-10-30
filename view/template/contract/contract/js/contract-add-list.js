var proLineArr = [];

// 计算方法
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
		|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// 获取当前数
		var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// 获取当前单价
		var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// 计算本行金额 - 不含税
		var thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}
//联系人从表
function linkmanList(customerId) {
// 客户联系人
	$("#linkmanListInfo").yxeditgrid({
		objName: 'contract[linkman]',
		isAddOneRow: false,
		tableClass: 'form_in_table',
		colModel: [{
		// 	display: '客户联系人',
		// 	name: 'linkmanName',
		// 	tclass: 'txt',
		// 	process: function($input, rowData) {
		// 		var rowNum = $input.data("rowNum");
		// 		var g = $input.data("grid");
		// 		$input.yxcombogrid_linkman({
		// 			hiddenId: 'linkmanListInfo_cmp_linkmanId' + rowNum,
		// 			isFocusoutCheck: false,
		// 			gridOptions: {
		// 				showcheckbox: false,
		// 				param: {
		// 					'customerId': customerId
		// 				},
		// 				event: {
		// 					row_dblclick: (function(rowNum) {
		// 						return function(e, row, rowData) {
		// 							var $telephone = g.getCmpByRowAndCol(rowNum, 'telephone');
		// 							$telephone.val(rowData.mobile);
		// 							var $QQ = g.getCmpByRowAndCol(rowNum, 'QQ');
		// 							$QQ.val(rowData.QQ);
		// 							var $email = g.getCmpByRowAndCol(rowNum, 'Email');
		// 							$email.val(rowData.email);
		// 						}
		// 					})(rowNum)
		// 				}
		// 			}
		// 		});
		// 	}
		// }, {
		// 	display: '联系人ID',
		// 	name: 'linkmanId',
		// 	type: 'hidden'
		// },{
            display: '客户联系人',
            name: 'linkmanName',
			tclass: 'txt'
		}, {
			display: '电话',
			name: 'telephone',
			tclass: 'txt'
		}, {
			display: '职位',
			name: 'position',
			tclass: 'txt'
		}, {
			display: '邮箱',
			name: 'Email',
			tclass: 'txt'
		}, {
			display: '备注',
			name: 'remark',
			tclass: 'txt'
		}],
		event: {
			'clickAddRow': function(e, rowNum, g) {
				var customerId = $("#customerId").val();
				if (customerId == '') {
					alert("请选择客户信息");
					g.removeRow(rowNum);
				}
			}
		}

	});

	setTimeout(
		function(){
			var length = $("#linkmanListInfo").yxeditgrid("getCmpByCol", "telephone").length;
			if(length <= 0){
				$("#linkmanListInfo").yxeditgrid('addRow',1);
			}
		}, 300
	);
}

// 单独封装产品选择
(function($) {
	// 产品清单
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'contract[product]',
		tableClass: 'form_in_table',
		colModel: [{
			display: '产品线',
			name: 'newProLineName',
			tclass: 'readOnlyTxtNormal',
			width: 80,
			readonly: true
		}, {
			display: '产品线编号',
			name: 'newProLineCode',
			type: 'hidden'
		}, {
			display: '执行区域',
			name: 'exeDeptId',
			type: 'select',
			emptyOption : true,
			datacode: 'GCSCX',
			event: {
				change: function () {
					var rowNum = $(this).data("rowNum");
					// 只更新该行的执行区域，不影响之前产品的执行区域
					var exeDeptObj = $("#productInfo_cmp_exeDeptId" + rowNum);
					// 更新所有产品的执行区域
					// console.log($(this).val());
					if(exeDeptObj.length > 0){
						exeDeptObj.each(function(){
							var exeDeptName = $(this).find("option:[value='"+ $(this).val() + "']").text();
							// console.log(exeDeptName);
							$('#productInfo_cmp_exeDeptName'+rowNum).val(exeDeptName);
						});
					}
				}
			}
		}, {
			display: '执行区域Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '产品类型',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			readonly: true
		}, {
			display: '产品类型id',
			name: 'proTypeId',
			type: 'hidden'
		}, {
			display: 'proExeDeptId',
			name: 'proExeDeptId',
			type: 'hidden'
		}, {
			display: 'proExeDeptName',
			name: 'proExeDeptName',
			type: 'hidden'
		}, {
			display: 'newExeDeptCode',
			name: 'newExeDeptCode',
			type: 'hidden'
		}, {
			display: 'newExeDeptName',
			name: 'newExeDeptName',
			type: 'hidden'
		}, {
			display: '产品名称',
			name: 'conProductName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '产品Id',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '产品描述',
			name: 'conProductDes',
			tclass: 'txt'
		}, {
			display: '数量',
			name: 'number',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '单价',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '金额',
			name: 'money',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '加密配置Id',
			name: 'license',
			type: 'hidden'
		}, {
			display: '产品配置Id',
			name: 'deploy',
			type: 'hidden'
		}, {
			name: 'deployButton',
			display: '产品配置',
			type: 'statictext',
			event: {
				click: function(e) {
					var rowNum = $(this).data("rowNum");
					// 缓存产品信息
					var conProductId = $("#productInfo_cmp_conProductId" + rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName" + rowNum).val();
					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();

					if (conProductId == "") {
						alert('请先选择相关产品!');
						return false;
					} else {
						if (deploy == "") {
							var url = "?model=goods_goods_properties&action=toChoose"
									+ "&productInfoId="
									+ "productInfo_cmp_deploy"
									+ rowNum
									+ "&goodsId="
									+ conProductId
									+ "&goodsName="
									+ conProductName
									+ "&rowNum="
									+ rowNum
									+ "&componentId=productInfo"
								;
							window.open(url, '',
								'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
						} else {
							var url = "?model=goods_goods_properties&action=toChooseAgain"
									+ "&productInfoId="
									+ "productInfo_cmp_deploy"
									+ rowNum
									+ "&goodsId="
									+ conProductId
									+ "&goodsName="
									+ conProductName
									+ "&cacheId="
									+ deploy
									+ "&rowNum="
									+ rowNum
									+ "&componentId=productInfo"
								;

							window.open(url, '',
								'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
						}
					}

				}
			},
			html: '<input type="button" value="产品配置" class="txt_btn_a"/>'
		}, {
			display: '产品物料唯一Id',
			name: 'onlyProductId',
			type: 'hidden'
		}],
		isAddOneRow: false,
		event: {
			clickAddRow: function(e, rowNum, g) {
				rowNum = g.allAddRowNum;
				//是否框架合同，销售类合同是框架合同允许合同金额为0
				var isFrame = $("#contractType").val() == 'HTLX-XSHT' && $("#isFrame").val() == '1' ? '1' : '0';
				var url = "?model=contract_contract_product&action=toProductIframe&isCon=1"
					+ "&componentId=productInfo"
					+ "&rowNum="
					+ rowNum
					+ "&isFrame="
					+ isFrame;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function() {
				initCacheInfo();
			},
			removeRow: function(e, rowNum, rowData) {
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
					var matArr = $("#materialInfo").yxeditgrid("getCmpByCol", "onlyProductId");
					matArr.each(function() {
						if ($(this).val() == rowData.onlyProductId) {
							var $tr = $(this).parent("td").parent("tr");
							$tr.remove();
						}
					});
					createProArr();
				}
			}
		},
		addBtnClick: function() {
			return false;
		},
		setData: function(returnValue, rowNum) {
			var g = this;
			if (returnValue) {
				// 新增一行
				g.addRow(g.allAddRowNum);
				//产品
				var proArr = returnValue[0];
				g.setRowColValue(rowNum, "proType", proArr.proType);
				g.setRowColValue(rowNum, "proTypeId", proArr.proTypeId);
				g.setRowColValue(rowNum, "proExeDeptId", proArr.proExeDeptId);
				g.setRowColValue(rowNum, "proExeDeptName", proArr.proExeDeptName);
				g.setRowColValue(rowNum, "exeDeptName", proArr.proExeDeptName);
				g.setRowColValue(rowNum, "newExeDeptCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", proArr.newExeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", proArr.newExeDeptName);
//				initExeDeptByRow(g, rowNum);
				setProExeDeptByRow(rowNum);

				g.setRowColValue(rowNum, "conProductId", proArr.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", proArr.goodsName, true);
				g.setRowColValue(rowNum, "number", proArr.number, true);
				g.setRowColValue(rowNum, "price", proArr.price, true);
				g.setRowColValue(rowNum, "money", proArr.money, true);
				g.setRowColValue(rowNum, "warrantyPeriod", proArr.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", proArr.cacheId, true);
				g.setRowColValue(rowNum, "license", proArr.licenseId, true);
				g.setRowColValue(rowNum, "onlyProductId", proArr.onlyProductId, true);
				proArr.deploy = proArr.cacheId;
				var $tr = g.getRowByRowNum(rowNum);
				$tr.data("rowData", proArr);
				//选择产品后动态渲染下面的配置单
				getCacheInfo(proArr.cacheId, rowNum);

				//物料
				var equArr = returnValue[1];
				if (typeof(equArr) != 'undefined') {
					var equLen = equArr.length;
					var equObj = $("#materialInfo");
					for (var i = 0; i < equLen; i++) {
						//重新获取行数
						var tbRowNum = equObj.yxeditgrid("getAllAddRowNum");
						//新增行
						equObj.yxeditgrid("addRow", tbRowNum);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productCode", equArr[i].productCode);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productName", equArr[i].productName);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productId", equArr[i].productId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "conProductId", equArr[i].conProductId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productModel", equArr[i].productModel);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "number", equArr[i].number);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "isAddFromConfig", equArr[i].isAddFromConfig);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "onlyProductId", equArr[i].onlyProductId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "price", equArr[i].price);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "money", equArr[i].money);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "license", equArr[i].license);
					}
				}
				createProArr();
			}
		},
		reloadCache: function(cacheId, rowNum) {
			if (cacheId) {
				// 根据cacheId获取关联的物料信息
				var thisGrid = $("#productInfo");
				var equObj = $("#materialInfo");
				var cacheEqu = $.ajax({
					type: "POST",
					url: 'index1.php?model=common_contract_allsource&action=newGetProductEqu',
					data: {
						deloy: cacheId,
						parentEquId: 0
					},
					async: false
				}).responseText;
				var cacheEquObj = (cacheEqu && cacheEqu != 'null')? eval("("+cacheEqu+")") : {};
				var tbRowNum = equObj.yxeditgrid("getAllAddRowNum");
				var proOnlyId = $("#productInfo_cmp_onlyProductId" + rowNum).val();

				// 干掉此产品下的所有配置项关联的物料信息
				for(var i = 0;i < tbRowNum;i++){
					var onlyProductId = $("#materialInfo_cmp_onlyProductId"+i).val();
					var isAddFromConfig = $("#materialInfo_cmp_isAddFromConfig"+i).val();
					if(onlyProductId == proOnlyId && isAddFromConfig == 1){
						$("#materialInfo_cmp_isAddFromConfig"+i).parents(".tr_even").remove();
					}
				}

				$.each(cacheEquObj,function(i,item){
					// 新增的物料
					equObj.yxeditgrid("addRow", tbRowNum);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "id", '');
					equObj.yxeditgrid("setRowColValue", tbRowNum, "productCode", item.productCode);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "productName", item.productName);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "productId", item.productId);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "conProductId", '');
					equObj.yxeditgrid("setRowColValue", tbRowNum, "productModel", item.productModel);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "number", item.number);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "isDel", 0);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "isAddFromConfig", 1);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "onlyProductId", proOnlyId);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "price", item.price);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "money", item.money);
					equObj.yxeditgrid("setRowColValue", tbRowNum, "license", item.license);
					tbRowNum += 1;
				});

			    $("#goodsDetail_" + cacheId).remove();
			    //选择产品后动态渲染下面的配置单
			    getCacheInfo(cacheId, rowNum);
			}
		}
	});
})(jQuery);

// 合同新增从表
$(function() {
	linkmanList();

	// 产品清单
	$("#productInfo").productInfoGrid();

	$("#materialInfo").yxeditgrid({
		objName: 'contract[material]',
		tableClass: 'form_in_table',
		isAddAndDel: false,
		isAddOneRow: false,
		colModel: [{
			display: 'originalId',
			name: 'originalId',
			type: 'hidden'
		}, {
			display: '配置带出的物料标示',
			name: 'isAddFromConfig',
			type: 'hidden'
		}, {
			display: '物料编号',
			name: 'productCode',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '物料名称',
			name: 'productName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '产品Id',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '型号/版本',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtNormal'
		}, {
			display: '数量',
			name: 'number',
			tclass: 'txtshort',
			event: {
				change: function() {
					var number = $(this).val();
					if(!isNum(number)){
						alert("请输入正整数！");
						$(this).val(1);
						return false;
					}
					var g = $(this).data("grid");
					var rowNum = $(this).data("rowNum");
					g.getCmpByRowAndCol(rowNum,'money').val(g.getCmpByRowAndCol(rowNum,'price').val()*number);
				}
			}
		}, {
			display: '产品物料唯一Id',
			name: 'onlyProductId',
			type: 'hidden'
		}, {
			display: '单价',
			name: 'price',
			type: 'hidden'
		}, {
			display: '金额',
			name: 'money',
			type: 'hidden'
		}, {
			display: '加密配置ID',
			name: 'license',
			type: 'hidden'
		}]
	});

	//借试用转销售
	$("#borrowConEquInfo").yxeditgrid({
		objName: 'contract[equ]',
		isAddOneRow: false,
		tableClass: 'form_in_table',
		colModel: [{
			display: '物料Id',
			name: 'productId',
			tclass: 'txt',
			type: 'hidden'
		}, {
			display: '归属产品',
			name: 'onlyProductId',
			type: 'select',
			tclass: 'txt',
			width: 100,
			options: [
				{
					name: '..请选择产品..',
					value: ''
				}
			],
			event: {
				change: function() {
					rowNum = $(this).data("rowNum");//行号
				}
			}
		}, {
			display: '归属产品名称',
			name: 'conProductNameOriginal',
			tclass: 'txt',
			type: 'hidden'
		}, {
			display: '物料编号',
			name: 'productCode',
			tclass: 'readOnlyTxtItem',
			readonly: 'readonly'
		}, {
			display: '物料名称',
			name: 'productName',
			tclass: 'readOnlyTxtNormal',
			readonly: 'readonly'
		}, {
			display: '型号/版本',
			name: 'productModel',
			tclass: 'readOnlyTxtItem',
			readonly: 'readonly'
		}, {
			display: '数量',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '单价',
			name: 'price',
			tclass: 'txtshort',
			type: 'hidden',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '金额',
			name: 'money',
			tclass: 'txtshort',
			type: 'hidden',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '保修期',
			name: 'warrantyPeriod',
			tclass: 'txtshort'
		}, {
			display: '借试用表Id',
			name: 'toBorrowId',
			tclass: 'txtshort',
			type: 'hidden'
		}, {
			display: '借试用从ID',
			name: 'toBorrowequId',
			tclass: 'txtshort',
			type: 'hidden'
		}, {
			name: 'serialId',
			display: '序列号ID',
			type: 'hidden'
		}, {
			name: 'serialName',
			display: '序列号',
			tclass: 'readOnlyTxtNormal',
			readonly: 'readonly',
			process: function($input, rowData, $tr, grid) {
				if (typeof(rowData) != 'undefined') {
					var inputId = $input.attr('id');
					var rownum = $input.data('rowNum');// 第几行
					var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
					var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='选择序列号'>");
					$img.click(function(toBorrowId, productId, num, inputId, sid) {
						return function() {
							serialNum(toBorrowId, productId, num, inputId, sid);
						}
					}(rowData.toBorrowId, rowData.productId, rowData.number, inputId, sid));
					$input.before($img)
				}
			},
			event: {
				dblclick: function() {
					var serial = $(this).val();
					if (serial != "") {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
						+ serial
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
					}
				}
			}
		}],
		event: {
			'clickAddRow': function(e, rowNum, g) {
				var customerId = $("#customerId").val();
				var prinvipalId = $("#prinvipalId").val();
				var checkIds = "";
				var ids = $("#ids").val();
				if (customerId == '' || prinvipalId == '') {
					alert("请确认是否选择了客户或负责人信息");
					g.removeRow(rowNum);
				} else {
					for (var j = 0; j < rowNum; j++) {
						if ($("#borrowConEquInfo_cmp_toBorrowequId" + j).val() != "undefined") {
							checkIds += $("#borrowConEquInfo_cmp_toBorrowequId" + j).val() + ","
						}
					}
					var url = "?model=projectmanagent_borrow_borrow&action=borrowTurnInfo&showAll&customerId=" + customerId +
						"&salesNameId=" + prinvipalId + "&checkIds=" + checkIds;
					var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");
					if (returnValue) {
						//循环拆分数组
						g.removeRow(rowNum);
						var num = g.getCurRowNum();
						var rowId = rowNum;
						$.each(returnValue,function(index,item){
							var canExeNum = item.executedNum - item.backNum;
							var outJson = {
								"productId": item.productId,
								"productCode": item.productNo,
								"productName": item.productName,
								"productModel": item.productModel,
								"number": canExeNum,
								"price": item.price,
								"money": item.price * canExeNum,
								"warrantyPeriod": item.warrantyPeriod,
								"isBorrowToorder": 1,
								"toBorrowId": item.borrowId,
								"toBorrowequId": item.id
							};

							//插入数据
							g.addRow(rowId, outJson);

							// 带出已执行数量,并添加此行数量框的变动监听
							if(typeof(item.executedNum) != 'undefined'){
								var executedNum = item.executedNum;
								var exeNum = item.executedNum - item.backNum;
								var obj = $("#borrowConEquInfo_cmp_number" + rowId);
								obj.after("<input type='hidden' id='borrowConEquInfo_cmp_executedNum"+rowId+"' value='"+item.executedNum+"'/>");
								obj.change(function(){
									var inputVal = $(this).val();
									if(isNaN(inputVal) || parseInt(inputVal) <= 0){
										alert("请输入大于0的整数。");
										$(this).val(exeNum);
									}else if(parseInt(inputVal) > parseInt(exeNum)){
										alert("转销售数量请控制在可行性数量范围内。");
										$(this).val(exeNum);
									}
								});
							}
							rowId++;
							createProArr();
						});
						// for (var i = 0; i < returnValue.length + num; i++) {
						// 	var outJson = {
						// 		"productId": returnValue[i].productId,
						// 		"productCode": returnValue[i].productNo,
						// 		"productName": returnValue[i].productName,
						// 		"productModel": returnValue[i].productModel,
						// 		// "number": returnValue[i].number - returnValue[i].backNum,
						// 		"number": returnValue[i].executedNum, //数量默认带出已执行数量
						// 		"price": returnValue[i].price,
						// 		"money": returnValue[i].money,
						// 		"warrantyPeriod": returnValue[i].warrantyPeriod,
						// 		"isBorrowToorder": 1,
						// 		"toBorrowId": returnValue[i].borrowId,
						// 		"toBorrowequId": returnValue[i].id
						// 	};
						// 	console.log(returnValue);
						// 	//插入数据
						// 	if (rowNum > 0) {
						// 		g.addRow(rowNum, outJson);
						// 		rowNum++;
						// 	} else {
						// 		g.addRow(i, outJson);
						// 	}
                        //
						// 	// 带出已执行数量,并添加此行数量框的变动监听
						// 	if(typeof(returnValue[i].executedNum) != 'undefined'){
						// 		var executedNum = returnValue[i].executedNum;
						// 		var obj = $("#borrowConEquInfo_cmp_number" + i);
						// 		obj.after("<input type='hidden' id='borrowConEquInfo_cmp_executedNum"+i+"' value='"+returnValue[i].executedNum+"'/>");
						// 		obj.change(function(){
						// 			var inputVal = $(this).val();
						// 			if(isNaN(inputVal) || parseInt(inputVal) <= 0){
						// 				alert("请输入大于0的整数。");
						// 				$(this).val(executedNum);
						// 			}else if(parseInt(inputVal) > parseInt(executedNum)){
						// 				alert("物料申请数量不能大于已执行数量。");
						// 				$(this).val(executedNum);
						// 			}
						// 		});
						// 	}
						// 	createProArr();
						// }
					} else {
						g.removeRow(rowNum);
					}
				}
				return false;
			},
			'removeRow': function(e, rowNum, rowData) {
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		}

	});
	// 选择序列号
	function serialNum(borrowId, productId, num, inputId, sid) {
		var amount = $("#bornumber" + num).val();
		showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNum&borrowId='
		+ borrowId
		+ '&productId='
		+ productId
		+ '&num='
		+ num
		+ '&amount='
		+ num
		+ '&inputId='
		+ inputId
		+ '&sid='
		+ sid
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
	}


	//收开计划
	$("#financialplanInfo").yxeditgrid({
		objName: 'contract[financialplan]',
		isAddOneRow: false,
		tableClass: 'form_in_table',
		colModel: [{
			display: '日期',
			name: 'planDate',
			type: 'date'
		}, {
			display: '开票金额',
			name: 'invoiceMoney',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '收款金额',
			name: 'incomeMoney',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '备注',
			name: 'remark',
			tclass: 'txtlong'
		}]
	});

});

//构建归属产品下拉数组
function createProArr() {
	var itemArr = $("#productInfo").productInfoGrid("getCmpByCol", "conProductName");
	if (itemArr.length > 0) {
		var returnArr = [];
		//循环
		itemArr.each(function() {
			var rowNum = $(this).data("rowNum");
			var rowArr = $("#productInfo").productInfoGrid("getRowByRowNum", rowNum);
			rowArr.each(function() {
				var beforeStr = "productInfo_cmp_";
				//借试用转销售的物料，只能挂在销售类产品下
				if($("#" + beforeStr + "proTypeId" + rowNum).val() == "11"){
					var equJson = {
						name: $("#" + beforeStr + "conProductName" + rowNum).val(),
						value: $("#" + beforeStr + "onlyProductId" + rowNum).val()
					};
					returnArr.push(equJson);
				}
			})
		});
	}
	proLineArr = returnArr;

	//借用物料
	var bowItemArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "productCode");
	if (bowItemArr.length > 0) {
		var returnArr = [];
		//循环
		bowItemArr.each(function() {
			var borowNum = $(this).data("rowNum");
			var borowArr = $("#borrowConEquInfo").yxeditgrid("getRowByRowNum", borowNum);
			borowArr.each(function() {
				var obj = $("#borrowConEquInfo_cmp_onlyProductId" + borowNum);
				var optiontText = $("#borrowConEquInfo_cmp_conProductNameOriginal" + borowNum).val();
				obj.empty();
				var obj = document.getElementById("borrowConEquInfo_cmp_onlyProductId" + borowNum);
				obj.add(new Option("..请选择产品..", " "));
				if (typeof(proLineArr) != 'undefined') {
					if (proLineArr.length > 0) {
						for (var i = 0; i < proLineArr.length; i++) {
							if(optiontText == proLineArr[i]['name']){
								obj.add(new Option("" + proLineArr[i]['name'] + "", "" + proLineArr[i]['value'] + "",true,true));
							}else{
								obj.add(new Option("" + proLineArr[i]['name'] + "", "" + proLineArr[i]['value'] + ""));
							}
						}
					}
				}
			})
		});
	}
}