<?php
echo "<html>\r\n<head>\r\n<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<style>\r\n.menulines{}\r\n</style>\r\n\r\n<script Language=\"JavaScript\">\r\nvar parent_window = parent.dialogArguments;\r\n\r\n\r\n\r\nfunction click_user(user_id)\r\n{\r\n  TO_VAL=parent_window.form1.";
echo $toid;
echo ".value;\r\n  targetelement=document.all(user_id);\r\n  user_name=targetelement.name;\r\n  if(TO_VAL.indexOf(\",\"+user_id+\",\")>0 || TO_VAL.indexOf(user_id+\",\")==0)\r\n  {\r\n    if(TO_VAL.indexOf(user_id+\",\")==0)\r\n    {\r\n       parent_window.form1.";
echo $toid;
echo ".value=parent_window.form1.";
echo $toid;
echo ".value.replace(user_id+\",\",\"\");\r\n       parent_window.form1.";
echo $toname;
echo ".value=parent_window.form1.";
echo $toname;
echo ".value.replace(user_name+\",\",\"\");\r\n       borderize_off(targetelement);\r\n    }\r\n    if(TO_VAL.indexOf(\",\"+user_id+\",\")>0)\r\n    {\r\n       parent_window.form1.";
echo $toid;
echo ".value=parent_window.form1.";
echo $toid;
echo ".value.replace(\",\"+user_id+\",\",\",\");\r\n       parent_window.form1.";
echo $toname;
echo ".value=parent_window.form1.";
echo $toname;
echo ".value.replace(\",\"+user_name+\",\",\",\");\r\n       borderize_off(targetelement);\r\n    }\r\n  }\r\n  else\r\n  {\r\n    parent_window.form1.";
echo $toid;
echo ".value+=user_id+\",\";\r\n    parent_window.form1.";
echo $toname;
echo ".value+=user_name+\",\";\r\n    borderize_on(targetelement);\r\n  }\r\n\r\n}\r\n\r\nfunction borderize_on(targetelement)\r\n{\r\n color=\"#003FBF\";\r\n targetelement.style.borderColor=\"black\";\r\n targetelement.style.backgroundColor=color;\r\n targetelement.style.color=\"white\";\r\n targetelement.style.fontWeight=\"bold\";\r\n}\r\n\r\nfunction borderize_off(targetelement)\r\n{\r\n  targetelement.style.backgroundColor=\"\";\r\n  targetelement.style.borderColor=\"\";\r\n  targetelement.style.color=\"\";\r\n  targetelement.style.fontWeight=\"\";\r\n}\r\n\r\nfunction begin_set()\r\n{\r\n  TO_VAL=parent_window.form1.";
echo $toid;
echo ".value;\r\n\r\n  for (step_i=0; step_i<document.all.length; step_i++)\r\n  {\r\n    if(document.all(step_i).className==\"menulines\")\r\n    {\r\n       user_id=document.all(step_i).id;\r\n       if(TO_VAL.indexOf(\",\"+user_id+\",\")>0 || TO_VAL.indexOf(user_id+\",\")==0)\r\n          borderize_on(document.all(step_i));\r\n    }\r\n  }\r\n}\r\n\r\nfunction add_all(flag)\r\n{\r\n  TO_VAL=parent_window.form1.";
echo $toid;
echo ".value;\r\n  for (step_i=0; step_i<document.all.length; step_i++)\r\n  {\r\n    if(document.all(step_i).className==\"menulines\")\r\n    {\r\n       if(flag!=document.all(step_i).flag)\r\n          continue;\r\n       user_id=document.all(step_i).id;\r\n       user_name=document.all(step_i).name;\r\n\r\n       if(TO_VAL.indexOf(\",\"+user_id+\",\")<0 && TO_VAL.indexOf(user_id+\",\")!=0)\r\n       {\r\n         parent_window.form1.";
echo $toid;
echo ".value+=user_id+\",\";\r\n         parent_window.form1.";
echo $toname;
echo ".value+=user_name+\",\";\r\n         borderize_on(document.all(step_i));\r\n       }\r\n    }\r\n  }\r\n}\r\n\r\nfunction del_all(flag)\r\n{\r\n  for (step_i=0; step_i<document.all.length; step_i++)\r\n  {\r\n    TO_VAL=parent_window.form1.";
echo $toid;
echo ".value;\r\n    if(document.all(step_i).className==\"menulines\")\r\n    {\r\n       if(flag!=document.all(step_i).flag)\r\n          continue;\r\n       user_id=document.all(step_i).id;\r\n       user_name=document.all(step_i).name;\r\n       if(TO_VAL.indexOf(user_id+\",\")==0)\r\n       {\r\n          parent_window.form1.";
echo $toid;
echo ".value=parent_window.form1.";
echo $toid;
echo ".value.replace(user_id+\",\",\"\");\r\n          parent_window.form1.";
echo $toname;
echo ".value=parent_window.form1.";
echo $toname;
echo ".value.replace(user_name+\",\",\"\");\r\n          borderize_off(document.all(step_i));\r\n       }\r\n       if(TO_VAL.indexOf(\",\"+user_id+\",\")>0)\r\n       {\r\n          parent_window.form1.";
echo $toid;
echo ".value=parent_window.form1.";
echo $toid;
echo ".value.replace(\",\"+user_id+\",\",\",\");\r\n          parent_window.form1.";
echo $toname;
echo ".value=parent_window.form1.";
echo $toname;
echo ".value.replace(\",\"+user_name+\",\",\",\");\r\n          borderize_off(document.all(step_i));\r\n       }\r\n    }\r\n  }\r\n}\r\n</script>\r\n</head>\r\n\r\n<body class=\"bodycolor\" topmargin=\"1\" leftmargin=\"0\" onload=\"begin_set()\">\r\n\r\n\r\n<table border=\"1\" cellspacing=\"0\" width=\"100%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#000000\" bordercolordark=\"#FFFFFF\">\r\n<tr class=\"TableHeader\">\r\n  <td colspan=\"2\" align=\"center\"><b>sadfdsfdsf</b></td>\r\n</tr>\r\n\r\n<tr class=\"TableContent\">\r\n  <td onclick=\"javascript:add_all('1');\" style=\"cursor:hand\" align=\"center\">全部添加</td>\r\n</tr>\r\n<tr class=\"TableContent\">\r\n  <td onclick=\"javascript:del_all('1');\" style=\"cursor:hand\" align=\"center\">全部删除</td>\r\n</tr>\r\n\r\n<tr class=\"TableControl\">\r\n  <td class=\"menulines\" id=\"admin\" name=\"系统管理员\" flag=\"1\" align=\"center\" onclick=\"javascript:click_user('admin')\" style=\"cursor:hand\">\r\n  系统管理员  </td>\r\n</tr>\r\n\r\n\r\n<tr class=\"TableControl\">\r\n  <td class=\"menulines\" id=\"ada\" name=\"dfdf\" flag=\"1\" align=\"center\" onclick=\"javascript:click_user('ada')\" style=\"cursor:hand\">\r\n  dfdf  </td>\r\n</tr>\r\n\r\n\r\n</table>\r\n</body>\r\n</html>\r\n";
?>
