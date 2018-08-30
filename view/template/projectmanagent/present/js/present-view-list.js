$(function() {
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
	var addGridChangeShow = function(g, rowNum, colName, rowData,tr) {
		var $cmp = g.getCmpByRowAndCol(rowNum, colName);
		if (rowData.changeTips == '1') {
			// tr.css("background-color", "#F8846B");
			$cmp.prepend('<img title="变更编辑的产品" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {

			$cmp.prepend('<img title="变更新增的产品" src="images/new.gif" />');
		} else if (rowData.changeTips == '3') {
			$cmp
					.prepend('<img title="变更删除的产品" src="images/changedelete.gif" />');
			 tr.css("color", "#8B9CA4");
		}
	}

	/**
	 * 处理变更明细
	 */
	var beforeAddRow = function(e, rowNum, rowData, g, tr) {
		var key = rowData.id;
		if (isTemp == 1) {
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

	var presentId = $("#presentId").val();
	var isTemp = $("#isTemp").val();
	var originalId = $("#originalId").val();
	var objId = presentId;
	if (isTemp == 1) {
		objId = originalId;
	}
	// 获取变更明细记录
	var data = $.ajax({

				url : '?model=common_changeLog&action=pageJsonDetail',
				type : 'POST',
				data : {
					logObj : 'present',
					objId : objId,
					// detailType : "product",
					isLast : true,
					isGetUpdate : true,
					isTemp : isTemp
					// 只获取编辑的明细
				},
				async : false,
				dataType : 'json'
			}).responseText;
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
				var $cf = $("#" + c.changeField);
				var oldHtml = $cf.html();
				var newHtml = tranToChangeShow(c.oldValue, oldHtml);
				$cf.html(newHtml);
			}
		}
	}
	if(data.collection.length==0){
		$("#selectChange").hide();
	}
	// 产品清单
	$("#productInfo").yxeditgrid({
		objName : 'present[product]',
		url : '?model=projectmanagent_present_product&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'presentId' : $("#presentId").val()
		},
		colModel : [{
					display : '产品名称',
					name : 'conProductName',
					tclass : 'txt'
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
				}
//				, {
//					display : '单价',
//					name : 'price',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//				}, {
//					display : '金额',
//					name : 'money',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//					// }, {
//				// display : '保修期',
//				// name : 'warrantyPeriod',
//				// tclass : 'txtshort'
//			}
//			, {
//					display : '加密配置Id',
//					name : 'license',
//					type : 'hidden'
//				}, {
//					name : 'licenseButton',
//					display : '加密配置',
//					process : function(v, row) {
//						if (row.license != "") {
//							return "<a href='#' onclick='showLicense(\""
//									+ row.license + "\")'>查看</a>";
//						}
//					}
//				}
				, {
					display : '产品配置Id',
					name : 'deploy',
					type : 'hidden'
				}, {
					name : 'deployButton',
					display : '产品配置',
					process : function(v, row) {
						if (row.deploy != "") {
							return "<a href='#' onclick='showGoods(\""
									+ row.deploy + "\",\"" + row.conProductName
									+ "\")'>查看</a>";
						}
					}
				}],
		event : {
			'reloadData' : function(e) {
				initCacheInfo();
			},
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
				addGridChangeShow(g, rowNum, 'conProductName', rowData,tr);
			}
		}
	});
	// 物料清单
	var isTempA = $("#isTempA").val();
	$("#equinfo").yxeditgrid({
		objName : 'present[presentequ]',
		url : '?model=projectmanagent_present_presentequ&action=listJsonGroup',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'presentId' : $("#presentId").val(),
			'isTemp' : isTemp,
			'isDel' : '0'
		},
		colModel : [ {
                    display : '所属产品',
                    name : 'conProductName',
                    tclass : 'txt'
                }, {
					display : '物料编号',
					name : 'productNo',
					tclass : 'txt'
				},{
					display : '物料名称',
					name : 'productName',
					tclass : 'txt'
				}, {
					display : '物料版本/型号',
					name : 'productModel',
					tclass : 'txt'
				}, {
					display : '数量',
					name : 'number',
					tclass : 'txtshort'
				}, {
					display : '已执行数量',
					name : 'executedNum',
					tclass : 'txtshort'
				}, {
					display : '退库数量',
					name : 'backNum',
					tclass : 'txtshort'
				}
//				, {
//					display : '单价',
//					name : 'price',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//				}, {
//					display : '金额',
//					name : 'money',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//			   }
				]
	});
});

// 表单收缩
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		if (tempH != null) {
			tempH.style.display = "";
		}
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		if (tempH != null) {
			tempH.style.display = 'none';
		}
	}
}