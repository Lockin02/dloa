

function operate(){
           var operateType = $("input[name='operate']:checked").val();
           if(operateType == "�й�����ͬ"){
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
     if(operate == "�޹�����ͬ"){
             showModalWin('?model=contract_contract_contract&action=toAdd&ids='
	                             + ids
								 +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
								 self.parent.parent.tb_remove();
     }else if(operate == "�й�����ͬ"){
           var contractId = $("#contractId").val();
              if(contractId == ''){
                 alert("��ѡ���ͬ��")
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
						if(ExaType == "δ����"){
						     showOpenWin('?model=contract_contract_contract&action=init&id='
									+ contractId
									+'&ids='
									+ ids
			                        + '&perm=edit'
			                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
						}else if(ExaType == "���"){
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
     //���� ��ͬ
      $(function(){
           $("#contractCode").yxcombogrid_allcontract({
							hiddenId : 'id',
							searchName : 'contractCode',
							isShowButton:false,
							isDown : false,
							gridOptions : {
								showcheckbox : false,
								param : {'prinvipalOrCreateId' : $("#userId").val(),'ExaStatusArr' : '���,δ����','states_t' : '0'},
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