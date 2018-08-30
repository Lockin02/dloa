

function operate(){
           var operateType = $("input[name='operate']:checked").val();
           if(operateType == "有关联合同"){
//              document.getElementById("orderTypeDis").style.display="none";
              document.getElementById("orderDis").style.display="";
           }else{
//              document.getElementById("orderTypeDis").style.display="";
              document.getElementById("orderDis").style.display="none";
           }
      }
function sub(){
     var operate = $("input[name='operate']:checked").val();
     var ids = $("#ids").val()
     if(operate == "无关联合同"){
             showModalWin('?model=contract_contract_contract&action=toAdd&ids='
	                             + ids
								 +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
								 self.parent.parent.tb_remove();
     }else if(operate == "有关联合同"){
           var contractId = $("#contractId").val();
              if(contractId == ''){
                 alert("请选择合同！")
	          }else{
	          	    	var ExaType = $.ajax({
						    type : 'POST',
						    url : "?model=projectmanagent_borrow_borrow&action=toconExastatusType",
						    data:{
						        contractId : contractId
						    },
						    async: false,
						    success : function(data){
							}
						}).responseText;
						  ExaType = strTrim(ExaType);
						if(ExaType == "未审批"){
						     showOpenWin('?model=contract_contract_contract&action=init&id='
									+ contractId
									+'&ids='
									+ ids
			                        + '&perm=edit'
			                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
						}else if(ExaType == "完成"){
						    showOpenWin('?model=contract_contract_contract&action=toChange&id='
							        + contractId
							        +'&ids='
									+ ids
							        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
						}
					self.parent.parent.tb_remove();
	         }
          }
}
     //加载 合同
      $(function(){
           $("#contractCode").yxcombogrid_allcontract({
							hiddenId : 'id',
							searchName : 'contractCode',
							isShowButton:false,
							isDown : false,
							gridOptions : {
								showcheckbox : false,
								param : {'prinvipalOrCreateId' : $("#userId").val(),'ExaStatusArr' : '完成,未审批','states_t' : '0'},
								event : {
									'row_dblclick' : function(e, row, data) {
										$("#contractCode").val(data.contractCode);
										$("#contractId").val(data.id);
										$("#contractType").val(data.contractType);

									}
								}
							}
						});
      });