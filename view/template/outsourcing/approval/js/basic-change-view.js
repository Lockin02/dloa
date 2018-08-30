var pageAttr = 'view';//配置页面操作，用于渲染整包/人员租赁信息
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
			$cmp.prepend('<img title="变更编辑的记录" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {
			$cmp.prepend('<img title="变更新增的记录" src="images/new.gif" />');
		} else if (rowData.changeTips == '3' || rowData.isDel == '1') {
			$cmp.prepend('<img title="变更删除的记录" src="images/changedelete.gif" />');
			 tr.css("color", "#8B9CA4");
		}
	}

$(document).ready(function() {
	//变更外包类型
	outsourType();

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




		// 获取变更明细记录
	var data = $.ajax({

				url : '?model=common_changeLog&action=pageJsonDetail',
				type : 'POST',
				data : {
					logObj : 'outsourcingapproval',
					objId : $("#originalId").val(),
					// detailType : "product",
					isLast : true,
					isGetUpdate : true,
					isTemp : 1
					// 只获取编辑的明细
				},
				async : false,
				dataType : 'json'
			}).responseText;
	 //alert(data)
	data = eval("(" + data + ")");
	var changeDetailObj = {};
	var key = "detailId";
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
				if(oldHtml != '')
				  $cf.html(newHtml);
			}
		}
	}

	var thisObj ;
//	$.ajax({
//	    type: "POST",
//	    url: "?model=common_changeLog&action=getChangeObjs",
//	    data: {"tempId" : $("#id").val() , "logObj" : "outsourcingapproval"},
//	    async: false,
//	    success: function(data){
//	   		if(data){
//				var rtArr = data.split(",");
//				for(var i = 0; i < rtArr.length ; i++){
//					thisObj = $("#" + rtArr[i]);
//					if(thisObj.html() != ""){
//						thisObj.attr('style','color:red')
//					}
//				}
//	   		}
//		}
//	});

	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}

		var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			url : '?model=outsourcing_approval_persronRental&action=listJson',
			param : {
				dir : 'ASC',
				mainId :$("#id").val()
			},
			type : 'view',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '人员级别',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '级别',
				width : 60,
				readonly : true
			}, {
				name : 'pesonName',
				display : '姓名',
				width : 60
			}, {
				name : 'suppId',
				display : '归属外包供应商Id',
				type : "hidden"
			},{
				name : 'suppName',
				display : '归属外包供应商',
				width : 80
			}, {
				name : 'beginDate',
				display : '租赁开始日期',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '租赁结束日期',
				width : 80,
				type : 'date'
			}, {
				name : 'totalDay',
				display : '天数',
				width : 60
			},{
				name : 'inBudgetPrice',
				display : '服务人力成本单价(元/天)',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'selfPrice',
				display : '服务人力成本',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'outBudgetPrice',
				display : '外包单价(元/天)',
				width : 60
			},{
				name : 'rentalPrice',
				display : '外包价格',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'skillsRequired',
				display : '工作技能要求',
				width : 120
			}, {
				name : 'remark',
				display : '备注',
				align:'left',
				width : 120
			}],
		event : {
			beforeAddRow : function(e, rowNum, rowData, g, tr) {
				var key = rowData.id;
				if ( 1) {
					key = rowData.originalId;
				}
				var detail = changeDetailObj["d" + key];
				if (detail) {
					for (var i = 0; i < detail.length; i++) {
						var changeField = detail[i].changeField;
						if (changeField == "inBudgetPrice" || changeField == "selfPrice"|| changeField == "outBudgetPrice"|| changeField == "rentalPrice") {
							rowData[changeField] = tranToChangeShow(
									moneyFormat2(detail[i].oldValue),
									moneyFormat2(rowData[detail[i].changeField]));
						}else{
							rowData[changeField] = tranToChangeShow(
									detail[i].oldValue,
									rowData[detail[i].changeField]);
						}
					}
				}
			},
			addRow : function(e, rowNum, rowData, g, tr) {
				addGridChangeShow(g, rowNum, 'personLevelName', rowData,tr);
			}
		}
		});
		tableHead();
	}


});

var isTemp=1;
	var changeDetailObj = {};
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

   //人员租赁
function itemDetail() {


}