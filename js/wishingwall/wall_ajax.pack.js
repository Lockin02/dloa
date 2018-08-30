/*
 * wishingWall 0.0.3 - Javascript
 *
 * Copyright (c) 2008 DeltaCat (http://www.zu14.cn)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * $Date: 2008-12-6 14:22:17 -0400 (Sat, 6 Dec 2008) $
 * $Rev: 3 $
 * Requires jQuery 1.1.3+
 */
function doPost()
{var wishCharCount=$('#wish').val().length;if(wishCharCount==0||wishCharCount>50)
{window.alert('祝福内容必须介于1～50字之');$('#wish').focus();return;}
$.ajax({type:'POST',url:'postpad.php',data:$('form').serialize(),error:function(msg){alert(msg);}});}
$(document).ready(function(){$('#waiting').bind('ajaxSend',function(){$(this).show();});$('#success').bind('ajaxSend',function(){$(this).hide();});$('#waiting').bind('ajaxStop',function(){$(this).hide();});$('#success').bind('ajaxStop',function(){$(this).show();});});