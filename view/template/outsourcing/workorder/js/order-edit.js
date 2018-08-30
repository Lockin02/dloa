$(document).ready(function() {
	validate({
				"approvalCode" : {
					required : true
				},
				"suppName" : {
					required : true
				},
				"suppType" : {
					required : true
				},
				"natureCode" : {
					required : true
				},
				"projectName" : {
					required : true
				}
			});
	detail();
	//�����Ӧ������
	$("#suppName").yxcombogrid_outsupplier({
			hiddenId : 'suppId',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e,row,data) {
	                                    $("#suppCode").val(data.suppCode);
	                                    $("#itemTable").yxeditgrid('removeAll');
					}
				
				}
			},
			event : {
				'clear' : function() {
					$("#suppCode").val("");
				}
			}
	});
	//����ʡ��
	function getProvince(obj){
		//��ȡʡ��
		  var pro = new Array(2);
		  pro[0] = 0;
          $.ajax({
			    type : 'POST',
			    url : "?model=system_procity_province&action=pageJson",
			    data:{
			        countryId : 1,
					pageSize : 999
			    },
			    async: false,
			    success : function(data){
			    	var str = obj.substring(0,2);
			    	var o = eval("(" + data + ")");
					var provinceArr = o.collection;
					for (var i = 0, l = provinceArr.length; i < l; i++) {
						if(str == provinceArr[i].provinceName){
							pro[0] = str;
							pro[1] = provinceArr[i].id;
							break;
						}
					}
					if(pro == 0){
						str = obj.substring(0,3);
					    for (var i = 0, l = provinceArr.length; i < l; i++) {
							if(str == provinceArr[i].provinceName){
								pro[0] = str;
								pro[1] = provinceArr[i].id;
								break;
							}
						}
					}
				}
			});
			return pro;
	}
		//���������
	$("#approvalCode").yxcombogrid_outsourApprova({
			hiddenId : 'approvalId',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e,row,data) {
						$("#suppName").val(data.suppName);
						$("#suppId").val(data.suppId);
						$("#suppCode").val(data.suppCode);
						$("#projectName").val(data.projectName);
						$("#projectCode").val(data.projectCode);
						$("#projectId").val(data.projectId);
						$("#projectManager").val(data.projectManangerName);
						$("#projectManagerId").val(data.projectManangerId);
						$("#suppType").val(data.outsourcing);
						$("#suppTypeName").val(data.outsourcingName);
						$("#natureCode").val(data.projectType);
						$("#natureName").val(data.projectTypeName);
						$("#number").val(data.personSum);
						var projectAddress = getProvince(data.projectAddress);
						if(projectAddress[0]!=0){
							$("#province").val(projectAddress[1]);
							$("#provinceName").val(projectAddress[0]);
						}
	                    $("#itemTable").yxeditgrid('removeAll');
					}
				}
			},
		event : {
			'clear' :function(){
						$("#suppName").val("");
						$("#suppId").val("");
						$("#suppCode").val("");
						$("#projectName").val("");
						$("#projectCode").val("");
						$("#projectId").val("");
						$("#projectManager").val("");
						$("#projectManagerId").val("");
						$("#suppType").val("");
						$("#suppTypeName").val("");
						$("#natureCode").val("");
						$("#natureName").val("");
						$("#province").val("");
						$("#provinceName").val("");
						$("#number").val("");
			}
		}
	});
//	$('#suppType').change(function() {
//				$('#suppTypeName').val($(this).find("option:selected").text());
//			});
//	$('#natureCode').change(function() {
//		$('#natureName').val($(this).find("option:selected").text());
//		var provinceId = $(this).val();
//	});
	
	//�������֤
//     function checkIDCard (obj)
//{
//	str = $(obj).val();
//	if(isIdCardNo(str)){
//	}else{
//		$(obj).val('');
//	}
//
//}
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  
})
function detail(){
	$("#itemTable").yxeditgrid({		
		objName : 'order[orderequ]',
		url : "?model=outsourcing_workorder_orderequ&action=listJson",
		param : {
			parentId : $("#ID").val(),
			dir : 'ASC'
		},
//		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
			},{
			display : 'ʩ����Ա',
			name : 'personName',
			tclass : 'txtshort',
			validation : {
						required : true
					},
			process : function($input,row){
				var rowNum = $input.data("rowNum");
//				$input.yxcombogrid_outsourcPersonnel('remove');
				$input.yxcombogrid_outsourcPersonnel({
					nameCol : 'itemTable_cmp_personName'.rowNum,
					gridOptions : {
					param:{
						suppId:$("#suppId").val()
						},
						event : {
							row_dblclick : function(e, row, data) {
								$("#itemTable").yxeditgrid("getCmpByRowAndCol",rowNum,"personId").val(data.id);
								$("#itemTable").yxeditgrid("getCmpByRowAndCol",rowNum,"parentId").val($("#ID").val());
								$("#itemTable").yxeditgrid("getCmpByRowAndCol",rowNum,"IdCard").val(data.identityCard);
								$("#itemTable").yxeditgrid("getCmpByRowAndCol",rowNum,"phone").val(data.mobile);
								$("#itemTable").yxeditgrid("getCmpByRowAndCol",rowNum,"email").val(data.email);
							}
						}
					}
					
				});
			}
		}, {
			display : 'ʩ����ԱID',
			name : 'personId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '����ID',
			name : 'parentId',
			tclass : 'txt',
			type : 'hidden'
		},{
			display : '���֤����',
			name : 'IdCard',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '�ֻ�',
			name : 'phone',
			tclass : 'txt',
			validation : {
				required : true,
				custom : ['mobilephone']
			}
		}, {
			display : '����',
			name : 'email',
			tclass : 'txt',
			validation : {
				required : true,
				custom : ['email']
			}
		}, {
			display : '��ĿԤ�ƿ�ʼʱ��',
			name : 'exceptStart',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		}, {
			display : '��ĿԤ�ƽ���ʱ��',
			name : 'exceptEnd',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			}
		}, {
			display : '����(Ԫ)',
			name : 'price',
			tclass : 'txtshort'
		}, {
			display : '���㷽ʽ',
			name : 'payWay',
			tclass : 'txt'
		}, {
			display : '����˵��',
			name : 'payExplain',
			tclass : 'txt'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	
	$("#itemTable").attr('style',"width:1300px");
}