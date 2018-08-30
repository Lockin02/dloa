
/**
 *
 * 物料确认公用js(新增，编辑，变更，查看)
 */
var equConfig = {
	type : ''// add edit change view
};// 配置属性

$(function() {
	//定义特殊物料id数组，用于处理配件成本
	specialProIdArr = $("#specialProId").val().split(",");
});

// 变更信息
/**
 * 转成变更颜色提醒
 */
var tranToChangeShow = function(oldVal, newVal) {
	var newHtml = "<font color='red'><span class='oldValue' style='display:none'>"
			+ oldVal
			+ "</span><span class='compare' style='display:none'>=></span><span class='newValue'>"
			+ newVal + "</span></font>";
	return newHtml;
}

/**
 * 给可编辑表格加上变更标识图标
 */
var addGridChangeShow = function(g, rowNum, colName, rowData, tr) {
	if (equConfig.type == "view") {
		var $cmp = g.getCmpByRowAndCol(rowNum, colName);
		if (rowData.changeTips == '1') {
			// tr.css("background-color", "#F8846B");
			$cmp.prepend('<img title="变更编辑的产品" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {
			$cmp.prepend('<img title="变更新增的产品" src="images/new.gif" />');
		} else if (rowData.changeTips == '3'&&rowData.isDel=='1') {
			$cmp.prepend('<img title="变更删除的产品" src="images/changedelete.gif" />');
			tr.css("color", "#8B9CA4");
		} else if (rowData.changeTips == '0'&&rowData.isDel=='1') {
			tr.css("color", "#8B9CA4");
		}
	}
}

/**
 * 处理变更明细
 */
var beforeAddRow = function(e, rowNum, rowData, g, tr) {
	if (equConfig.type == "view") {
		var key = rowData.id;
		if ($("#isTemp").val() == 1) {
			key = rowData.originalId;
		}
		var detail = changeDetailObj["d" + key];
		if (detail) {
			for (var i = 0; i < detail.length; i++) {
				var changeField = detail[i].changeField;
				rowData[changeField] = tranToChangeShow(detail[i].oldValue,
						rowData[detail[i].changeField]);
			}
		}
	}
}

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
function initProductConfigs(goodsId,goodsRowNum,contractId) {
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
	var alreadyDelObj = $productGrid.yxeditgrid("getCmpByCol", "alreadyDel");// 删除的可重启的物料
	var equSize = equIdObj.size();
	productObj.each(function(i, n) {
		var productId = this.value;
		if (equSize > 0) {
			if(equIdObj[i].value != '0' && equIdObj[i].value != ''){
			   getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i, equIdObj[i].value,false,contractId,alreadyDelObj[i].value);
			   if(isSubAppChange == '1'){
				   equIdArr.push(this.id);
			   }
			}
		} else {
			getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
					i,"",false,contractId,alreadyDelObj[i].value);
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
							i, equIdObjA[i].value,false,contractId,alreadyDelObj[i].value);
					}
				} else {
					getProductConfig(goodsId, productId, numObj[i].value, goodsRowNum,
							i,"",false,contractId,alreadyDelObj[i].value);
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
		productRowNum, equId, ifGetPro,contractId,isDel) {
	var url = "";
	var param = {};
	// 判断是编辑还是新增情况
	if (equConfig.type != 'add' && !ifGetPro) {
		param.parentEquId = equId;
		param.contractId = contractId;
		if (equConfig.type != "view" || $("#isShowDel").val() == "false") {
			param.isDel = typeof(isDel) == undefined ? 0 : isDel;
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
			var $table = $("<table class='form_in_table' id='contractequ_" + goodsId + "_" + productRowNum + "'></table>");
			var $td = $("<td class='innerTd' colspan='20'></td>");
			var $tr = $("<tr id='goodsDetail_" + goodsId + "_" + productRowNum
					+ "'></tr>")
			$tr.append($td)
			$td.append($table);

			var $th = $('<tr class="main_tr_header1"><th width="30px"><img src="images/add_item.png" onclick="addConfig(' + goodsId + ',' + productRowNum + ','+ goodsRowNum + ','+ productId + ')" title="添加行"></th><th width="35px">序号</th><th>配件编码</th>'
					+ '<th>配件名称</th><th>版本型号</th><th>数量</th><th>保修期</th><th>交货期</th><th>成本</th></tr>');
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
                var executedN = parseInt(p.executedNum);//已发货数量
				var shipedN = parseInt(p.issuedShipNum);//下达发货计划数量
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
					$ctr.append("<td name='"
									+ name
									+ "'><img src='images/removeline.png' onclick='delConfig(this," + goodsId + "," + productRowNum + ","+ executedN +","+shipedN+");' title='删除行'></td>");
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
						+ '[unitName]"  value="' + p.unitName
						+ '"/><input type="hidden" name="' + name
						+ '[originalId]"  value="' + p.originalId
						+ '"/> ';
				if (equConfig.type != 'add') {
					hide += '<input type="hidden" name="' + name + '[id]" value="'
							+ (p.id ? p.id : "") + '"/>';
				}
				if(goodsId == '0'){//用于借用转销售物料
					hide += '<input type="hidden" name="' + name + '[isBorrowToorder]" value="1"/>';
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
                var excdNum = executedN;
				if (equConfig.type != "view") {
					cnum = $('<input type="text" class="txtshort"  name="' + name
							+ '[number]" value="' + num + '" onchange="chkNumber('+ excdNum +',' + goodsRowNum + ','+ index +',' + goodsId + ','+ productRowNum +', this)" />');
				}
				var $td = $("<td></td>");
				$td.append(cnum);
				$ctr.append($td);
				var cwarranty = p.warranty;
				if (equConfig.type != "view") {
					cwarranty = $('<input type="text" class="txtshort"  name="'
							+ name + '[warrantyPeriod]" value="' + p.warranty + '"/>');
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
				var price = 0;
				if (p.configNum) {
					price = p.priCost;
				} else if (p.number) {
					price = p.price;
				}
				var cost = "";
				if (equConfig.type != "view") {
					cost = $('<input type="text" class="readOnlyTxtShort"  name="' + name
							+ '[cost]" value="' + num * price + '" readonly/>');
				}
				var $td = $("<td></td>");
				$td.append(cost);
				$ctr.append($td);
				var price = $('<input type="hidden" name="' + name
						+ '[price]" value="' + price + '" />');
				$td.append(price);
				$ctr.append($td);
				$table.append($ctr);
				countStander();
				countEquCost(goodsId,productRowNum);
			}
		}
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
	var cmp_arrivalPeriod = [];
	var inner_arrivalPeriod = [];
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
	var cmp_arrivalPeriod = [];
	var inner_arrivalPeriod = [];
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
				cmp_arrivalPeriod.splice(i,1);
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
function delConfig(obj,goodsId,productRowNum,exeNum,shipedN) {
    if(exeNum > 0){//如果物料已发货，不得删除，只能改数量
        alert("此配件已发货"+exeNum+"件,不得删除,只能改数量。");
    }else if(shipedN > 0) {
		alert("此配件已下达发货计划"+shipedN+"件,不得删除,只能改数量。");
    }else{
        if (confirm('确定要删除该行？')) {
            var $td = $(obj).parent();
            var name = $td.attr("name");
            var $tr = $td.parent();
            $tr.append("<input type='hidden' name='" + name
                    + "[isDelTag]' value='1'>");
            $tr.hide();
            var rowNum = $tr.parent().children(":visible").size();
            if (rowNum == 1) {
                $tr.parent().hide();
            }
            countStander();
            countEquCost(goodsId,productRowNum);
			cal();
        }
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
		parentEquId : 0
//		isTemp : 1,
//		isDel : 0
//		,number : number
	};
   }else{
   	  var param = {
   	  	contractId : contractId,
		conProductId : goodsId,
		parentEquId : 0,
		isTemp : 0
//		isDel : 0
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

    // todo 产品对应物料
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
		},{
			display : 'originalTempId',
			name : 'originalTempId',
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
										countStander();
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val('10');
											$("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val('0');
											countEquCost(goodsId,rowNum);
										}
                                        // 加入物料计算
                                        cal();
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
										countStander();
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val(10);
											$("#productInfo"+ goodsRowNum+"_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val(0);
											countEquCost(goodsId,rowNum);
										}
                                        // 加入物料计算
                                        cal();
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
		},{
            display : '已执行数量',
            name : 'executedNum',
            type : 'hidden'
        },{
			display : '下达发货计划数量',
			name : 'issuedShipNum',
			type : 'hidden'
		},{
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				change : function(e){
                    var selfId = $(this).attr('id');
                    var exeNumId =selfId.replace('number','executedNum');
                    var exeNum = parseInt($('#'+exeNumId).val());//已执行数量
					if(!isNum($(this).val())){
						alert("请输入正整数");
						$(this).val(1);
						// 加入物料计算
						cal();
						return false;
					}else if( parseInt($(this).val()) < exeNum ){
                        alert("输入数量不得少于已发货数！");
                        $(this).val(exeNum);
						// 加入物料计算
						cal();
                        return false;
                    }else{
						// 加入物料计算
						cal();
					}
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
			value : 0,
			event : {
				blur : function() {
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
						   $("#productInfo"+ goodsRowNum+"_cmp_originalTempId" + i).val($("#productInfo"+ goodsRowNum+"_cmp_id" + i).val());
						   // 加载物料配件
						   if (confirm('是否重启物料下配置？')) {
							   initProductConfigs(goodsId, goodsRowNum,contractId);
						   }
					   }
				   }
				}
			},
			html : '<input type="button"  value="重启删除物料"  class="txt_btn_a"  />'
		}, {
			display : '成本比例（%）',
			name : 'proportion',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			process : function($input, rowData) {
				$input.val('');
				if (equConfig.type != "view" || !rowData) {
					var proportionVal = (!rowData || rowData['proportion'] == '' || rowData['proportion'] < 0)? '0' : rowData['proportion'];
					var rowNum = $input.data("rowNum");
					var grid = $input.data("grid");
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					if(specialProIdArr.indexOf(productId) != -1){
						$input.attr("style", "background:#fffffb;").attr("readonly",false).val(proportionVal);
					}
				}
			},
			event : {
				change : function(e){
					if(!isNum($(this).val())){
						if($(this).val() != 0){
							alert("请输入正整数");
							$(this).val(0);
							return false;
						}
					}
					var rowNum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					if(specialProIdArr.indexOf(productId) != -1){
						countEquCost(goodsId,rowNum);
                        // 加入物料计算
                        cal();
					}
				}
			}
		}, {
			display : '成本概算',
			name : 'costEstimate',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '',
			name : 'alreadyDel',
			type : 'hidden',
			staticVal : '0'
		}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander();
			},
//			beforeRemoveRow : function(goodsRowNum){
//				return function(e, rowNum, rowData, index, g) {
//			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
                if( equConfig.type=='change' && rowData){
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_equ&action=getRelativeEqu",
						data : {
							"isTemp" : 0,
							"isDel" : 0,
							"advCondition" : " AND parentEquId='"+rowData.id+"'"

						},
						async : false,
						success : function(data) {
							var configExecutedNum = configIssuedShipNum = 0;
							if (data != "") {
								data = eval("(" + data + ")");
								$.each(data,function(i,item){
									configExecutedNum += parseInt(item.executedNum);
									configIssuedShipNum += parseInt(item.issuedShipNum);
								});
							}
							if(configExecutedNum > 0){
								alert("该物料部分配件已出库，禁止直接删除，请走退货流程！");
								g.isRemoveAction=false;
								return false;
							}else if(configIssuedShipNum > 0){
								alert("该物料部分配件已下达发货计划，禁止直接删除！");
								g.isRemoveAction=false;
								return false;
							}else if( rowData.executedNum>0 ){//如果有已出库不能删
								alert("该物料已部分出库，禁止直接删除，请走退货流程！");
								g.isRemoveAction=false;
								return false;
							}else if(rowData.issuedShipNum > 0){//如果没有已出库,但是有发货计划的也不能删
								alert("该物料已下达发货计划，禁止直接删除！");
								g.isRemoveAction=false;
								return false;
							}

						}
					});
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
						   $("#productInfo"+ goodsRowNum+"_cmp_number" + i).attr("style", "background:#a1a3a6;").attr("readonly", true);
						   $("#productInfo"+ goodsRowNum+"_cmp_arrivalPeriod" + i).attr("style", "background:#a1a3a6;").attr("readonly", true);
						   $("#productInfo"+ goodsRowNum+"_cmp_alreadyDel" + i).val("1")
						}
					}

                    // 加入物料计算
                    cal();
				}
			},
			removeRow : function(goodsRowNum) {
				return function(e, rowNum, rowData, index, g) {
					if (equConfig.type == "change"){
					  var goodsId = $("#originalId" + goodsRowNum).val();
					  if(goodsId == '0' || goodsId == ''){
						  var goodsId = $("#conProductId" + goodsRowNum).val();
					  }
					}else{
					  var goodsId = $("#conProductId" + goodsRowNum).val();
					}
					var $tr = $("#goodsDetail_" + goodsId + "_" + rowNum);
					//经与交付确认，删除物料后默认删除所有配件
//					if (confirm("确认删除物料下配置？")) {
						if (equConfig.type == "change" || equConfig.type == "edit") {// 如果是变更或是编辑
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
//					} else {
//						// var name = $tr.attr("name");
//						$tr.find("tbody>tr").each(function() {
//							var $td = $(this).find("td");
//							var name = $td.attr("name");
//							if (name) {
//								var $configCode = $("<input type='hidden' name='"
//										+ name
//										+ "[configCode]' value='1'></input>");
//
//								if ($td.size() > 0) {
//									$($td.get(0)).append($configCode);
//								}
//							}
//						});
//					}
					countStander();
					arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
					countStanderAfterRemove(arrivalPeriod)

                    // 加入物料计算
                    cal('del',g.el['selector'],index);
				}
			}(goodsRowNum)
		}
	});
}

// 递进循环获取借试用物料信息
var jsyequ_arr = [];
function cacheJsyEqu(n,id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num){
	var conId_str = id_str.replace("productId","id");
	var originalId_str = id_str.replace("productId","originalId");
	var isNotDel = true;
	if(typeof isdel_str != 'undefined'){
		isNotDel = ( $(isdel_str+n).val() != 1 );
	}
	// 变更时删除的数据不取
	if(isNotDel){
		// 假删除的数据不取
		if(typeof $(id_str+n).val() != 'undefined' && $(id_str+n).parent().parent().is(":visible")){
			var id = $(id_str+n).val();
			var conId = $(conId_str+n).val();
			var originalId = $(originalId_str+n).val();
			originalId = Number(originalId);
			var num = parseInt($(num_str + n).val());

			if (specialProIdArr.indexOf(id) != -1) {
				// 可调整成本比例的数据，概算直接获取
				jsyequ_arr.push({
					rownum : n,
					productId: id,
					number : num,
					conId  : (originalId === 0)? conId : originalId,
					amount : $(cost_str + n).val()
				});
			} else {
				jsyequ_arr.push({
					rownum : n,
					productId: id,
					conId  : (originalId === 0)? conId : originalId,
					number: num
				});
			}

			// 获取物料下的配件ID以及数量 PMS2364 2017-01-06
			if(conProductId_str != '' && specialProIdArr.indexOf(id) == -1){// pms2522 特殊物料的配件不参与概算计算
				var conProductId = $(conProductId_str+n).val();
				if($("#contractequ_"+conProductId+"_"+n).children('tbody').children('tr').length > 0){
					$("#contractequ_"+conProductId+"_"+n).children('tbody').children('tr').each(function(){
						if($(this).attr('index') != undefined){
							var index = $(this).attr('index');
							var name = $("#goodsDetail_"+conProductId+"_"+n).attr('name')+"["+index+"]";
							var productId_name = name+"[productId]";
							var originalId_name = name+"[originalId]";
							var conId_name = name+"[id]";
							var productNum_name = name+"[number]";
							var isDelTag_name = name+"[isDelTag]";
							// console.log($("input[name='"+productId_name+"']").val());
							var productId = $("input[name='"+productId_name+"']").val();
							var originalId = $("input[name='"+originalId_name+"']").val();
							originalId = Number(originalId);
							var conId = $("input[name='"+conId_name+"']").val();
							var productNum = $("input[name='"+productNum_name+"']").val();
							var isDelTag = $("input[name='"+isDelTag_name+"']").val();
							if(isDelTag != 1){
								jsyequ_arr.push({
									rownum : n,
									productId: productId,
									conId: (originalId === 0)? conId : originalId,
									number: productNum
								});
							}
						}
					});
				}
			}

			product_Num += 1;
			return cacheJsyEqu((n+1),id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num);
		}else{
			// 当收集到的物料数量少于产品下的所有物料时继续收集（防止物料ID后面的序号不连贯时会出Bug）
			if( typeof product_Num != 'undefined' && product_Num < product_Count ){
				product_Count -= 1;
				return cacheJsyEqu((n+1),id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num);
			}else{
				return jsyequ_arr;
			}
		}
	}else{
		return cacheJsyEqu((n+1),id_str,num_str,conProductId_str,cost_str,isdel_str,product_Count,product_Num);
	}
}

// 计算概算和毛利率的方法
function cal(act,productId,equIndex) {
    var rowNum = $("#rowNum").val();
    var productArr = [];
	jsyequ_arr = [];

    //当删除物料时收集物料信息
    var delProductArr = [];
    if( act == "del" ){
		delProductArr['rownum'] = $(productId+"_cmp_productId"+equIndex).parent("td").parent("tr").attr("rownum");
		delProductArr['id'] = $(productId+"_cmp_productId"+equIndex).val();
		delProductArr['num'] = $(productId+"_cmp_number"+equIndex).val();
    }

    // 通过产品数量循环获取物料信息
    for (var i = 1; i <= rowNum; i++) {
		// 产品下物料数量
		var product_Count = $("#productInfo"+i+" table tbody").children('tr').length;
		// 产品被删除了的数据不取
		if($("#conProductId"+i).parent().prev().children().attr('title') != '变更删除的产品'){//因为找不到可参照元素，暂时用被删除产品序号前面图标的title属性判断
			cacheJsyEqu(0,"#productInfo" + i + "_cmp_productId","#productInfo" + i + "_cmp_number","#productInfo" + i + "_cmp_conProductId","#productInfo" + i + "_cmp_costEstimate","#productInfo" + i + "_cmp_isDel",product_Count,0);
		}
    }
	// 获取借试用物料信息
	cacheJsyEqu(0,"#productInfo_cmp_productId","#productInfo_cmp_number",'',"#productInfo_cmp_costEstimate");
	if(jsyequ_arr.length > 0){
		for (var i = 0; i < jsyequ_arr.length; i++) {
			productArr.push(jsyequ_arr[i]);
		}
	}

	// 动态排除掉删除的数据
	var del_num = 0;
	if( act == "del" ){
		for (var i = 0; i < productArr.length; i++) {
			// if (productArr[i].productId == delProductArr['id'] && productArr[i].number == delProductArr['num'] && del_num == 0) {
			// 	productArr.splice(i,1);
			// 	del_num += 1;
			// }
			if(productArr[i].rownum == delProductArr['rownum']){
				productArr.splice(i,1);
				del_num += 1;
			}
		}
	}

	//console.log(productArr);
	//var ids = '';
	//for (var i = 0; i < productArr.length; i++) {
	//	ids += productArr[i].productId + ',';
	//}
	//console.log(ids);
	//console.log("here: "+specialProIdArr);

    // 计算
	var contractId = (typeof $("#oldId").val() != 'undefined' && $("#oldId").val() != "undefined")? $("#oldId").val() : $("#contractId").val();//分辨出新增合同或旧合同
	var isChangeVal =  ($("#dealStatus").val() == 1 || $("#dealStatus").val() == 3)? 1 : $("#isSubAppChange").val();
    $.ajax({
        url: "?model=contract_contract_contract&action=getCostByEqu",
        data: {
            cid: contractId,
            equArr: productArr,
			isChange: isChangeVal
        },
        async: false,
        type: 'post',
        dataType: 'json',
        success: function(msg) {
            if (msg.result == "ok") {
                $("#calArea").html("概算：" + msg.costEstimates + ", 预计毛利率：" + msg.exgross + " %");
            } else {
                $("#calArea").html(msg.msg);
            }
        }
    });
}

$(function() {
	/**
	 * 验证信息
	 */
	 validate({},{disableButton:true});
});

// 根据借试用物料ID查询相应的执行数量
function chkBorrowEquExeNum(borrowEquId){
	var returnValue = $.ajax({
		type: 'POST',
		url: "?model=projectmanagent_borrow_borrow&action=getOriginalBorrowEquInfo",
		data: {
			id: borrowEquId
		},
		async: false,
		success: function (data) {
		}
	}).responseText;
	returnValue = eval("(" + returnValue + ")");
	return returnValue[0];
}

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
	var isSubAppChange = $("#isSubAppChange").val();
	if (isSubAppChange == '1') {
		param.temp = 1;
	} else {
		param.isTemp = 0;
	}
	if (equConfig.type == "view" && $("#isShowDel").val() == "true") {
		delete param.notDel;
	}
	$("#productInfo").yxeditgrid({
		objName : 'contract[detail][' + newRowNum + ']',
		tableClass : 'form_in_table',
		url : '?model=contract_contract_equ&action=getNoProductEqu',
		param : param,
		type : equConfig.type,
		// isAddOneRow:false,
		isAdd : false,
		colModel : [{
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
		},{
			display : 'originalTempId',
			name : 'originalTempId',
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
										if (confirm("是否更新物料配件？")) {
											var productNum = rowData
												? rowData.number
												: 1;
											var conProId = rowData
												? rowData.productId
												: 0;
											getProductConfig('0',
													productData.id, productNum,
													newRowNum, rowNum,
													conProId, true,contractId);
										}
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val('10');
											$("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val('0');
											countEquCost('0',rowNum);
										}
										countStander();
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
										if (confirm("是否更新物料配件？")) {
											var productNum = rowData
												? rowData.number
												: 1;
											var conProId = rowData
												? rowData.productId
												: 0;
											getProductConfig('0',
													productData.id, productNum,
													newRowNum, rowNum,
													conProId, true,contractId);
										}
										if(specialProIdArr.indexOf(productData.id) != -1){
											// $("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
											// 	.attr("readonly",false).val('10');
											$("#productInfo_cmp_proportion" + rowNum).attr("style", "background:#fffffb;")
												.attr("readonly",false).val('0');
											countEquCost('0',rowNum);
										}
										countStander();
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
			display : '下达发货计划数量',
			name : 'issuedShipNum',
			type : 'hidden'
		}, {
			display : '借试用物料ID',
			name : 'toBorrowequId',
			type : 'hidden'
		},{
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			value : 1,
			event : {
				blur : function() {//chkBorrowEquExeNum(borrowEquId)
					var borrowEquId = $("#productInfo_cmp_toBorrowequId"+$(this).data("rowNum")).val();
					var borrowEqu = chkBorrowEquExeNum(borrowEquId);
					var executedNum = borrowEqu.executedNum - borrowEqu.backNum;
					if(isNaN($(this).val()) || parseInt($(this).val()) <= 0){
						alert("请输入大于0的整数。");
						$(this).val(executedNum);
					}else if(parseInt($(this).val()) > parseInt(executedNum)){
						alert("转销售数量请控制在可行性数量范围内。");
						$(this).val(executedNum);
					}else{
						// 加入物料计算
						cal();
					}
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
			display : 'isBorrowToorder',
			name : 'isBorrowToorder',
			type : 'hidden'
		}, {
			display : '',
			name : 'configCode',
			type : 'hidden',
			staticVal : 1
		}, {
			name : 'delButton',
			display : '操作',
			type : 'hidden',
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
						    $("#productInfo_cmp_originalTempId" + i).val($("#productInfo_cmp_id" + i).val());
						   // 加载物料配件
						   if (confirm('是否重启物料下配置？')) {
							   initProductConfigs('0',newRowNum,contractId);
						   }
					   }
				   }
				}
			},
			html : '<input type="button"  value="重启删除物料"  class="txt_btn_a"  />'
		}, {
			display : '成本比例（%）',
			name : 'proportion',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			process : function($input, rowData) {
				$input.val('');
				if (equConfig.type != "view" || !rowData) {
					var proportionVal = (!rowData || rowData['proportion'] == '' || rowData['proportion'] < 0)? '0' : rowData['proportion'];
					var rowNum = $input.data("rowNum");
					var grid = $input.data("grid");
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					var isDel = grid.getCmpByRowAndCol(rowNum, 'isDel').val();
					if(specialProIdArr.indexOf(productId) != -1 && isDel == '0'){
						$input.attr("style", "background:#fffffb;").attr("readonly",false).val(proportionVal);
					}
				}
			},
			event : {
				change : function(e){
					if(!isNum($(this).val())){
						if($(this).val() != 0){
							alert("请输入正整数");
							$(this).val(0);
							return false;
						}
					}
					var rowNum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					var productId = grid.getCmpByRowAndCol(rowNum, 'productId').val();
					if(specialProIdArr.indexOf(productId) != -1){
						countEquCost('0',rowNum);
						// 加入物料计算
						cal();
					}
				}
			}
		}, {
			display : '成本概算',
			name : 'costEstimate',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '',
			name : 'alreadyDel',
			type : 'hidden',
			staticVal : '0'
		}],
		event : {
			beforeAddRow : beforeAddRow,
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'productName', rowData, tr);
				countStander()
			},
			beforeRemoveRow : function(e, rowNum, rowData, g) {
				if( equConfig.type=='change' && rowData){
					if( rowData.executedNum>0 ){//如果有已出库不能删
						alert("该物料已部分出库，禁止直接删除，请走退货流程！");
						g.isRemoveAction=false;
						return false;
					}else if(rowData.issuedShipNum > 0){//如果没有已出库,但是有发货计划的也不能删
						alert("该物料已下达发货计划，禁止直接删除！");
						g.isRemoveAction=false;
						return false;
					}
				}
			},
			removeRow : function(e, rowNum, rowData, index, g){
				var $tr = $("#goodsDetail_0_" + rowNum);
				//经与交付确认，删除物料后默认删除所有配件
				if (equConfig.type == "change" || equConfig.type == "edit") {// 如果是变更或是编辑
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
				countStander();
				arrivalPeriod=g.getCmpByRowAndCol(rowNum,"arrivalPeriod").val();
				countStanderAfterRemove(arrivalPeriod);
				cal();
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
						   $("#productInfo_cmp_alreadyDel" + i).val("1")
						}
					}
				}
				cal();
			}
		}
	});
}

//修改配件数量时，检查该配件是否已发货
function chkNumber(executedNum,goodsRowNum,index,goodsId,productRowNum,self){
    if(parseInt(executedNum)>0 && parseInt($(self).val()) < parseInt(executedNum)){
        alert("已发货"+executedNum+"件，填写的数量不得少于已发货数量。");
        $(self).val(executedNum);
    }else{
        setEquConfigCost(goodsRowNum , index);
        countEquCost(goodsId,productRowNum);
		cal();
    }
}

//计算配件成本
function setEquConfigCost(goodsRowNum,rowNum){
	var price = $("input[name='contract[detail][" + goodsRowNum + "][" + rowNum + "][price]']").val();
	var number = $("input[name='contract[detail][" + goodsRowNum + "][" + rowNum + "][number]']").val();
	$("input[name='contract[detail][" + goodsRowNum + "][" + rowNum + "][cost]']").val(accMul(price,number));
}

//计算物料成本概算
function countEquCost(goodsId,productRowNum){
	var tr = $("#goodsDetail_" + goodsId + "_" + productRowNum);
	var configObj = tr.find("input[name*='cost']:visible");
	var cost = 0;
	configObj.each(function(){
		cost = accAdd(cost,$(this).val());
	});
	var proportion = tr.prev("tr").find("input[id*='proportion']").val();
//	var number = tr.prev("tr").find("input[id*='number']").val();
//	tr.prev("tr").find("input[id*='costEstimate']").val(accMul(accMul(cost,accAdd(1,accDiv(proportion,100))),number));
	if(proportion == 0){
		tr.prev("tr").find("input[id*='costEstimate']").val(cost);
	}else if(proportion > 0){
		tr.prev("tr").find("input[id*='costEstimate']").val(accMul(cost,accAdd(1,accDiv(proportion,100))));
	}
}

//新增配件
function addConfig(goodsId,productRowNum,goodsRowNum,productId){
	var tr = $("#goodsDetail_" + goodsId + "_" + productRowNum);
	var index = accAdd(tr.find("tr:last").attr("index"),1);
	var name = 'contract[detail]['+ goodsRowNum +']['+ index +']';
	var mycount = tr.find("tr").length;
	var itemtable = document.getElementById("contractequ_" + goodsId + "_" + productRowNum);
	i = itemtable.rows.length;
    oTR = itemtable.insertRow([i]);
    oTR.className = "tr_inner";
    oTR.index = index;
    oTR.height = "28px";
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delConfig(this,' + goodsId + ',' + productRowNum + ');" title="删除行">';
    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = mycount;
    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = '<input type="text" name="' + name
    + '[productCode]" class="txtshort" />'
    + '<input type="hidden" name="' + name
    + '[parentRowNum]" value="' + productId + '_' + productRowNum + '"/>'
    + '<input type="hidden" name="' + name
    + '[productId]" />'
    + '<input type="hidden" name="' + name
    + '[conProductId]" value="' + goodsId + '"/>'
    + '<input type="hidden" name="' + name
    + '[unitName]" />';
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = '<input type="text" name="' + name
    + '[productName]" class="txtshort" />';
    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = '<input type="text" name="' + name
    + '[productModel]" class="txtshort" />';
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = '<input type="text" name="' + name
    + '[number]" class="txtshort" onchange="setEquConfigCost('+ goodsRowNum + ',' + index + ');countEquCost('+ goodsId + ',' + productRowNum + ')" />';
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" name="' + name
    + '[warrantyPeriod]" class="txtshort" />';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = '<input type="text" name="' + name
        + '[arrivalPeriod]" class="txtshort" onblur="countStander()" id="inner_arrivalPeriod' + productId + '_' + productRowNum + '"/>';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = '<input type="text" name="' + name
    + '[cost]" class="readOnlyTxtShort" readonly="readonly" />'
    + '<input type="hidden" name="' + name
    + '[price]" />';
    //给新增的tr的删除按钮td增加name，删除的时候会用到
    tr.find("tr:last").find("td:first").attr("name",name);
    reloadConfigProduct(goodsId,productRowNum,name);
}

/**
 * 渲染配件物料信息combogrid
 */
function reloadConfigProduct(goodsId,productRowNum,name) {
    $("input[name='" + name + "[productCode]']").yxcombogrid_product("remove").yxcombogrid_product({// 绑定物料编号
        nameCol: 'productCode',
		event : {
			'clear' : function() {
            	$("input[name='" + name + "[productId]']").val('');
            	$("input[name='" + name + "[productName]']").val('');
            	$("input[name='" + name + "[unitName]']").val('');
            	$("input[name='" + name + "[productModel]']").val('');
            	$("input[name='" + name + "[number]']").val('');
            	$("input[name='" + name + "[warrantyPeriod]']").val('');
            	$("input[name='" + name + "[arrivalPeriod]']").val('');
            	$("input[name='" + name + "[price]']").val('');
            	$("input[name='" + name + "[cost]']").val('');
            	countEquCost(goodsId,productRowNum);
			}
		},
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (i) {
                    return function (e, row, data) {
                    	$("input[name='" + name + "[productId]']").val(data.id);
                    	$("input[name='" + name + "[productName]']").val(data.productName);
                    	$("input[name='" + name + "[unitName]']").val(data.unitName);
                    	$("input[name='" + name + "[productModel]']").val(data.pattern);
                    	$("input[name='" + name + "[number]']").val('1');
                    	$("input[name='" + name + "[warrantyPeriod]']").val(data.warranty);
                    	$("input[name='" + name + "[arrivalPeriod]']").val(data.arrivalPeriod);
                    	$("input[name='" + name + "[price]']").val(data.priCost);
                    	$("input[name='" + name + "[cost]']").val(data.priCost);
                    	countEquCost(goodsId,productRowNum);
                    }
                }(i)
            }
        }
    });
    $("input[name='" + name + "[productName]']").yxcombogrid_product("remove").yxcombogrid_product({// 绑定物料名称
        nameCol: 'productName',
		event : {
			'clear' : function() {
            	$("input[name='" + name + "[productId]']").val('');
            	$("input[name='" + name + "[productCode]']").val('');
            	$("input[name='" + name + "[unitName]']").val('');
            	$("input[name='" + name + "[productModel]']").val('');
            	$("input[name='" + name + "[number]']").val('');
            	$("input[name='" + name + "[warrantyPeriod]']").val('');
            	$("input[name='" + name + "[arrivalPeriod]']").val('');
            	$("input[name='" + name + "[price]']").val('');
            	$("input[name='" + name + "[cost]']").val('');
            	countEquCost(goodsId,productRowNum);
			}
		},
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (i) {
                    return function (e, row, data) {
                    	$("input[name='" + name + "[productId]']").val(data.id);
                    	$("input[name='" + name + "[productCode]']").val(data.productCode);
                    	$("input[name='" + name + "[unitName]']").val(data.unitName);
                    	$("input[name='" + name + "[productModel]']").val(data.pattern);
                    	$("input[name='" + name + "[number]']").val('1');
                    	$("input[name='" + name + "[warrantyPeriod]']").val(data.warranty);
                    	$("input[name='" + name + "[arrivalPeriod]']").val(data.arrivalPeriod);
                    	$("input[name='" + name + "[price]']").val(data.priCost);
                    	$("input[name='" + name + "[cost]']").val(data.priCost);
                    	countEquCost(goodsId,productRowNum);
                    }
                }(i)
            }
        }
    });
}