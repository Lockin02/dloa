
	//隐藏区域设置
	function mulSelectSet(thisObj,code){
		thisObj.next().find("input").each(function(i,n){		
			if($(this).attr('class') == 'combo-text validatebox-text'){
				$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
			}
		});
	}

	//设值多选值 -- 初始化赋值
	function mulSelectInit(thisObj){
		//初始化对应内容
		var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
		if(objVal != "" ){
			thisObj.combobox("setValues",objVal.split(','));
		}
	}
	//初始化建议补充方式信息
	function initLevel(id,data){

			//获取建议补充方式
			var positionLevelArr = $('#'+id+"Hidden").val().split(",");
			var str;
			//建议补充方式渲染
			var positionLevelObj = $('#'+id);
			positionLevelObj.combobox({
				data : data,
				multiple:true,
				editable : false,
				valueField:'text',
	            textField:'text',
		        formatter: function(obj){
		        	//判断 如果没有初始化数组中，则选中
		        	if($.inArray(obj.text,positionLevelArr) == -1){
		        		str = "<input type='checkbox' id='"+id+"_"+ obj.id +"' value='"+ obj.text +"'/> " + obj.text;
		        	}else{
		        		str = "<input type='checkbox' id='"+id+"_"+ obj.id +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
		        	}
					return str;
		        },
				onSelect : function(obj){
					//checkbox设值
					$("#"+id+"_" + obj.id).attr('checked',true);
					//设置对象下的选中项
					mulSelectSet(positionLevelObj);
				},
				onUnselect : function(obj){
					//checkbox设值
					$("#" +id+"_"+ obj.id).attr('checked',false);
					//设置隐藏域
					mulSelectSet(positionLevelObj);
				}
			});

			//客户类型初始化赋值
			mulSelectInit(positionLevelObj);
	}

	//选择网优类型职位时，加载数据字典内容
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
	
	