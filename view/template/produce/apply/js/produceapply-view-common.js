/**
 * 产品配置查看
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
 * LICENSE 查看方法
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

//普通查看页面
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
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编码',
			name : 'productCode'
		},{
			display : '物料名称',
			name : 'productName'
		},{
			display : '规格型号',
			name : 'productModel'
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'txtshort'
		},{
			display : '申请数量',
			name : 'produceNum',
			tclass : 'txtshort'
		},{
			name : 'exeNum',
			display : '已下达数量',
			tclass : 'txtshort'
		},{
			name : 'stockNum',
			display : '已入库数量',
			tclass : 'txtshort'
		},{
			name : 'inventory',
			display : '库存数量',
			tclass : 'txtshort'
		},{
			name : 'onwayAmount',
			display : '在途数量',
			tclass : 'txtshort'
		},{
			display : '期望交货时间',
			name : 'planEndDate',
			tclass : 'txtshort'
		},{
			display : '备注',
			name : 'remark',
			width : '20%',
			align : 'left'
		},{
			name : 'ccxx',
			display : '库存信息',
			process : function(input ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct"
					+ "&code=" + row.productCode
					+ "\",1)'><img title='库存信息' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
			}
		}],
		event : {
			reloadData : function () {
				$("#goodsTable > table > tbody > tr").each(function () {
					if ($(this).attr('rownum') >= 0) {
						$(this).css('background-color' ,'yellow');
						initCacheInfo($(this) ,$(this).attr('rownum')); //显示合同产品清单
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
									//加载完数据隐藏序号
									$("#itemTable" + rowNum + " > table > thead > tr").children().eq(0).css('visibility' ,'hidden'); //隐藏表头
									$("#itemTable" + rowNum + " > table > tbody > tr").each(function () {
										$(this).children().eq(0).css('visibility' ,'hidden'); //隐藏每一行数据的序号
									});
								}
							},
							colModel : [{
								name : 'id',
								display : 'id',
								type : 'hidden'
							},{
								name : 'proType',
								display : '物料类型'
							},{
								name : 'productCode',
								display : '物料编码',
								process : function (v ,row) {
									if (row.state == 1) {
										return v + '<span style="color:red">（已关闭）</span>';
									} else if (row.state == 2) {
										return v + '<span style="color:green">（已打回）</span>';
									} else {
										return v;
									}
								}
							},{
								name : 'productName',
								display : '物料名称'
							},{
								name : 'pattern',
								display : '规格型号'
							},{
								name : 'unitName',
								display : '单位'
							},{
								name : 'produceNum',
								display : '申请数量',
								tclass : 'txtshort'
							},{
								name : 'exeNum',
								display : '已下达数量',
								tclass : 'txtshort'
							},{
								name : 'stockNum',
								display : '已入库数量',
								tclass : 'txtshort'
							},{
								name : 'inventory',
								display : '库存数量',
								tclass : 'txtshort'
							},{
								name : 'onwayAmount',
								display : '在途数量',
								tclass : 'txtshort'
							},{
								name : 'planEndDate',
								display : '计划交货时间',
								tclass : 'txtshort'
							},{
								name : 'shipPlanDate',
								display : '计划发货日期',
								tclass : 'txtshort'
							},{
								name : 'remark',
								display : '备注',
								width : '20%',
								align : 'left'
							},{
								name : 'jmpz',
								display : '加密配置',
								process : function(input ,row) {
									if (row.licenseConfigId > 0) {
										return "<a title='"
											+ row.remark
											+ "' href='#' onclick='showLicense("
											+ row.licenseConfigId
											+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
									} else {
										return '';
									}
								}
							},{
								name : 'ccxx',
								display : '库存信息',
								process : function(input ,row) {
									return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct"
										+ "&code=" + row.productCode
										+ "\",1)'><img title='库存信息' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
								}
							}]
						});
						$("#itemTable" + rowNum).trigger('reloadData'); //加载完执行事件
						rowNum++;
					}
				}
			}
		}
	});
}

//其他部门的查看页面
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
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编码',
			name : 'productCode'
		},{
			display : '物料名称',
			name : 'productName'
		},{
			display : '规格型号',
			name : 'productModel'
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'txtshort'
		},{
			display : '申请数量',
			name : 'produceNum',
			tclass : 'txtshort'
		},{
			name : 'exeNum',
			display : '已下达数量',
			tclass : 'txtshort'
		},{
			name : 'stockNum',
			display : '已入库数量',
			tclass : 'txtshort'
		},{
			name : 'inventory',
			display : '库存数量',
			tclass : 'txtshort'
		},{
			name : 'onwayAmount',
			display : '在途数量',
			tclass : 'txtshort'
		},{
			display : '期望交货时间',
			name : 'planEndDate',
			tclass : 'txtshort'
		},{
			display : '备注',
			name : 'remark',
			width : '20%',
			align : 'left'
		},{
			name : 'ccxx',
			display : '库存信息',
			process : function(input ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct"
					+ "&code=" + row.productCode
					+ "\",1)'><img title='库存信息' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
			}
		}],
		event : {
			reloadData : function () {
				$("#goodsTable > table > tbody > tr").each(function () {
					if ($(this).attr('rownum') >= 0) {
						$(this).css('background-color' ,'yellow');
						initCacheInfo($(this) ,$(this).attr('rownum')); //显示合同产品清单
					}
				});
			}
		}
	});
}

//产品查看方法
function showGoods(thisVal ,goodsName){

	url = "?model=goods_goods_properties&action=toChooseView"
		+ "&cacheId=" + thisVal
		+ "&goodsName=" + goodsName;

	var sheight = screen.height-300;
	var swidth = screen.width-200;
	var winoption = "dialogHeight:"+sheight+"px;dialogWidth:"+ swidth +"px;status:yes;scroll:yes;resizable:yes;center:yes";

	window.open(url ,"" ,"width=900,height=500,top=200,left=200");
}

//渲染产品配置信息
function initCacheInfo(obj ,rownum) {
	//缓存表格对象
	var thisGrid = $("#goodsTable");
	var colObj = thisGrid.yxeditgrid("getCmpByRowAndCol" ,rownum ,"deploy");
	if ($("#goodsDetail_" + colObj.val()).length == 0) {
		getCacheInfo(colObj.val() ,rownum);
	}
}

//回调插入产品信息 － 单条
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