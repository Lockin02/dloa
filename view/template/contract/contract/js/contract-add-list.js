var proLineArr = [];

// ���㷽��
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
		|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// ��ȡ��ǰ��
		var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		var thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}
//��ϵ�˴ӱ�
function linkmanList(customerId) {
// �ͻ���ϵ��
	$("#linkmanListInfo").yxeditgrid({
		objName: 'contract[linkman]',
		isAddOneRow: false,
		tableClass: 'form_in_table',
		colModel: [{
		// 	display: '�ͻ���ϵ��',
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
		// 	display: '��ϵ��ID',
		// 	name: 'linkmanId',
		// 	type: 'hidden'
		// },{
            display: '�ͻ���ϵ��',
            name: 'linkmanName',
			tclass: 'txt'
		}, {
			display: '�绰',
			name: 'telephone',
			tclass: 'txt'
		}, {
			display: 'ְλ',
			name: 'position',
			tclass: 'txt'
		}, {
			display: '����',
			name: 'Email',
			tclass: 'txt'
		}, {
			display: '��ע',
			name: 'remark',
			tclass: 'txt'
		}],
		event: {
			'clickAddRow': function(e, rowNum, g) {
				var customerId = $("#customerId").val();
				if (customerId == '') {
					alert("��ѡ��ͻ���Ϣ");
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

// ������װ��Ʒѡ��
(function($) {
	// ��Ʒ�嵥
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'contract[product]',
		tableClass: 'form_in_table',
		colModel: [{
			display: '��Ʒ��',
			name: 'newProLineName',
			tclass: 'readOnlyTxtNormal',
			width: 80,
			readonly: true
		}, {
			display: '��Ʒ�߱��',
			name: 'newProLineCode',
			type: 'hidden'
		}, {
			display: 'ִ������',
			name: 'exeDeptId',
			type: 'select',
			emptyOption : true,
			datacode: 'GCSCX',
			event: {
				change: function () {
					var rowNum = $(this).data("rowNum");
					// ֻ���¸��е�ִ�����򣬲�Ӱ��֮ǰ��Ʒ��ִ������
					var exeDeptObj = $("#productInfo_cmp_exeDeptId" + rowNum);
					// �������в�Ʒ��ִ������
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
			display: 'ִ������Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			readonly: true
		}, {
			display: '��Ʒ����id',
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
			display: '��Ʒ����',
			name: 'conProductName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '��ƷId',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			name: 'conProductDes',
			tclass: 'txt'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '����',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '���',
			name: 'money',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '��������Id',
			name: 'license',
			type: 'hidden'
		}, {
			display: '��Ʒ����Id',
			name: 'deploy',
			type: 'hidden'
		}, {
			name: 'deployButton',
			display: '��Ʒ����',
			type: 'statictext',
			event: {
				click: function(e) {
					var rowNum = $(this).data("rowNum");
					// �����Ʒ��Ϣ
					var conProductId = $("#productInfo_cmp_conProductId" + rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName" + rowNum).val();
					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();

					if (conProductId == "") {
						alert('����ѡ����ز�Ʒ!');
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
			html: '<input type="button" value="��Ʒ����" class="txt_btn_a"/>'
		}, {
			display: '��Ʒ����ΨһId',
			name: 'onlyProductId',
			type: 'hidden'
		}],
		isAddOneRow: false,
		event: {
			clickAddRow: function(e, rowNum, g) {
				rowNum = g.allAddRowNum;
				//�Ƿ��ܺ�ͬ���������ͬ�ǿ�ܺ�ͬ�����ͬ���Ϊ0
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
				// ����һ��
				g.addRow(g.allAddRowNum);
				//��Ʒ
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
				//ѡ���Ʒ��̬��Ⱦ��������õ�
				getCacheInfo(proArr.cacheId, rowNum);

				//����
				var equArr = returnValue[1];
				if (typeof(equArr) != 'undefined') {
					var equLen = equArr.length;
					var equObj = $("#materialInfo");
					for (var i = 0; i < equLen; i++) {
						//���»�ȡ����
						var tbRowNum = equObj.yxeditgrid("getAllAddRowNum");
						//������
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
				// ����cacheId��ȡ������������Ϣ
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

				// �ɵ��˲�Ʒ�µ����������������������Ϣ
				for(var i = 0;i < tbRowNum;i++){
					var onlyProductId = $("#materialInfo_cmp_onlyProductId"+i).val();
					var isAddFromConfig = $("#materialInfo_cmp_isAddFromConfig"+i).val();
					if(onlyProductId == proOnlyId && isAddFromConfig == 1){
						$("#materialInfo_cmp_isAddFromConfig"+i).parents(".tr_even").remove();
					}
				}

				$.each(cacheEquObj,function(i,item){
					// ����������
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
			    //ѡ���Ʒ��̬��Ⱦ��������õ�
			    getCacheInfo(cacheId, rowNum);
			}
		}
	});
})(jQuery);

// ��ͬ�����ӱ�
$(function() {
	linkmanList();

	// ��Ʒ�嵥
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
			display: '���ô��������ϱ�ʾ',
			name: 'isAddFromConfig',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productCode',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '��������',
			name: 'productName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '��ƷId',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			readonly: true,
			tclass: 'readOnlyTxtNormal'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			event: {
				change: function() {
					var number = $(this).val();
					if(!isNum(number)){
						alert("��������������");
						$(this).val(1);
						return false;
					}
					var g = $(this).data("grid");
					var rowNum = $(this).data("rowNum");
					g.getCmpByRowAndCol(rowNum,'money').val(g.getCmpByRowAndCol(rowNum,'price').val()*number);
				}
			}
		}, {
			display: '��Ʒ����ΨһId',
			name: 'onlyProductId',
			type: 'hidden'
		}, {
			display: '����',
			name: 'price',
			type: 'hidden'
		}, {
			display: '���',
			name: 'money',
			type: 'hidden'
		}, {
			display: '��������ID',
			name: 'license',
			type: 'hidden'
		}]
	});

	//������ת����
	$("#borrowConEquInfo").yxeditgrid({
		objName: 'contract[equ]',
		isAddOneRow: false,
		tableClass: 'form_in_table',
		colModel: [{
			display: '����Id',
			name: 'productId',
			tclass: 'txt',
			type: 'hidden'
		}, {
			display: '������Ʒ',
			name: 'onlyProductId',
			type: 'select',
			tclass: 'txt',
			width: 100,
			options: [
				{
					name: '..��ѡ���Ʒ..',
					value: ''
				}
			],
			event: {
				change: function() {
					rowNum = $(this).data("rowNum");//�к�
				}
			}
		}, {
			display: '������Ʒ����',
			name: 'conProductNameOriginal',
			tclass: 'txt',
			type: 'hidden'
		}, {
			display: '���ϱ��',
			name: 'productCode',
			tclass: 'readOnlyTxtItem',
			readonly: 'readonly'
		}, {
			display: '��������',
			name: 'productName',
			tclass: 'readOnlyTxtNormal',
			readonly: 'readonly'
		}, {
			display: '�ͺ�/�汾',
			name: 'productModel',
			tclass: 'readOnlyTxtItem',
			readonly: 'readonly'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '����',
			name: 'price',
			tclass: 'txtshort',
			type: 'hidden',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '���',
			name: 'money',
			tclass: 'txtshort',
			type: 'hidden',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '������',
			name: 'warrantyPeriod',
			tclass: 'txtshort'
		}, {
			display: '�����ñ�Id',
			name: 'toBorrowId',
			tclass: 'txtshort',
			type: 'hidden'
		}, {
			display: '�����ô�ID',
			name: 'toBorrowequId',
			tclass: 'txtshort',
			type: 'hidden'
		}, {
			name: 'serialId',
			display: '���к�ID',
			type: 'hidden'
		}, {
			name: 'serialName',
			display: '���к�',
			tclass: 'readOnlyTxtNormal',
			readonly: 'readonly',
			process: function($input, rowData, $tr, grid) {
				if (typeof(rowData) != 'undefined') {
					var inputId = $input.attr('id');
					var rownum = $input.data('rowNum');// �ڼ���
					var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
					var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='ѡ�����к�'>");
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
					alert("��ȷ���Ƿ�ѡ���˿ͻ���������Ϣ");
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
						//ѭ���������
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

							//��������
							g.addRow(rowId, outJson);

							// ������ִ������,����Ӵ���������ı䶯����
							if(typeof(item.executedNum) != 'undefined'){
								var executedNum = item.executedNum;
								var exeNum = item.executedNum - item.backNum;
								var obj = $("#borrowConEquInfo_cmp_number" + rowId);
								obj.after("<input type='hidden' id='borrowConEquInfo_cmp_executedNum"+rowId+"' value='"+item.executedNum+"'/>");
								obj.change(function(){
									var inputVal = $(this).val();
									if(isNaN(inputVal) || parseInt(inputVal) <= 0){
										alert("���������0��������");
										$(this).val(exeNum);
									}else if(parseInt(inputVal) > parseInt(exeNum)){
										alert("ת��������������ڿ�����������Χ�ڡ�");
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
						// 		"number": returnValue[i].executedNum, //����Ĭ�ϴ�����ִ������
						// 		"price": returnValue[i].price,
						// 		"money": returnValue[i].money,
						// 		"warrantyPeriod": returnValue[i].warrantyPeriod,
						// 		"isBorrowToorder": 1,
						// 		"toBorrowId": returnValue[i].borrowId,
						// 		"toBorrowequId": returnValue[i].id
						// 	};
						// 	console.log(returnValue);
						// 	//��������
						// 	if (rowNum > 0) {
						// 		g.addRow(rowNum, outJson);
						// 		rowNum++;
						// 	} else {
						// 		g.addRow(i, outJson);
						// 	}
                        //
						// 	// ������ִ������,����Ӵ���������ı䶯����
						// 	if(typeof(returnValue[i].executedNum) != 'undefined'){
						// 		var executedNum = returnValue[i].executedNum;
						// 		var obj = $("#borrowConEquInfo_cmp_number" + i);
						// 		obj.after("<input type='hidden' id='borrowConEquInfo_cmp_executedNum"+i+"' value='"+returnValue[i].executedNum+"'/>");
						// 		obj.change(function(){
						// 			var inputVal = $(this).val();
						// 			if(isNaN(inputVal) || parseInt(inputVal) <= 0){
						// 				alert("���������0��������");
						// 				$(this).val(executedNum);
						// 			}else if(parseInt(inputVal) > parseInt(executedNum)){
						// 				alert("���������������ܴ�����ִ��������");
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
	// ѡ�����к�
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


	//�տ��ƻ�
	$("#financialplanInfo").yxeditgrid({
		objName: 'contract[financialplan]',
		isAddOneRow: false,
		tableClass: 'form_in_table',
		colModel: [{
			display: '����',
			name: 'planDate',
			type: 'date'
		}, {
			display: '��Ʊ���',
			name: 'invoiceMoney',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '�տ���',
			name: 'incomeMoney',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '��ע',
			name: 'remark',
			tclass: 'txtlong'
		}]
	});

});

//����������Ʒ��������
function createProArr() {
	var itemArr = $("#productInfo").productInfoGrid("getCmpByCol", "conProductName");
	if (itemArr.length > 0) {
		var returnArr = [];
		//ѭ��
		itemArr.each(function() {
			var rowNum = $(this).data("rowNum");
			var rowArr = $("#productInfo").productInfoGrid("getRowByRowNum", rowNum);
			rowArr.each(function() {
				var beforeStr = "productInfo_cmp_";
				//������ת���۵����ϣ�ֻ�ܹ����������Ʒ��
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

	//��������
	var bowItemArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "productCode");
	if (bowItemArr.length > 0) {
		var returnArr = [];
		//ѭ��
		bowItemArr.each(function() {
			var borowNum = $(this).data("rowNum");
			var borowArr = $("#borrowConEquInfo").yxeditgrid("getRowByRowNum", borowNum);
			borowArr.each(function() {
				var obj = $("#borrowConEquInfo_cmp_onlyProductId" + borowNum);
				var optiontText = $("#borrowConEquInfo_cmp_conProductNameOriginal" + borowNum).val();
				obj.empty();
				var obj = document.getElementById("borrowConEquInfo_cmp_onlyProductId" + borowNum);
				obj.add(new Option("..��ѡ���Ʒ..", " "));
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