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
													display : '���ϱ��',
													validation : {
														required : true
													}
												},
												{
													name : 'productName',
													tclass : 'txt',
													display : '��������',
													validation : {
														required : true
													}
												},
												{
													name : 'pattern',
													tclass : 'txt',
													tclass : 'readOnlyTxtItem',
													display : '�ͺ�'
												},
												{
													name : 'unitName',
													tclass : 'readOnlyTxtItem',
													display : '��λ'
												},
												{
													name : 'assignNum',
													tclass : 'txt',
													display : '����'

												},
												{
													name : 'standardNum',
													tclass : 'txt',
													display : '�ϸ�����'
												},
												{
													name : 'checkStatus',
													tclass : 'txt',
													display : '����״̬',
													process : function(v) {
														if (v == "YJY") {
															return "�Ѽ���";
														} else {
															return "δ����";
														}
													}
												},
												{
													name : 'staticAssitem',
													display : '����',
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
																	alert("�������Ѽ���!");
																}
															} else {
																alert("���Ƚ��մ�����!");
															}
														}
													},
													html : '<input type="button"  value="���"  class="txt_btn_a"  />'
												} ]
									})
				})