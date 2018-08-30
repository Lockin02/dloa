var pageAttr = 'view';//����ҳ�������������Ⱦ����/��Ա������Ϣ
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
			$cmp.prepend('<img title="����༭�ļ�¼" src="images/changeedit.gif" />');
		} else if (rowData.changeTips == '2') {
			$cmp.prepend('<img title="��������ļ�¼" src="images/new.gif" />');
		} else if (rowData.changeTips == '3' || rowData.isDel == '1') {
			$cmp.prepend('<img title="���ɾ���ļ�¼" src="images/changedelete.gif" />');
			 tr.css("color", "#8B9CA4");
		}
	}

$(document).ready(function() {
	//����������
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




		// ��ȡ�����ϸ��¼
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
					// ֻ��ȡ�༭����ϸ
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
				display : '��Ա����',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '����',
				width : 60,
				readonly : true
			}, {
				name : 'pesonName',
				display : '����',
				width : 60
			}, {
				name : 'suppId',
				display : '���������Ӧ��Id',
				type : "hidden"
			},{
				name : 'suppName',
				display : '���������Ӧ��',
				width : 80
			}, {
				name : 'beginDate',
				display : '���޿�ʼ����',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '���޽�������',
				width : 80,
				type : 'date'
			}, {
				name : 'totalDay',
				display : '����',
				width : 60
			},{
				name : 'inBudgetPrice',
				display : '���������ɱ�����(Ԫ/��)',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'selfPrice',
				display : '���������ɱ�',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'outBudgetPrice',
				display : '�������(Ԫ/��)',
				width : 60
			},{
				name : 'rentalPrice',
				display : '����۸�',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'skillsRequired',
				display : '��������Ҫ��',
				width : 120
			}, {
				name : 'remark',
				display : '��ע',
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

   //��Ա����
function itemDetail() {


}