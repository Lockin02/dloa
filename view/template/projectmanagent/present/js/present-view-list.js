$(function() {
	/**
	 * ת�ɱ����ɫ����
	 */
	var tranToChangeShow = function(oldVal, newVal) {
		var newHtml = "<font color='red'><span class='oldValue' style='display:none'>"
				+ oldVal
				+ "</span><span class='compare' style='display:none'>=></span><span class='newValue'>"
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
		} else if (rowData.changeTips == '3') {
			$cmp
					.prepend('<img title="���ɾ���Ĳ�Ʒ" src="images/changedelete.gif" />');
			 tr.css("color", "#8B9CA4");
		}
	}

	/**
	 * ��������ϸ
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
	// ��ȡ�����ϸ��¼
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
					// ֻ��ȡ�༭����ϸ
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
	// ��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'present[product]',
		url : '?model=projectmanagent_present_product&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'presentId' : $("#presentId").val()
		},
		colModel : [{
					display : '��Ʒ����',
					name : 'conProductName',
					tclass : 'txt'
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
				}
//				, {
//					display : '����',
//					name : 'price',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//				}, {
//					display : '���',
//					name : 'money',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//					// }, {
//				// display : '������',
//				// name : 'warrantyPeriod',
//				// tclass : 'txtshort'
//			}
//			, {
//					display : '��������Id',
//					name : 'license',
//					type : 'hidden'
//				}, {
//					name : 'licenseButton',
//					display : '��������',
//					process : function(v, row) {
//						if (row.license != "") {
//							return "<a href='#' onclick='showLicense(\""
//									+ row.license + "\")'>�鿴</a>";
//						}
//					}
//				}
				, {
					display : '��Ʒ����Id',
					name : 'deploy',
					type : 'hidden'
				}, {
					name : 'deployButton',
					display : '��Ʒ����',
					process : function(v, row) {
						if (row.deploy != "") {
							return "<a href='#' onclick='showGoods(\""
									+ row.deploy + "\",\"" + row.conProductName
									+ "\")'>�鿴</a>";
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
	// �����嵥
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
                    display : '������Ʒ',
                    name : 'conProductName',
                    tclass : 'txt'
                }, {
					display : '���ϱ��',
					name : 'productNo',
					tclass : 'txt'
				},{
					display : '��������',
					name : 'productName',
					tclass : 'txt'
				}, {
					display : '���ϰ汾/�ͺ�',
					name : 'productModel',
					tclass : 'txt'
				}, {
					display : '����',
					name : 'number',
					tclass : 'txtshort'
				}, {
					display : '��ִ������',
					name : 'executedNum',
					tclass : 'txtshort'
				}, {
					display : '�˿�����',
					name : 'backNum',
					tclass : 'txtshort'
				}
//				, {
//					display : '����',
//					name : 'price',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//				}, {
//					display : '���',
//					name : 'money',
//					tclass : 'txtshort',
//					process : function(v){
//						return moneyFormat2(v);
//					}
//			   }
				]
	});
});

// ������
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