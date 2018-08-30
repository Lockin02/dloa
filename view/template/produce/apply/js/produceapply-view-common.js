/**
 * ��Ʒ���ò鿴
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId="
			+ thisVal;// + "&goodsName=" + goodsName;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE �鿴����
 *
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id="
			+ thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

//��ͨ�鿴ҳ��
function toView() {
	var goodsObj = $("#goodsTable");
	goodsObj.yxeditgrid({
		type : 'view',
		url: '?model=produce_apply_produceapplyitem&action=listJson',
		param: {
			mainId: $("#id").val(),
			isTemp: 0
		},
        isAddOneRow: false,
        isAdd: false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode'
		},{
			display : '��������',
			name : 'productName'
		},{
			display : '����ͺ�',
			name : 'productModel'
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'txtshort'
		},{
			display : '��������',
			name : 'produceNum',
			tclass : 'txtshort'
		},{
			name : 'exeNum',
			display : '���´�����',
			tclass : 'txtshort'
		},{
			name : 'stockNum',
			display : '���������',
			tclass : 'txtshort'
		},{
			name : 'inventory',
			display : '�������',
			tclass : 'txtshort'
		},{
			name : 'onwayAmount',
			display : '��;����',
			tclass : 'txtshort'
		},{
			display : '��������ʱ��',
			name : 'planEndDate',
			tclass : 'txtshort'
		},{
			display : '��ע',
			name : 'remark',
			width : '20%',
			align : 'left'
		},{
			name : 'ccxx',
			display : '�����Ϣ',
			process : function(input ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct"
					+ "&code=" + row.productCode
					+ "\",1)'><img title='�����Ϣ' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
			}
		}],
		event : {
			reloadData : function () {
				$("#goodsTable > table > tbody > tr").each(function () {
					if ($(this).attr('rownum') >= 0) {
						$(this).css('background-color' ,'yellow');
						initCacheInfo($(this) ,$(this).attr('rownum')); //��ʾ��ͬ��Ʒ�嵥
					}
				});
			}
		}
	});

	$.ajax({
		type : "POST",
		url : '?model=contract_contract_product&action=listJsonLimit',
		data : {
			contractId : $("#relDocId").val(),
			dir : 'ASC',
			prinvipalId : $("#saleUserId").val(),
			createId : $("#contractCreateId").val(),
			areaPrincipalId : $("#areaPrincipalId").val(),
			isDel : '0'
		},
		success : function(data) {
			if (data != 'false') {
				data = eval("(" + data + ")");
				var rowNum = 0;
				for (var i = 0; i < data.length; i++) {
					var data2 = $.ajax({
						type : "POST",
						url : '?model=produce_apply_produceapplyitem&action=listJson',
						data : {
							mainId : $("#id").val(),
							goodsId : data[i].id,
							isTemp : 0
						},
						async : false
					}).responseText;

					if (data2 != 'false' && data2) {
						goodsObj.yxeditgrid("addRow" ,rowNum ,data[i]);

						data2 = eval("(" + data2 + ")");
						var $tab = $("#goodsTable > table > tbody");

						htmlArr = '<tr><td colspan="6"><div id=itemTable' + rowNum + '></div></td></tr>';
						$tab.children().eq(0 + (rowNum * 3)).after(htmlArr);

						$("#itemTable" + rowNum).yxeditgrid({
							type : 'view',
							data : data2,
							event : {
								reloadData : function() {
									//�����������������
									$("#itemTable" + rowNum + " > table > thead > tr").children().eq(0).css('visibility' ,'hidden'); //���ر�ͷ
									$("#itemTable" + rowNum + " > table > tbody > tr").each(function () {
										$(this).children().eq(0).css('visibility' ,'hidden'); //����ÿһ�����ݵ����
									});
								}
							},
							colModel : [{
								name : 'id',
								display : 'id',
								type : 'hidden'
							},{
								name : 'proType',
								display : '��������'
							},{
								name : 'productCode',
								display : '���ϱ���',
								process : function (v ,row) {
									if (row.state == 1) {
										return v + '<span style="color:red">���ѹرգ�</span>';
									} else if (row.state == 2) {
										return v + '<span style="color:green">���Ѵ�أ�</span>';
									} else {
										return v;
									}
								}
							},{
								name : 'productName',
								display : '��������'
							},{
								name : 'pattern',
								display : '����ͺ�'
							},{
								name : 'unitName',
								display : '��λ'
							},{
								name : 'produceNum',
								display : '��������',
								tclass : 'txtshort'
							},{
								name : 'exeNum',
								display : '���´�����',
								tclass : 'txtshort'
							},{
								name : 'stockNum',
								display : '���������',
								tclass : 'txtshort'
							},{
								name : 'inventory',
								display : '�������',
								tclass : 'txtshort'
							},{
								name : 'onwayAmount',
								display : '��;����',
								tclass : 'txtshort'
							},{
								name : 'planEndDate',
								display : '�ƻ�����ʱ��',
								tclass : 'txtshort'
							},{
								name : 'shipPlanDate',
								display : '�ƻ���������',
								tclass : 'txtshort'
							},{
								name : 'remark',
								display : '��ע',
								width : '20%',
								align : 'left'
							},{
								name : 'jmpz',
								display : '��������',
								process : function(input ,row) {
									if (row.licenseConfigId > 0) {
										return "<a title='"
											+ row.remark
											+ "' href='#' onclick='showLicense("
											+ row.licenseConfigId
											+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
									} else {
										return '';
									}
								}
							},{
								name : 'ccxx',
								display : '�����Ϣ',
								process : function(input ,row) {
									return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct"
										+ "&code=" + row.productCode
										+ "\",1)'><img title='�����Ϣ' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
								}
							}]
						});
						$("#itemTable" + rowNum).trigger('reloadData'); //������ִ���¼�
						rowNum++;
					}
				}
			}
		}
	});
}

//�������ŵĲ鿴ҳ��
function toViewDepartment() {
	var goodsObj = $("#goodsTable");
	goodsObj.yxeditgrid({
		url : '?model=produce_apply_produceapplyitem&action=listJson',
		param : {
			mainId : $("#id").val(),
			isTemp : 0
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode'
		},{
			display : '��������',
			name : 'productName'
		},{
			display : '����ͺ�',
			name : 'productModel'
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'txtshort'
		},{
			display : '��������',
			name : 'produceNum',
			tclass : 'txtshort'
		},{
			name : 'exeNum',
			display : '���´�����',
			tclass : 'txtshort'
		},{
			name : 'stockNum',
			display : '���������',
			tclass : 'txtshort'
		},{
			name : 'inventory',
			display : '�������',
			tclass : 'txtshort'
		},{
			name : 'onwayAmount',
			display : '��;����',
			tclass : 'txtshort'
		},{
			display : '��������ʱ��',
			name : 'planEndDate',
			tclass : 'txtshort'
		},{
			display : '��ע',
			name : 'remark',
			width : '20%',
			align : 'left'
		},{
			name : 'ccxx',
			display : '�����Ϣ',
			process : function(input ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct"
					+ "&code=" + row.productCode
					+ "\",1)'><img title='�����Ϣ' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
			}
		}],
		event : {
			reloadData : function () {
				$("#goodsTable > table > tbody > tr").each(function () {
					if ($(this).attr('rownum') >= 0) {
						$(this).css('background-color' ,'yellow');
						initCacheInfo($(this) ,$(this).attr('rownum')); //��ʾ��ͬ��Ʒ�嵥
					}
				});
			}
		}
	});
}

//��Ʒ�鿴����
function showGoods(thisVal ,goodsName){

	url = "?model=goods_goods_properties&action=toChooseView"
		+ "&cacheId=" + thisVal
		+ "&goodsName=" + goodsName;

	var sheight = screen.height-300;
	var swidth = screen.width-200;
	var winoption = "dialogHeight:"+sheight+"px;dialogWidth:"+ swidth +"px;status:yes;scroll:yes;resizable:yes;center:yes";

	window.open(url ,"" ,"width=900,height=500,top=200,left=200");
}

//��Ⱦ��Ʒ������Ϣ
function initCacheInfo(obj ,rownum) {
	//���������
	var thisGrid = $("#goodsTable");
	var colObj = thisGrid.yxeditgrid("getCmpByRowAndCol" ,rownum ,"deploy");
	if ($("#goodsDetail_" + colObj.val()).length == 0) {
		getCacheInfo(colObj.val() ,rownum);
	}
}

//�ص������Ʒ��Ϣ �� ����
function getCacheInfo(cacheId ,rowNum) {
	$.ajax({
		type : "POST",
		url : "?model=goods_goods_goodscache&action=getCacheConfig",
		data : {
			"id" : cacheId
		},
		async : false,
		success : function(data) {
			if(data != "") {
				$("#goodsTable > table > tbody > tr[rowNum="+ rowNum + "]").after(data);
				$("#goodsDiv_" + cacheId).hide();
				var showHtml = '<div onclick="'
							+ 'showAndHideDiv(\'' + cacheId + 'Img\',\'goodsDiv_' + cacheId
							+ '\')">&nbsp;<img src="images/icon/info_right.gif" id="' + cacheId + 'Img"/></div>';
				$("#goodsDetail_" + cacheId).children(":eq(0)").append(showHtml);
			}
		}
	});
}