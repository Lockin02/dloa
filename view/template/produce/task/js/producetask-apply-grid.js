var show_page = function(page) {
	$("#producetaskGrid").yxgrid("reload");
};
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
$(function() {
	$("#producetaskGrid")
			.yxgrid(
					{
						model : 'produce_task_producetask',
						title : '��������',
						param : {
							"applyDocId" : $("#applyDocId").val()
						},
						isAddAction : false,
						isDelAction : false,
						isEditAction : false,
						showcheckbox : false,
						// ����Ϣ
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'docCode',
									display : '������',
									sortable : true,
									width : 90
								},
								{
									name : 'docType',
									display : '��������',
									sortable : true,
									hide : true
								},
								{
									name : 'docStatus',
									display : '����״̬',
									sortable : true,
									width : '60',
									hide : true
								},
								{
									name : 'docDate',
									display : '�´�����',
									sortable : true,
									width : 70
								},
								{
									name : 'applyDocCode',
									display : '���뵥���',
									sortable : true
								},
								{
									name : 'planStartDate',
									display : '�ƻ���ʼʱ��',
									sortable : true,
									width : 70
								},
								{
									name : 'planEndDate',
									display : '�ƻ�����ʱ��',
									sortable : true,
									width : 70
								},
								{
									name : 'estimateHour',
									display : '���ƹ�����(Сʱ)',
									sortable : true,
									width : 90
								},
								{
									name : 'estimateDay',
									display : '�ƻ�����(��)',
									sortable : true,
									width : 70
								},
								{
									name : 'chargeUserName',
									display : '����������',
									sortable : true,
									width : 70
								},
								{
									name : 'chargeUserCode',
									display : '����������code',
									sortable : true,
									hide : true
								},
								{
									name : 'urgentLevel',
									display : '���ȼ�',
									sortable : true,
									hide : true
								},
								{
									name : 'productCode',
									display : '���ϱ��',
									sortable : true
								},
								{
									name : 'productName',
									display : '��������',
									sortable : true,
									width : 150
								},
								{
									name : 'pattern',
									display : '����ͺ�',
									sortable : true,
									width : 70
								},
								{
									name : 'unitName',
									display : '��λ',
									sortable : true,
									width : 70

								},
								{
									name : 'jmpz',
									display : '��������',
									width : '70',
									process : function(v, row) {
										return "<a title='"
												+ row.remark
												+ "' href='#' onclick='showLicense("
												+ row.licenseConfigId
												+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
									}
								},
								{
									name : 'cppz',
									display : '��Ʒ����',
									width : '70',
									sortable : true,
									process : function(v, row) {
										return "<a title='"
												+ row.remark
												+ "' href='#' onclick='showGoodsConfig("
												+ row.goodsConfigId
												+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
									}
								}, {
									name : 'taskNum',
									display : '����',
									sortable : true,
									width : 50
								}, {
									name : 'qualityNum',
									display : '�ύ�ʼ�����',
									sortable : true,
									width : 70
								}, {
									name : 'qualifiedNum',
									display : '�ʼ�ϸ�����',
									sortable : true,
									width : 70
								}, {
									name : 'stockNum',
									display : '�������',
									sortable : true,
									width : 50
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '�´���',
									sortable : true
								}, {
									name : 'updateName',
									display : '�޸���',
									sortable : true
								} ],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "������",
							name : 'docCode'
						}, {
							display : "���뵥���",
							name : 'applyDocCode'
						}, {
							display : "��������",
							name : 'productName'
						} ]
					});
});