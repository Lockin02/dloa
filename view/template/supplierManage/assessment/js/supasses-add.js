$(document).ready(function() {
	$("#menberName").yxselect_user({
		hiddenId : 'menberId',
		mode:"check"
			});
	uploadfile = createSWFUpload({
		"serviceType": "oa_supp_suppasses"
	});


	/**
	 * ��֤��Ϣ
	 */
	validate({
//		"schemeCode" : {
//			required : true
//		},
		"suppName" : {
			required : true
		}
	});
	$("#newsupp").hide();
	$("#yearsupp").hide();
	$("#assessType").bind('change',function(){//���ݲ�ͬ�Ŀ������ͣ����ػ�����ʾ����Ӧ����Դ���ı���
			$("#suppId").val("");
			$("#suppName").val("");
			$("#suppLinkName").val("");
			$("#suppTel").val("");
			$("#suppAddress").val("");
			$("#mainProduct").val("");
			$("#suppLinkName").val("");
			$("#suppassesTable").html("");
			$("#assessName").val("");
			$("#assessId").val("");
			$("#assessCode").val("");
			$("#totalNum").val("");
		if($(this).val()=="xgyspg"){
			$("#newsupp").show();
			$("#yearsupp").hide();
		}
		if($(this).val()=="gysjd"){
			$("#newsupp").hide();
			$("#yearsupp").show();
		}
		if($(this).val()=="gysnd"){
			$("#newsupp").hide();
			$("#yearsupp").show();
		}
		if($(this).val()==""){//���û��ѡ���������ͣ��������������
			$("#newsupp").hide();
			$("#yearsupp").hide();
			$("#assessName").yxcombogrid_scheme("remove");
			$("#suppName").yxcombogrid_supplier("remove");
			$("#suppassesTable").html("");
		}
		if($(this).val()!=""){
			if($(this).val()=="xgyspg"){
				var suppGrad="E";
			}else{
				var suppGrad="A,B,C";
			}
			var supassType=$(this).val();
			$("#suppName").yxcombogrid_supplier("remove");
			$("#suppName").yxcombogrid_supplier({
						hiddenId : 'suppId',
						gridOptions : {
							showcheckbox : false,
							param:{suppGrade:suppGrad},
							event : {
								'row_dblclick' : function(e, row, data) {
									 $.ajax({//�ж��Ƿ��ѽ��п���
									    type: "POST",
									    url: "?model=supplierManage_assessment_supasses&action=isAsses",
									    data: { suppId: data.id,
									    		supassType:supassType
									    	},
									    async: false,
									    success: function(msg){
									   	   if(msg==1){
												$("#suppLinkName").val("");
												$("#suppTel").val("");
												$("#suppAddress").val("");
												$("#mainProduct").val("");
												$("#suppLinkName").val("");
					//							$("#suppLinkName").val("");
												//�����Ӧ�̴�����ϵ����Ϣ����Ĭ�ϴ�����һ����Ϣ
												$.post(
														"?model=supplierManage_formal_sfcontact&action=getLinkmanInfo",
														{
															suppId : data.id
														}, function(linkman) {
															if(linkman){
					        									var o = eval("(" + linkman + ")");
																$("#suppLinkName").val(o.name);
															}
														});
												$("#suppTel").val(data.plane);
												$("#suppAddress").val(data.address+"  "+data.zipCode);
												$("#mainProduct").val(data.products);
									   	   }else{
									   	   		alert("�ù�Ӧ���ѽ�����������");
												$("#suppId").val("");
												$("#suppName").val("");
												$("#suppLinkName").val("");
												$("#suppTel").val("");
												$("#suppAddress").val("");
												$("#mainProduct").val("");
												$("#suppLinkName").val("");
									   	   }
										}
									});
								}
							}
						}
			});
			$("#suppName").yxcombogrid_supplier("showCombo");
			$("#assessName").yxcombogrid_scheme("remove");
			$("#assessName").yxcombogrid_scheme({
						hiddenId : 'assessId',
						width:500,
						gridOptions : {
							showcheckbox : false,
							param:{"schemeType":supassType},
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#assessCode").val(data.schemeCode);
									$("#suppassesTable").html("");
									$("#suppassesTable").yxeditgrid({
											objName : 'supasses[assesmentitem]',
											url : '?model=supplierManage_assessment_assesmentitem&action=addItemJson',
											param:{parentId:data.id},
											isAddAndDel : false,
											colModel : [{
												display : '������Ŀ',
												name : 'assesProName',
												type : 'statictext'
											},{
												display : '������Ŀ',
												name : 'assesProName',
												type : 'hidden'
											}, {
												display : '����ָ��',
												name : 'assesStandard',
												type : 'statictext'
											},  {
												display : '����ָ��',
												name : 'assesStandard',
												type : 'hidden'
											}, {
												display : 'ָ��Ȩ��',
												name : 'assesProportion',
												type : 'statictext'
											},  {
												display : 'ָ��Ȩ��',
												name : 'assesProportion',
												type : 'hidden'
											}, {
												display : '����˵��',
												name : 'assesExplain',
												type : 'statictext',
												width:550
											}, {
												display : '����˵��',
												name : 'assesExplain',
												type : 'hidden'
											}, {
												display : '�÷�[*]',
												name : 'assesScore',
												tclass : 'txtshort',
												validation : {
													custom : ['percentageNum']
												},
												event : {
													blur : function() {
														var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
														if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
															if (isNaN(this.value)) {
																this.value = "";
	//															return false;
															}
														}
														var rownum = $(this).data('rowNum');// �ڼ���
														var colnum = $(this).data('colNum');// �ڼ���
														var grid = $(this).data('grid');// ������
														var assesProportion = grid.getCmpByRowAndCol(rownum, 'assesProportion').val();
														var assesScore = $(this).val();
														assesProportion = parseFloat(assesProportion);
														assesScore = parseFloat(assesScore);
														if (assesScore > assesProportion) {
															alert("�÷ֲ��ܳ���ָ��Ȩ�أ�"+assesProportion);
															$(this).val("");
														}
														check_all();
													}
												}
											}, {
												display : '����˵��',
												name : 'assesRemark'
											}]
										});
								}
							}
						}
			});
			$("#assessName").yxcombogrid_scheme("showCombo");
		}
	});
  })

  // �����ܷ���
	function check_all() {
		var totalNum = 0;
		var cmps = $("#suppassesTable").yxeditgrid("getCmpByCol", "assesScore");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),2);
		});
		$("#totalNum").val(totalNum);
		checkGrade(totalNum);//���ֹ�Ӧ�̵ȼ�
	//	return false;
	}

	//���ֹ�Ӧ�̵ȼ�
	function checkGrade(totalNum){
		var assessType=$("#assessType").val();
		if(assessType=="gysjd"||assessType=="gysnd"){
			if(totalNum>90){
				$("#suppGrade").val("A");
			}else if(totalNum==75||totalNum>75){
				$("#suppGrade").val("B");
			}else if(totalNum>60||totalNum==60){
				$("#suppGrade").val("C");
			}else if(totalNum<60){
				$("#suppGrade").val("D");
			}
		}else if(assessType=="xgyspg"){
			if(totalNum>70||totalNum==70){
				$("#suppGrade").val("C");
			}
		}
	}

  //��֤�Ƿ�ѡ������������
  function checkSelectSupp(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("����ѡ����������");
	}
  }

  //��֤�Ƿ�ѡ������������
  function checkAssesType(){
	var assessType=$("#assessType").val();
	if(assessType==""){
		alert("����ѡ����������");
	}
  }

  //ֱ���ύ����
function toSubmit(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=add&actType=audit";
}