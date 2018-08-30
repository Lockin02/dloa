var show_page = function(page) {
	$("#producetaskGrid").yxgrid("reload");
};
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
$(function() {
	$("#producetaskGrid")
			.yxgrid(
					{
						model : 'produce_task_producetask',
						title : '生产任务',
						param : {
							"applyDocId" : $("#applyDocId").val()
						},
						isAddAction : false,
						isDelAction : false,
						isEditAction : false,
						showcheckbox : false,
						// 列信息
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'docCode',
									display : '任务编号',
									sortable : true,
									width : 90
								},
								{
									name : 'docType',
									display : '单据类型',
									sortable : true,
									hide : true
								},
								{
									name : 'docStatus',
									display : '任务状态',
									sortable : true,
									width : '60',
									hide : true
								},
								{
									name : 'docDate',
									display : '下达日期',
									sortable : true,
									width : 70
								},
								{
									name : 'applyDocCode',
									display : '申请单编号',
									sortable : true
								},
								{
									name : 'planStartDate',
									display : '计划开始时间',
									sortable : true,
									width : 70
								},
								{
									name : 'planEndDate',
									display : '计划结束时间',
									sortable : true,
									width : 70
								},
								{
									name : 'estimateHour',
									display : '估计工作量(小时)',
									sortable : true,
									width : 90
								},
								{
									name : 'estimateDay',
									display : '计划工期(天)',
									sortable : true,
									width : 70
								},
								{
									name : 'chargeUserName',
									display : '任务责任人',
									sortable : true,
									width : 70
								},
								{
									name : 'chargeUserCode',
									display : '任务责任人code',
									sortable : true,
									hide : true
								},
								{
									name : 'urgentLevel',
									display : '优先级',
									sortable : true,
									hide : true
								},
								{
									name : 'productCode',
									display : '物料编号',
									sortable : true
								},
								{
									name : 'productName',
									display : '物料名称',
									sortable : true,
									width : 150
								},
								{
									name : 'pattern',
									display : '规格型号',
									sortable : true,
									width : 70
								},
								{
									name : 'unitName',
									display : '单位',
									sortable : true,
									width : 70

								},
								{
									name : 'jmpz',
									display : '加密配置',
									width : '70',
									process : function(v, row) {
										return "<a title='"
												+ row.remark
												+ "' href='#' onclick='showLicense("
												+ row.licenseConfigId
												+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
									}
								},
								{
									name : 'cppz',
									display : '产品配置',
									width : '70',
									sortable : true,
									process : function(v, row) {
										return "<a title='"
												+ row.remark
												+ "' href='#' onclick='showGoodsConfig("
												+ row.goodsConfigId
												+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
									}
								}, {
									name : 'taskNum',
									display : '数量',
									sortable : true,
									width : 50
								}, {
									name : 'qualityNum',
									display : '提交质检数量',
									sortable : true,
									width : 70
								}, {
									name : 'qualifiedNum',
									display : '质检合格数量',
									sortable : true,
									width : 70
								}, {
									name : 'stockNum',
									display : '入库数量',
									sortable : true,
									width : 50
								}, {
									name : 'remark',
									display : '备注',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '下达人',
									sortable : true
								}, {
									name : 'updateName',
									display : '修改人',
									sortable : true
								} ],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "任务编号",
							name : 'docCode'
						}, {
							display : "申请单编号",
							name : 'applyDocCode'
						}, {
							display : "物料名称",
							name : 'productName'
						} ]
					});
});