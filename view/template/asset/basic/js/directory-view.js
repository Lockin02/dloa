  $(document).ready(function(){
	      if($("#depr").text()=="1"){
          $("#depr").text("平均年限法");
          }

          if($("#depr").text()=="2"){
          $("#depr").text("工作量法");
         }
         if($("#depr").text()=="3"){
         $("#depr").text("年数总和法");
         }

         if($("#depr").text()=="4"){
         $("#depr").text("双倍余额递减法");}


          if($("#isDepr").text()=="1"){
          $("#isDepr").text("由使用状态决定是否提折旧");
          }

          if($("#isDepr").text()=="2"){
          $("#isDepr").text("不管使用状态如何一定提折旧");
         }
         if($("#isDepr").text()=="3"){
         $("#isDepr").text("不管使用状态如何一定不提折旧");
         }
		});