$(document)
		.ready(
				function() {

					/*
					 * validate({ "orderNum" : { required : true, custom :
					 * 'onlyNumber' } });
					 */$("#itemTable")
							.yxeditgrid(
									{
										objName : 'qualitytask[items]',
										url : '?model=produce_quality_qualitytaskitem&action=editItemJson',
										type : 'view',
										param : {
											mainId : $("#id").val()
										},
										colModel : [
												{
													name : 'id',
													display : 'id',
													type : 'hidden'
												},
												{
													name : 'productCode',
													tclass : 'txt',
													display : '物料编号',
													validation : {
														required : true
													}
												},
												{
													name : 'productName',
													tclass : 'txt',
													display : '物料名称',
													validation : {
														required : true
													}
												},
												{
													name : 'pattern',
													tclass : 'txt',
													tclass : 'readOnlyTxtItem',
													display : '型号'
												},
												{
													name : 'unitName',
													tclass : 'readOnlyTxtItem',
													display : '单位'
												},
												{
													name : 'assignNum',
													tclass : 'txt',
													display : '数量'

												},
												{
													name : 'standardNum',
													tclass : 'txt',
													display : '合格数量'
												},
												{
													name : 'checkStatus',
													tclass : 'txt',
													display : '检验状态',
													process : function(v) {
														if (v == "YJY") {
															return "已检验";
														} else {
															return "未检验";
														}
													}
												},
												{
													name : 'staticAssitem',
													display : '检验',
													type : 'statictext',
													event : {
														'click' : function(e) {
															if ($(
																	"#acceptStatus")
																	.val() == "YJS") {
																// alert(row.checkStatus);
																var rowNum = $(
																		this)
																		.data(
																				"rowNum");
																var g = $(this)
																		.data(
																				"grid");
																var rowData = $(
																		this)
																		.data(
																				"rowData");

																if ($(
																		"#itemTable_cmp_checkStatus"
																				+ rowNum)
																		.val() != "YJY") {
																	self.parent
																			.tb_remove();
																	// showThickboxWin("?model=produce_quality_qualityereport&action=toAdd&taskItemId="
																	// + $(
																	// "#itemTable_cmp_id"
																	// + rowNum)
																	// .val()
																	// +
																	// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1120");
																	showModalWin("?model=produce_quality_qualityereport&action=toAdd&taskItemId="
																			+ $(
																					"#itemTable_cmp_id"
																							+ rowNum)
																					.val());
																} else {
																	alert("此物料已检验!");
																}
															} else {
																alert("请先接收此任务!");
															}
														}
													},
													html : '<input type="button"  value="检测"  class="txt_btn_a"  />'
												} ]
									})
				})