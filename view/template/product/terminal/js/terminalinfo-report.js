
	//������������
	function mulSelectSet(thisObj,code){
		thisObj.next().find("input").each(function(i,n){		
			if($(this).attr('class') == 'combo-text validatebox-text'){
				$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
			}
		});
	}

	//��ֵ��ѡֵ -- ��ʼ����ֵ
	function mulSelectInit(thisObj){
		//��ʼ����Ӧ����
		var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
		if(objVal != "" ){
			thisObj.combobox("setValues",objVal.split(','));
		}
	}
	//��ʼ�����鲹�䷽ʽ��Ϣ
	function initLevel(id,data){

			//��ȡ���鲹�䷽ʽ
			var positionLevelArr = $('#'+id+"Hidden").val().split(",");
			var str;
			//���鲹�䷽ʽ��Ⱦ
			var positionLevelObj = $('#'+id);
			positionLevelObj.combobox({
				data : data,
				multiple:true,
				editable : false,
				valueField:'text',
	            textField:'text',
		        formatter: function(obj){
		        	//�ж� ���û�г�ʼ�������У���ѡ��
		        	if($.inArray(obj.text,positionLevelArr) == -1){
		        		str = "<input type='checkbox' id='"+id+"_"+ obj.id +"' value='"+ obj.text +"'/> " + obj.text;
		        	}else{
		        		str = "<input type='checkbox' id='"+id+"_"+ obj.id +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
		        	}
					return str;
		        },
				onSelect : function(obj){
					//checkbox��ֵ
					$("#"+id+"_" + obj.id).attr('checked',true);
					//���ö����µ�ѡ����
					mulSelectSet(positionLevelObj);
				},
				onUnselect : function(obj){
					//checkbox��ֵ
					$("#" +id+"_"+ obj.id).attr('checked',false);
					//����������
					mulSelectSet(positionLevelObj);
				}
			});

			//�ͻ����ͳ�ʼ����ֵ
			mulSelectInit(positionLevelObj);
	}

	//ѡ����������ְλʱ�����������ֵ�����
	function initLevelWY(id,code){
		
		var data=$.ajax({
						url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode='+code,
						type:'post',
						dataType:'json',
						//async:false,	
						success:function(data){
							var dataArr=[];							
							//data=eval("("+data+")");console.info(data);
							for(i=0;i<data.length;i++){
								dataArr.push({"id":data[i].id,"text":data[i].text});
							}
							initLevel(id,dataArr);
						}
					});
		
	}
	$(function(){
		//initLevelWY('proType','CPFWLX');
		var $situation=$("#situation_");
		if($situation.val()!=''){
			$("#situation").val($situation.val());
		}
		initLevelWY('deviceType','SBLX');
		initLevelWY('supportNetwork','ZCWL');
		initLevelWY('versionStatus','BBZT');
		 $("#proType").yxcombogrid_terminalProduct('remove');
		 $("#proType").yxcombogrid_terminalProduct({
		        nameCol: 'productName',
		        hiddenId: 'proTypeHidden',
		        width : 300,
		 		height : 200,
		        gridOptions: {		 		
		            event: {
		                'after_row_check': function(e, row, data) {
		                   
		                }
		            }
		        }
		    });
	})
	
	