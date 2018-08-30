
// 物料确认查看
var changeDetailObj = {};
$(function() {
	equConfig.type = "view";
	var rowNum = $('#rowNum').val();
	var contractId = $("#contractId").val();
	var key = "detailId";
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i);
	}
	var newEquRowNum = rowNum*1+1;
	getNoGoodsProducts(newEquRowNum);

});



/**
 *
 * 物料确认公用js(新增，编辑，变更，查看)
 */
var equConfig = {
	type : ''// add edit change view
};// 配置属性


// 回调插入产品信息 － 单条
function getCacheInfo(cacheId, rowNum) {
	$.ajax({
		type : "POST",
		url : "?model=goods_goods_goodscache&action=getCacheConfig",
		data : {
			"id" : cacheId
		},
		async : false,
		success : function(data) {
			if (data != "") {
				$("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
			}

		}
	});
}

// 加载页面时渲染产品配置信息
function initCacheInfo() {
	// 缓存表格对象
	var thisGrid = $("#productInfo");

	var colObj = thisGrid.yxeditgrid("getCmpByCol", "deploy");
	colObj.each(function(i, n) {
		getCacheInfo(this.value, i);
	});

}

/**
 * 加载某个产品的物料配件信息
 *
 * @param {}
 *            goodsId 产品
 * @param {}
 *            goodsRowNum
 */
function initProductConfigs(goodsId, goodsRowNum,contractId) {
	// 缓存表格对象
	if(goodsId == '0'){//用于借用转销售物料
		var $productGrid = $("#productInfo");
	}else{
		var $productGrid = $("#productInfo" + goodsRowNum);
	}
	// thisGrid.html("");
	var isSubAppChange = $("#isSubAppChange").val();
	if(isSubAppChange == '1'){
	    var equIdObj = $productGrid.yxeditgrid("getCmpByCol", "originalId");// 清单id
	    var equIdObjA = $productGrid.yxeditgrid("getCmpByCol", "id");// 清单id
	    var equIdArr = [];//针对变更业务，用来缓存已经渲染过配件的物料所在行的id
	}else{
	    var equIdObj = $productGrid.yxeditgrid("getCmpByCol", "id");// 清单id
	}
	var productObj = $productGrid.yxeditgrid("getCmpByCol", "productId");// 物料id
	var numObj = $productGrid.yxeditgrid("getCmpByCol", "number");// 物料数量
	var equSize = equIdObj.size();
	productObj.each(function(i, n) {
		var productId = this.value;
		if (equSize > 0) {
			if(equIdObj[i].value != '0' && equIdObj[i].value != ''){
			   getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i, equIdObj[i].value,false,contractId);
			   if(isSubAppChange == '1'){
				   equIdArr.push(this.id);
			   }
			}
		} else {
			getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i,"",false,contractId);
		}

	});
    //显示变更新增用-可能有bug后续在优化
	if(isSubAppChange == '1'){
		var equSizeA = equIdObjA.size();
		productObj.each(function(i, n) {
			if($.inArray(this.id,equIdArr) == -1){//没有渲染过配件的物料才需渲染
				var productId = this.value;
				if (equSizeA > 0) {
					if(equIdObjA[i].value != '0'){
					   getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
							i, equIdObjA[i].value,false,contractId);
					}
				} else {
					getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
							i,"",false,contractId);
				}
			}
		});
	}
}

/**
 * 加载某个物料的配件(用于物料确认)
 *
 * @param {}
 *            goodsId 产品id
 * @param {}
 *            productId 物料id
 * @param {}
 *            productNum 物料数量
 * @param {}
 *            goodsRowNum 产品行
 * @param {}
 *            productRowNum 物料行
 *
 */
function getProductConfig(goodsId, productId, productNum, goodsRowNum,
		productRowNum, equId, ifGetPro,contractId) {
	var url = "";
	var param = {};
	// 判断是编辑还是新增情况
	if (equConfig.type != 'add' && !ifGetPro && equId != '') {
		param.parentEquId = equId;
		param.contractId = contractId;
		if (equConfig.type != "view" || $("#isShowDel").val() == "false") {
			param.isDel = 0;
//			param.isTemp = 1;  //edit by zzx 2014年1月11日 13:59:04 配件显示不出来
		}
		url = "?model=contract_contract_equ&action=listJson";
	} else {
		param.productId = productId;
		url = "?model=stock_productinfo_configuration&action=getAccessForPro";
	}
	var data = $.ajax({
		type : "POST",
		url : url,
		data : param,
		async : false
	}).responseText;

	$("#goodsDetail_" + goodsId + "_" + productRowNum).remove();
	// productIdArr[rowNum]=productId;
	// tr.empty();
	if(strTrim(data)){
		var obj = eval("(" + data + ")");
		if (obj.length) {
			var $table = $("<table class='form_in_table' id='contractequ_$id'></table>");
			var $td = $("<td class='innerTd' colspan='20'></td>");
			var $tr = $("<tr id='goodsDetail_" + goodsId + "_" + productRowNum
					+ "'></tr>")
			$tr.append($td)
			$td.append($table);

			var $th = $('<tr class="main_tr_header1"><th width="30px"></th><th width="35px">序号</th><th>配件编码</th>'
					+ '<th>配件名称</th><th>版本型号</th><th>数量</th><th>保修期</th><th>交货期</th></tr>');
			$table.append($th);
			if(goodsId == '0'){//用于借用转销售物料
				var configtr = $("#productInfo table tr[rowNum="
						+ productRowNum + "]");
			}else{
				var configtr = $("#productInfo" + goodsRowNum + " table tr[rowNum="
						+ productRowNum + "]");
			}
			configtr.after($tr);
			var nametr = 'contract[detail][' + goodsRowNum + ']';
			$tr.attr("name", nametr);
			for (var i = 0; i < obj.length; i++) {
				var index = i + (productRowNum + 1) * 100;
				var name = 'contract[detail][' + goodsRowNum + '][' + index + ']';
				var p = obj[i];
				$ctr = $("<tr  class='tr_inner'></tr>");
				var imgStr = "";
				if (equConfig.type == 'view') {
					var key = p.id;
					if ($("#isTemp").val() == 1) {
						key = p.originalId;
					}
					var detail = changeDetailObj["d" + key];
					if (detail) {
						for (var i = 0; i < detail.length; i++) {
							var changeField = detail[i].changeField;
							p[changeField] = tranToChangeShow(detail[i].oldValue,
									p[detail[i].changeField]);
						}
					}

					if (p.changeTips == '1') {
						imgStr = '<img title="最新变更编辑的物料" src="images/changeedit.gif" />';
					} else if (p.changeTips == '2') {

						imgStr = '<img title="最新变更新增的物料" src="images/new.gif" />';
					} else if (p.changeTips == '3'&&p.isDel=='1') {
						imgStr = '<img title="最新变更删除的物料" src="images/changedelete.gif" />';
						$ctr.css("color", "#8B9CA4");
					} else if (p.changeTips == '0'&&p.isDel=='1') {
						$ctr.css("color", "#8B9CA4");
					}
				}
				$ctr.attr("index", index);
				if (equConfig.type != 'view') {
					$ctr
							.append("<td name='"
									+ name
									+ "'><img src='images/removeline.png' onclick='delConfig(this)' title='删除行'></td>");
				} else {
					$ctr.append("<td></td>");
				}
				$ctr.append("<td>" + (i + 1) + "</td>");

				var hide = '<input type="hidden" name="' + name
						+ '[parentRowNum]" value="'
						+ (productId + "_" + productRowNum)
						+ '"/><input type="hidden" name="' + name
						+ '[productId]" value="' + p.productId
						+ '"/><input type="hidden" name="' + name
						+ '[productCode]"  value="' + p.productCode
						+ '"/><input type="hidden" name="' + name
						+ '[productName]"  value="' + p.productName
						+ '"/><input type="hidden" name="' + name
						+ '[conProductId]"  value="' + goodsId
						+ '"/><input type="hidden" name="' + name
						+ '[unitName]"  value="' + p.unitName + '"/>';
				if (equConfig.type != 'add') {
					hide += '<input type="hidden" name="' + name + '[id]" value="'
							+ (p.id ? p.id : "") + '"/>';
				}

				var td = $("<td>" + p.productCode + "</td>");
				td.append(hide);
				$ctr.append(td);
				$ctr.append("<td>" + imgStr + p.productName + "</td>");
				var pattern = (typeof(p.pattern) != 'undefined'
						? p.pattern
						: p.productModel);
				if (equConfig.type != "view") {
					pattern = $("<input class='txtshort' type='text' name='" + name
							+ "[productModel]' value='" + pattern + "'/>");
				}
				var $td = $("<td></td>");
				$td.append(pattern);
				$ctr.append($td);
				var num = 0;
				if (p.configNum) {
					num = productNum * p.configNum;
				} else if (p.number) {
					num = p.number;
				}
				var cnum = num;
				if (equConfig.type != "view") {
					cnum = $('<input type="text" class="txtshort"  name="' + name
							+ '[number]" value="' + num + '"/>');
				}
				var $td = $("<td></td>");
				$td.append(cnum);
				$ctr.append($td);
				var cwarranty = p.warranty;
				if (equConfig.type != "view") {
					cwarranty = $('<input type="text" class="txtshort"  name="'
							+ name + '[warranty]" value="' + p.warranty + '"/>');
				}
				var $td = $("<td></td>");
				$td.append(cwarranty);
				$ctr.append($td);
				var cperiod = p.arrivalPeriod;
				if (equConfig.type != "view") {
					cperiod = $('<input type="text" class="txtshort"  name="'
							+ name + '[arrivalPeriod]" value="' + p.arrivalPeriod
							+ '" onblur="countStander()" id="inner_arrivalPeriod'
							+ (productId + "_" + productRowNum)+'"/>');
				}
				var $td = $("<td></td>");
				$td.append(cperiod);
				$ctr.append($td);
				$table.append($ctr);
				countStander();
			}
		};
	}
}

// 计算方法
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
			|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// 获取当前数
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// 获取当前单价
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// 计算本行金额 - 不含税
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}

// 表单收缩
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + ".H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}
/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

// 直接提交审批
function toAudit() {
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equEdit&act=audit";
}

function countStander() {
	var cmp_arrivalPeriod = new Array();
	var inner_arrivalPeriod = new Array();
	var cmp_flag = false;
	var inner_flag = false;
	// 物料的交货期
	$('input[id*=cmp_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				cmp_arrivalPeriod[i] = $(this).val() * 1
				cmp_flag = true;
			}else{
				cmp_arrivalPeriod[i] = 0
			}
		} else {
			alert('交货期只能为正整数！');
			return false;
		}
	});
	// 物料配置的交货期
	$('input[id*=inner_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				inner_arrivalPeriod[i] = $(this).val() * 1
				inner_flag = true;
			}else{
				inner_arrivalPeriod[i] = 0
			}
		} else {
			alert('交货期只能为正整数！');
			return false;
		}
	});
	var maxArrival = 0
	if (cmp_flag && inner_flag) {// 从物料和物料配置里面，选最大交货期
		maxArrival = Math.max(Math.max.apply(Math, cmp_arrivalPeriod), Math.max
				.apply(Math, inner_arrivalPeriod));
	} else if (cmp_flag) {// 从物料里，选最大交货期
		maxArrival = Math.max.apply(Math, cmp_arrivalPeriod);
	}
	// 计算标准交货期，当前日期+物料最大交货期
	if( $('#standardDate_v').val() ){
		newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	}else{
		newDate = stringToDate(formatDate(new Date()), maxArrival)
	}
//	alert(formatDate(newDate))
	$('#standardDate').val(formatDate(newDate))

}

function countStanderAfterRemove(arrivalPeriod) {
	var cmp_arrivalPeriod = new Array();
	var inner_arrivalPeriod = new Array();
	var cmp_flag = false;
	var inner_flag = false;
	// 物料的交货期
	$('input[id*=cmp_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				cmp_arrivalPeriod[i] = $(this).val() * 1
				cmp_flag = true;
			}else{
				cmp_arrivalPeriod[i] = 0
			}
		} else {
			alert('交货期只能为正整数！');
			return false;
		}
	});
//	alert(cmp_arrivalPeriod.length);
	if( cmp_arrivalPeriod.length>0 ){
		for (var i=0;i<cmp_arrivalPeriod.length;i++){
			if( arrivalPeriod==cmp_arrivalPeriod[i] ){
				cmp_arrivalPeriod.splice(i,1)
				break;
			}
		}
	}
	// 物料配置的交货期
	$('input[id*=inner_arrivalPeriod]').each(function(i) {
		if (checkNum(this)) {
			if( $(this).parent().parent().children("input").val()!=1 ){
				inner_arrivalPeriod[i] = $(this).val() * 1
				inner_flag = true;
			}else{
				inner_arrivalPeriod[i] = 0
			}
		} else {
			alert('交货期只能为正整数！');
			return false;
		}
	});
	var maxArrival = 0
	if (cmp_flag && inner_flag) {// 从物料和物料配置里面，选最大交货期
		maxArrival = Math.max(Math.max.apply(Math, cmp_arrivalPeriod), Math.max
				.apply(Math, inner_arrivalPeriod));
	} else if (cmp_flag) {// 从物料里，选最大交货期
		maxArrival = Math.max.apply(Math, cmp_arrivalPeriod);
	}
	// 计算标准交货期，当前日期+物料最大交货期
	if( $('#standardDate_v').val() ){
		newDate = stringToDate($('#standardDate_v').val(), maxArrival)
	}else{
		newDate = stringToDate(formatDate(new Date()), maxArrival)
	}
//	alert(formatDate(newDate))
	$('#standardDate').val(formatDate(newDate))

}
/** ********************删除配件************************ */
function delConfig(obj) {
	if (confirm('确定要删除该行？')) {
		var $td = $(obj).parent();
		var name = $td.attr("name");
		var $tr = $td.parent();
		if (equConfig.type == "change") {// 如果是变更
			$tr.append("<input type='hidden' name='" + name
					+ "[isDelTag]' value='1'>");
			$tr.hide();
		} else {
			$tr.remove();
		}
		var rowNum = $tr.parent().children(":visible").size();
		if (rowNum == 1) {
			$tr.parent().hide();
		}
		countStander();
	}
}

/**
 * 根据产品信息带出物料信息
 */
function getGoodsProducts(goodsRowNum, url) {
	var docType=$('#docType').val();
	var selectAssetFn = function(g, rowNum, rowData) {
		var $productName = g.getCmpByRowAndCol(rowNum, 'productName');// 物料名称
		$productName.val(rowData.productName);
		var $arrivalPeriod = g.getCmpByRowAndCol(rowNum, 'arrivalPeriod');// 物料交货期
		$arrivalPeriod.val(rowData.arrivalPeriod);
		var $pattern = g.getCmpByRowAndCol(rowNum, 'productModel');// 物料规格型号
		$pattern.val(rowData.pattern);
		var $productCode = g.getCmpByRowAndCol(rowNum, 'productCode');// 物料编码
		$productCode.val(rowData.productCode);
		var $productId = g.getCmpByRowAndCol(rowNum, 'productId');// 物料id
		$productId.val(rowData.id);
		var num = getInventoryInfos(docType,rowData.id)
		var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// 物料id
		$exeNum.html(num);
		var $warrantyPeriod = g.getCmpByRowAndCol(rowNum, 'warrantyPeriod');// 交货期
		$warrantyPeriod.val(rowData.warranty);
	};
//	var flag=false;
//	alert(url)
//	if(!url){
//		var flag=true;
//	}
	url = url ? url : '?model=contract_contract_equ&action=getConEqu';// 产品下物料数据源

	var isSubAppChange = $("#isSubAppChange").val();
	if(isSubAppChange == '1'){
      var goodsId = $("#originalId" + goodsRowNum).val();
      if(goodsId == '0'){
        var goodsId = $("#conProductId" + goodsRowNum).val();
      }
	}else{
	  var goodsId = $("#conProductId" + goodsRowNum).val();
	}
	var linkId = $("#linkId").val();
	var number = $("#number" + goodsRowNum).val();
	var changeView = $("#changeView").val();
	var contractId = $("#contractId").val();
   if(isSubAppChange == '1'){
     	var param = {
     	contractId : contractId,
		conProductId : goodsId,
		parentEquId : 0,
//		isTemp : 1,
		isDel : 0
//		,number : number
	};
   }else{
   	  var param = {
   	  	contractId : contractId,
		conProductId : goodsId,
		parentEquId : 0,
		isTemp : 0,
		isDel : 0
//		,number : number
	};
   }
//	if(flag){
//		alert(2)
//		delete param.number;
//	}
//	if (linkId) {
//		param.linkId = linkId;
//	}
	if (changeView) {
		param.temp = 1;
		delete param.isTemp;
	}

	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.isDel;
	}
	$("#productInfo" + goodsRowNum).yxeditgrid({
		objName : 'contract[detail][' + goodsRowNum + ']',
		url : url,
		param : param,
		type : equConfig.type,
		colModel : [{
			display : 'isDel',
			name : 'isDel',
			type : 'hidden'
		},{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : 'originalId',
			name : 'originalId',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txtmiddle',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo' + goodsRowNum + '_cmp_productId'
								+ rowNum,
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										selectAssetFn(g, rowNum, productData);
										if (confirm("是否更新物料配件？")) {
											var productNum = rowData
													? rowData.number
													: 1;
											var conProId = rowData
													? rowData.productId
													: 0;
											getProductConfig(goodsId,
													productData.id, productNum,
													goodsRowNum, rowNum,
													conProId, true,contractId);
										}
										countStander()
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				// 变更页面不能更改物料
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo' + goodsRowNum + '_cmp_productId'
								+ rowNum,
						nameCol : 'productName',
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										selectAssetFn(g, rowNum, productData);
										if (confirm("是否更新物料配件？")) {
											var productNum = rowData
													? rowData.number
													: 1;
											var conProId = rowData
													? rowData.productId
													: 0;
											getProductConfig(goodsId,
													productData.id, productNum,
													goodsRowNum, rowNum,
													conProId, true,contractId);
										}
										countStander()
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '产品Id',
			name : 'conProductId',
			staticVal : goodsId,
			type : 'hidden'
		}, {
			display : '型号/版本',
			name : 'productModel',
			readonly : true,
			tclass : 'readOnlyTxtNormal'
		}, {
			display : '保修期',
			name : 'warrantyPeriod',
			readonly : true,
			tclass : 'txtshort'
		}, {
			display : '单位',
			name : 'unitName',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			value : 1
		}, {
			display : '库存数量',
			name : 'exeNum',
//			tclass : 'txtshort',
			type : 'statictext'
		}, {
			display : '交货期',
			name : 'arrivalPeriod',
			tclass : 'txtshort',
			value : 0,
			event : {
				blur : function() {
//					var rowNum = $(this).data("rowNum");
//					// 计算交货期
//					var conProductId = $("#productInfo" + goodsRowNum
//							+ "_cmp_arrivalPeriod" + rowNum).val();
//					var newDate = stringToDate($('#standardDate').val(),
//							conProductId * 1)
//					$('#standardDate').val(formatDate(newDate))
					countStander();
				}
			}
		}, {
//			display : '单价',
//			name : 'price'
//		}, {
//			display : '总金额',
//			name : 'money'
//		}, {
			display : '产品配置Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			display : '物料加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {
			display : '产品加密配置Id',
			name : 'parentLicenseId',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '加密配置',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(equConfig.type == "view" && rowData.license==''){
					return '无';
				}else{
					return html;
				}
			},
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// 获取产品加密配置id
					var productLicense = $("#productInfo" + goodsRowNum
							+ "_cmp_parentLicenseId" + rowNum).val();

					// 获取物料加密配置id
					var thisLicense = $("#productInfo" + goodsRowNum
							+ "_cmp_license" + rowNum).val();
					if (equConfig.type!= 'view'&&(!thisLicense|| thisLicense == productLicense)) {
						url = "?model=yxlicense_license_tempKey&action=toSelectChange&licenseId="
								+ productLicense

						var returnValue = showModalDialog(url, '',
								"dialogWidth:1000px;dialogHeight:600px;");
						// alert(returnValue)
						if (returnValue) {
							$("#productInfo" + goodsRowNum + "_cmp_license"
									+ rowNum).val(returnValue);
						}
					} else if (equConfig.type !== 'view') {
						var licenseUrl = '';
						if( thisLicense!=0 ){
							licenseUrl = '&licenseId='+thisLicense
						}
						url = "?model=yxlicense_license_tempKey&action=toSelectWin"+licenseUrl
						var returnValue = showModalDialog(url, '',
								"dialogWidth:1000px;dialogHeight:600px;");if (returnValue) {
							$("#productInfo" + goodsRowNum + "_cmp_license"
									+ rowNum).val(returnValue);
						}
					} else {
						showLicense(thisLicense);
					}
				}
			},
			html : '<input type="button"  value="加密配置"  class="txt_btn_a"  />'
		}, {
			display : '',
			name : 'configCode',
			type : 'hidden',
			staticVal : 1
		}, {
			name : 'delButton',
			display : '操作',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(rowData && rowData.isDel=='0'){
					return '无';
				}else{
					if(!rowData)
					  return '无';
					else
					  return html;
				}
			},
			event : {
				'click' : function(e) {
					var i = $(this).data("rowNum");
					var isDel = $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val();
				   if(isDel == '1'){
				   	if (confirm('是否确认重启物料？')) {
					       $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productCode" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productName" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_warrantyPeriod" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productModel" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("readonly", false);
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#fffffb;");
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("readonly", false);
						   $("#productInfo"+ goodsRowNum+"_cmp_delButton" + i).html("无");
						   $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val("0");
					 }
				   }
				}
			},
			html : '<input type="button"  value="重启删除物料"  class="txt_btn_a"  />'
		}],
		event : {
			addRow : function(e, rowNum, rowData, g, tr) {
				countStander()
			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
					if( equConfig.type=='change' && rowData){
						if( rowData.executedNum>0 ){
							alert("该物料已部分出库，禁止直接删除，请走退货流程！");
							g.isRemoveAction=false;
							return false;
						}
					}
			},
			reloadData : function(event, g,data) {
				var deploy = $("#deploy" + goodsRowNum).val();
				if (equConfig.type == 'add') {
					g.setColValue('deploy', deploy);
				}else{

				// 加载物料配件
				initProductConfigs(goodsId, goodsRowNum,contractId);
				}
				// 计算标准交货期
				countStander()
				if(data){
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).val();
						if(isDel == '1'){
						   $("#productInfo"+ goodsRowNum+"_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productCode" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productName" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("readonly", true);
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("readonly", true);
						}
					}
				}
			},
			removeRow : function(goodsRowNum) {
				return function(e, rowNum, rowData, index, g) {
					var goodsId = $("#conProductId" + goodsRowNum).val();
					var $tr = $("#goodsDetail_" + goodsId + "_" + rowNum);
					if (confirm("确认删除物料下配置？")) {
						if (equConfig.type == "change") {// 如果是变更
							$tr.find("tr").each(function(i) {
								if (i != 0) {
									var $tr_d = $(this).find("td").parent();
									var $td = $(this).find("td").eq(0);
									var name = $td.attr("name");
									$tr_d.append("<input type='hidden' name='"
											+ name + "[isDelTag]' value='1'>");
								}
							});
							$tr.hide();
						} else {
							$tr.remove();
						}
					} else {
						// var name = $tr.attr("name");
						$tr.find("tbody>tr").each(function() {
							var $td = $(this).find("td");
							var name = $td.attr("name");
							if (name) {
								var $configCode = $("<input type='hidden' name='"
										+ name
										+ "[configCode]' value='1'></input>");

								if ($td.size() > 0) {
									$($td.get(0)).append($configCode);
								}
							}
						});
					}
					countStander();
					arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
					countStanderAfterRemove(arrivalPeriod)
				}
			}(goodsRowNum)
		}
	});
}
$(function() {
	/**
	 * 验证信息
	 */
	 validate({},{disableButton:true});


});
function getNoGoodsProducts(newRowNum) {
	var docType=$('#docType').val();
	// 新增物料
	var param = {};
	var changeView = $("#changeView").val();
	var contractId = $("#contractId").val();
	param.contractId = contractId;
	param.parentEquId = 0;
//	param.linkId = $('#linkId').val();
//	param.notDel = 1;
	param.isDel = 0;
	var isSubAppChange = $("#isSubAppChange").val();
//	if (isSubAppChange == '1') {
//		param.temp = 1;
//	} else {
//		param.isTemp = 0;
//	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
//		delete param.notDel;
		delete param.isDel;
	}
	$("#productInfo").yxeditgrid({
		objName : 'contract[detail][' + newRowNum + ']',
		tableClass : 'form_in_table',
		url : '?model=contract_contract_equ&action=getNoProductEqu',
		param : param,
		type : equConfig.type,
		// isAddOneRow:false,
		// isAdd : false,
		colModel : [{
            display : '归属产品',
            name : 'conProduct',
            tclass : 'txt'
        },{
			display : 'isDel',
			name : 'isDel',
			type : 'hidden'
		},{
			display : 'istemp',
			name : 'isTemp',
			type : 'hidden'
		}, {
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'originalId',
			name : 'originalId',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txtmiddle',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo_cmp_productId' + rowNum,
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										g.getCmpByRowAndCol(rowNum,
												'productName')
												.val(productData.productName);
										g.getCmpByRowAndCol(rowNum,
												'productModel')
												.val(productData.pattern);
										g.getCmpByRowAndCol(rowNum,
												'arrivalPeriod')
												.val(productData.arrivalPeriod);
										g.getCmpByRowAndCol(rowNum,
												'warrantyPeriod')
												.val(productData.warranty);
										var num = getInventoryInfos(docType,productData.id)
										var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// 物料id
										$exeNum.html(num);
										// if (confirm("是否更新物料配件？")) {
										// var productNum = productData
										// ? productData.number
										// : 1;
										// var conProId = productData
										// ? productData.productId
										// : 0;
										// }
										countStander()
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				// 变更页面不能更改物料
				if ((equConfig.type != "change" && equConfig.type != "view")
						|| !rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_product({
//						closeCheck : true,
						closeAndStockCheck : true,
						hiddenId : 'productInfo_cmp_productId' + rowNum,
						nameCol : 'productName',
						height : 250,
						event : {
							'clear' : function() {
								g.clearRowValue(rowNum);
							}
						},
						gridOptions : {
							showcheckbox : false,
							isTitle : true,
							event : {
								"row_dblclick" : (function(rowNum, rowData) {
									return function(e, row, productData) {
										g.getCmpByRowAndCol(rowNum,
												'productCode')
												.val(productData.productCode);
										g.getCmpByRowAndCol(rowNum,
												'productModel')
												.val(productData.pattern);
										g.getCmpByRowAndCol(rowNum,
												'arrivalPeriod')
												.val(productData.arrivalPeriod);
										g.getCmpByRowAndCol(rowNum,
												'warrantyPeriod')
												.val(productData.warranty);
										var num = getInventoryInfos(docType,productData.id)
										var $exeNum = g.getCmpByRowAndCol(rowNum, 'exeNum');// 物料id
										$exeNum.html(num);
										// if (confirm("是否更新物料配件？")) {
										// var productNum = productData
										// ? productData.number
										// : 1;
										// var conProId = productData
										// ? productData.productId
										// : 0;
										// }
										countStander()
									}
								})(rowNum, rowData)
							}
						}
					});
				} else if (equConfig.type != "view") {
					$input.attr("readonly", true);
				}
				return $input;
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '型号/版本',
			name : 'productModel',
			readonly : true,
			tclass : 'readOnlyTxtNormal'
		}, {
			display : '单位',
			name : 'unitName',
			type : 'hidden'
		}, {
//			display : '单价',
//			name : 'price'
//		}, {
//			display : '总金额',
//			name : 'money'
//		}, {
			display : '保修期',
			readonly : true,
			name : 'warrantyPeriod'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {
				}
			}
		}, {
			display : '库存数量',
			name : 'exeNum',
//			tclass : 'txtshort',
			type : 'statictext'
		}, {
			display : '交货期',
			name : 'arrivalPeriod',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countStander();
				}
			}
		}, {
			display : '物料加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '加密配置',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(equConfig.type == "view" && rowData.license==''){
					return '无';
				}else{
					return html;
				}
			},
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// 获取物料加密配置id
					var thisLicense = $("#productInfo_cmp_license" + rowNum)
							.val();
					if (equConfig.type != "view") {
						url = "?model=yxlicense_license_tempKey&action=toSelectChange&licenseId="
								+ thisLicense

						var returnValue = showModalDialog(url, '',
								"dialogWidth:1000px;dialogHeight:600px;");
						if (returnValue) {
							$("#productInfo_cmp_license" + rowNum)
									.val(returnValue);
						}
					} else {
						showLicense(thisLicense)
					}
				}
			},
			html : '<input type="button"  value="加密配置"  class="txt_btn_a"  />'
		}, {
			display : '',
			name : 'configCode',
			type : 'hidden',
			staticVal : 1
		}, {
			name : 'delButton',
			display : '操作',
			type : 'statictext',
			process : function(html, rowData, $tr, g){
				if(rowData && rowData.isDel=='0'){
					return '无';
				}else{
					if(!rowData)
					  return '无';
					else
					  return html;
				}
			},
			event : {
				'click' : function(e) {
					var i = $(this).data("rowNum");
					var isDel = $("#productInfo_cmp_isDel" + i).val();
				   if(isDel == '1'){
				   	if (confirm('是否确认重启物料？')) {
					       $("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_productCode" + i).attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_productName" + i).attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_warrantyPeriod" + i).attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_productModel" + i).attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_number" + i).attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_number" + i).attr("readonly", false);
						   $("#productInfo_cmp_arrivalPeriod" + i).attr("style", "background:#fffffb;");
						   $("#productInfo_cmp_arrivalPeriod" + i).attr("readonly", false);
						   $("#productInfo_cmp_delButton" + i).html("无");
						   $("#productInfo_cmp_isDel" + i).val("0");
					 }
				   }
				}
			},
			html : '<input type="button"  value="重启删除物料"  class="txt_btn_a"  />'
		}],
		event : {
			addRow : function(e, rowNum, rowData, g, tr) {
				countStander()
			},
			removeRow : function(e, rowNum, rowData, index, g){
				countStander();
				arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
				countStanderAfterRemove(arrivalPeriod)
			},
			reloadData : function(event, g,data) {
				var deploy = $("#deploy" + newRowNum).val();
				if (equConfig.type == 'add') {
					g.setColValue('deploy', deploy);
				}else{
					// 加载物料配件
					initProductConfigs('0',newRowNum,contractId);
				}
				// 计算标准交货期
				countStander();
				if(data){
					var rowCount = g.getCurRowNum();
					for (var i = 0; i < rowCount; i++) {
						var isDel = $("#productInfo_cmp_isDel" + i).val();
						if(isDel == '1'){
						   $("#productInfo_cmp_isDel" + i).parent().parent(".tr_even").attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productCode" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productName" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_warrantyPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_productModel" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_number" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_number" + i).attr("readonly", true);
						   $("#productInfo_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;");
						   $("#productInfo_cmp_arrivalPeriod" + i).attr("readonly", true);
						}
					}
				}
			}
		}
	});
}

function toBack() {
	document.getElementById('form1').action = "index1.php?model=contract_contract_contract&action=confirmEqu&act=back";
}
