var TANGER_OCX_bDocOpen = false;
var TANGER_OCX_strOp;
var TANGER_OCX_filename;
var TANGER_OCX_attachName;
var TANGER_OCX_attachURL; //for use with OpenFromURL
var TANGER_OCX_actionURL; //For auto generate form fiields
var TANGER_OCX_OBJ; //The Control
var TANGER_OCX_user; //��¼�û�

//����ΪV1.7��������ʾ��

//�ӱ�������ͼƬ���ĵ�ָ��λ��
function AddPictureFromLocal()
{
	if(TANGER_OCX_bDocOpen)
	{
    TANGER_OCX_OBJ.AddPicFromLocal(
	"", //·��
	true,//�Ƿ���ʾѡ���ļ�
	true,//�Ƿ񸡶�ͼƬ
	100,//����Ǹ���ͼƬ���������ߵ�Left ��λ��
	100); //����Ǹ���ͼƬ������ڵ�ǰ����Top
	};
}

//��URL����ͼƬ���ĵ�ָ��λ��
function AddPictureFromURL(URL)
{
	if(TANGER_OCX_bDocOpen)
	{
    TANGER_OCX_OBJ.AddPicFromURL(
	URL,//URL ע�⣻URL���뷵��Word֧�ֵ�ͼƬ���͡�
	true,//�Ƿ񸡶�ͼƬ
	150,//����Ǹ���ͼƬ���������ߵ�Left ��λ��
	150);//����Ǹ���ͼƬ������ڵ�ǰ����Top
	};
}

//�ӱ�������ӡ���ĵ�ָ��λ��
function AddSignFromLocal(key)
{
   if(TANGER_OCX_bDocOpen)
   {
      TANGER_OCX_OBJ.AddSignFromLocal(
	TANGER_OCX_user,//��ǰ��½�û�
	"",//ȱʡ�ļ�
	true,//��ʾѡ��
	0,//left
	0,//top
	key)
   }
}

//��URL����ӡ���ĵ�ָ��λ��
function AddSignFromURL(URL,key)
{
   if(TANGER_OCX_bDocOpen)
   {
      TANGER_OCX_OBJ.AddSignFromURL(
	TANGER_OCX_user,//��ǰ��½�û�
	URL,//URL
	50,//left
	50, //top
	key)
   }
}

//��ʼ��дǩ��
function DoHandSign(key)
{
   if(TANGER_OCX_bDocOpen)
   {
	TANGER_OCX_OBJ.DoHandSign(
	TANGER_OCX_user,//��ǰ��½�û� ����
	0,//����0��ʵ�� 0��4 //��ѡ����
	0x000000ff, //��ɫ 0x00RRGGBB//��ѡ����
	2,//�ʿ�//��ѡ����
	100,//left//��ѡ����
	50, //top//��ѡ����
	null,
	key);
   }
}

//��ʼȫ����дǩ��
function DoHandSign2(key)
{
   if(TANGER_OCX_bDocOpen)
   {
	TANGER_OCX_OBJ.DoHandSign2(
	TANGER_OCX_user,//��ǰ��½�û� ����
	key, //SignKey
	0,//left//��ѡ����
	0,//top
	0,//relative=0����ʾ������Ļλ����ע
	100 //����100%����ʾԭ��С
        );
   }
}

//��ʼ�ֹ���ͼ���������ֹ���ʾ
function DoHandDraw()
{
	if(TANGER_OCX_bDocOpen)
	{
	TANGER_OCX_OBJ.DoHandDraw(
	0,//����0��ʵ�� 0��4 //��ѡ����
	0x00ff0000,//��ɫ 0x00RRGGBB//��ѡ����
	3,//�ʿ�//��ѡ����
	200,//left//��ѡ����
	50//top//��ѡ����
	);
	}
}

//��ʼȫ���ֹ���ͼ���������ֹ���ʾ
function DoHandDraw2()
{
	if(TANGER_OCX_bDocOpen)
	{
	TANGER_OCX_OBJ.DoHandDraw2();
	}
}

//���ǩ�����
function DoCheckSign(key)
{
	if(TANGER_OCX_bDocOpen)
	{
	   var ret = TANGER_OCX_OBJ.DoCheckSign(false,key);

	   //��ѡ���� IsSilent ȱʡΪFAlSE����ʾ������֤�Ի���,����ֻ�Ƿ�����֤���������ֵ
	   //alert(ret);
	}
}
//����Ϊ��ǰ�汾�ĺ�����ʵ�ú���
//�˺�����������һ���Զ�����ļ�ͷ��
function TANGER_OCX_AddDocHeader( strHeader )
{
	if(!TANGER_OCX_bDocOpen)
		return;
	var i,cNum = 15;
	var lineStr = "";
	try
	{
		for(i=0;i<cNum;i++) lineStr += "_";  //�����»���
		with(TANGER_OCX_OBJ.ActiveDocument.Application)
		{
			Selection.HomeKey(6,0); // go home
			Selection.TypeText(strHeader);
			Selection.TypeParagraph(); 	//����
			Selection.TypeText(lineStr);  //�����»���
			// Selection.InsertSymbol(95,"",true); //�����»���
			Selection.TypeText("��");
			Selection.TypeText(lineStr);  //�����»���
			Selection.TypeParagraph();
			//Selection.MoveUp(5, 2, 1); //�������У��Ұ�סShift�����൱��ѡ������
			Selection.HomeKey(6,1);  //ѡ���ļ�ͷ�������ı�
			Selection.ParagraphFormat.Alignment = 1; //���ж���
			with(Selection.Font)
			{
				NameFarEast = "����";
				Name = "����";
				Size = 22;
				Bold = true;
				Italic = false;
				Underline = 0;
				UnderlineColor = 0;
				StrikeThrough = false;
				DoubleStrikeThrough = false;
				Outline = false;
				Emboss = false;
				Shadow = false;
				Hidden = false;
				SmallCaps = false;
				AllCaps = false;
				Color = 255;
				Engrave = false;
				Superscript = false;
				Subscript = false;
				Spacing = 0;
				Scaling = 100;
				Position = 0;
				Kerning = 0;
				Animation = 0;
				DisableCharacterSpaceGrid = false;
				EmphasisMark = 0;
			}
			Selection.MoveDown(5, 3, 0); //����3��
		}
	}
	catch(err){
		//alert("����" + err.number + ":" + err.description);
	}
	finally{
	}
}

//���ԭ�ȵı���������OnSubmit�¼��������ĵ�ʱ���Ȼ����ԭ�ȵ��¼���
function TANGER_OCX_doFormOnSubmit()
{
	var form = document.forms[0];
  	if (form.onsubmit)
	{
    	var retVal = form.onsubmit();
     	if (typeof retVal == "boolean" && retVal == false)
       	return false;
	}
	return true;
}

/*�˺����ڽϵͰ汾��IE������У���������
//Javascript��escape������
function TANGER_OCX_encodeObjValue(value)
{
	var t;
	t = value.replace(/%/g,"%25");
	return(t.replace(/&/g,"%26"));
}
*/
//�˺������������Զ����������ݴ�����Ϊ
//�ؼ���SaveToURL��������Ҫ�Ĳ���������
//һ��paraObj����paraObj.FFN����������
//���һ��<input type=file name=XXX>��name
//paraObj.PARA�����˱������������ݣ����磺
//f1=v1&f2=v2&f3=v3.����,v1.v2.v3�Ǿ���
//Javascript��escape������������ݡ����IE
//�İ汾�ϵͣ�����ʹ������ע�͵���TANGER_OCX_encodeObjValue
//�������������escape������
function TANGER_OCX_genDominoPara(paraObj)
{
	var fmElements = document.forms[0].elements;
	var i,j,elObj,optionItem;
	for (i=0;i< fmElements.length;i++ )
	{
		elObj = fmElements[i];
		switch(elObj.type)
		{
			case "file":
				paraObj.FFN = elObj.name;
				break;
			case "reset":
				break;
			case "radio":
			case "checkbox":
				if (elObj.checked)
				{
					paraObj.PARA += ( elObj.name+"="+escape(elObj.value)+"&");
				}
				break;
			case "select-multiple":
				for(j=0;j<elObj.options.length;j++)
				{
					optionItem = elObj.options[j];
					if (optionItem.selected)
					{
						paraObj.PARA += ( elObj.name+"="+escape(optionItem.value)+"&");
					}
				}
				break;
			default: // text,Areatext,selecte-one,password,submit,etc.
				if(elObj.name)
				{
					paraObj.PARA += ( elObj.name+"="+escape(elObj.value)+"&");
				}
				break;
		}
	}
}
function TANGER_OCX_OpenDoc(opcode,fileName,strState)
{
    TANGER_OCX_State = strState;
    TANGER_OCX_actionURL = document.forms[0].action;
    TANGER_OCX_OBJ = document.all.item("TANGER_OCX");
    //alert("readdoc.jsp?docid=" + docid);
    if(opcode == 1)//edit
    {
        try
        {
            TANGER_OCX_OBJ.OpenFromURL(fileName,false,"Word.Document");	
           // TANGER_OCX_OBJ.OpenFromURL(fileName);
        }
        catch (e)
        {};
    }
    else
    {
        TANGER_OCX_OBJ.CreateNew("Word.Document");
    }
    //TANGER_OCX_OBJ.focus();
    if (strState == "view")
        TANGER_OCX_SetReadOnly(true);
}


function TANGER_OCX_DoPaiBan(URL)
{
	try{
		//TANGER_OCX_OBJ.SetReadOnly(false);
		//ѡ�����ǰ�ĵ�����������
		var curSel = TANGER_OCX_OBJ.ActiveDocument.Application.Selection;
		//TANGER_OCX_SetMarkModify(false);
		// del previous template
		var BookMarkName = "hongtou";

		if(TANGER_OCX_OBJ.ActiveDocument.BookMarks.Exists(BookMarkName))
		{
			TANGER_OCX_OBJ.ActiveDocument.BookMarks(BookMarkName).Range.Select();
			curSel.Delete();
			curSel.Delete(1,1);
		}
		curSel.HomeKey(6); //go to document home 
		//curSel.WholeStory();
		//curSel.Cut();

		//����ģ��
		TANGER_OCX_OBJ.AddTemplateFromURL(URL);		
		
	}
	catch(err)
	{		
		//alert("����" + err.number + ":" + err.description);
	}
	
	finally
	{
		TANGER_OCX_SetMarkModify(true);
		TANGER_OCX_ShowRevisions(true);
	}
	

}


//�����ĵ�Ϊֻ��
function TANGER_OCX_SetReadOnly(boolvalue)
{
	var appName,i;
	try
	{
		if (boolvalue) TANGER_OCX_OBJ.IsShowToolMenu = false;
		
	  if(!TANGER_OCX_bDocOpen)
		   return;
		   
		with(TANGER_OCX_OBJ.ActiveDocument)
		{
			appName = new String(Application.Name);
			if( (appName.toUpperCase()).indexOf("WORD") > -1 ) //Word
			{
				if (ProtectionType != -1 &&  !boolvalue)
				{
					Unprotect();
				}
				if (ProtectionType == -1 &&  boolvalue)
				{
					Protect(2,true,"");
				}
			}
			else if ( (appName.toUpperCase()).indexOf("EXCEL") > -1 ) //EXCEL
			{
				for(i=1;i<=Application.Sheets.Count;i++)
				{
					if(boolvalue)
					{
						Application.Sheets(i).Protect("",true,true,true);
					}
					else
					{
						Application.Sheets(i).Unprotect("");
					}
				}
				if(boolvalue)
				{
					Application.ActiveWorkbook.Protect("",true);
				}
				else
				{
					Application.ActiveWorkbook.Unprotect("");
				}
			}
			else
			{
			}
		}
	}
	catch(err){
		//alert("����" + err.number + ":" + err.description);
	}
	finally{
	}
}

//�������ֹ�û��ӿؼ���������
function TANGER_OCX_SetNoCopy(boolvalue)
{
	TANGER_OCX_OBJ.IsNoCopy = boolvalue;
}

//�������ֹ�ļ���>�½��˵�
function TANGER_OCX_EnableFileNewMenu(boolvalue)
{
	TANGER_OCX_OBJ.EnableFileCommand(0) = boolvalue;
}
//�������ֹ�ļ���>�򿪲˵�
function TANGER_OCX_EnableFileOpenMenu(boolvalue)
{
	TANGER_OCX_OBJ.EnableFileCommand(1) = boolvalue;
}
//�������ֹ�ļ���>����˵�
function TANGER_OCX_EnableFileSaveMenu(boolvalue)
{
	TANGER_OCX_OBJ.EnableFileCommand(3) = boolvalue;
}
//�������ֹ�ļ���>����Ϊ�˵�
function TANGER_OCX_EnableFileSaveAsMenu(boolvalue)
{
	TANGER_OCX_OBJ.EnableFileCommand(4) = boolvalue;
}
//�������ֹ�ļ���>��ӡ�˵�
function TANGER_OCX_EnableFilePrintMenu(boolvalue)
{
	TANGER_OCX_OBJ.EnableFileCommand(5) = boolvalue;
}
//�������ֹ�ļ���>��ӡԤ���˵�
function TANGER_OCX_EnableFilePrintPreviewMenu(boolvalue)
{
	TANGER_OCX_OBJ.EnableFileCommand(6) = boolvalue;
}

//�������ֹ��ʾ�޶��������͹��߲˵��������޶���
function TANGER_OCX_EnableReviewBar(boolvalue)
{
	
	if(!TANGER_OCX_bDocOpen)
		return;
	TANGER_OCX_OBJ.ActiveDocument.CommandBars("Reviewing").Enabled = boolvalue;
	TANGER_OCX_OBJ.ActiveDocument.CommandBars("Track Changes").Enabled = boolvalue;
	TANGER_OCX_OBJ.IsShowToolMenu = boolvalue;	//�رջ�򿪹��߲˵�
}

//�򿪻��߹ر��޶�ģʽ
function TANGER_OCX_SetReviewMode(boolvalue)
{
	if(!TANGER_OCX_bDocOpen)
		return;
	TANGER_OCX_OBJ.ActiveDocument.TrackRevisions = boolvalue;
}

//������˳��ۼ�����״̬�������������������
function TANGER_OCX_SetMarkModify(boolvalue)
{
	TANGER_OCX_SetReviewMode(boolvalue);
	//TANGER_OCX_EnableReviewBar(!boolvalue);
}

//��ʾ/����ʾ�޶�����
function TANGER_OCX_ShowRevisions(boolvalue)
{
	if(!TANGER_OCX_bDocOpen)
		return;
	TANGER_OCX_OBJ.ActiveDocument.ShowRevisions = boolvalue;
}

//��ӡ/����ӡ�޶�����
function TANGER_OCX_PrintRevisions(boolvalue)
{
	if(!TANGER_OCX_bDocOpen)
		return;
	TANGER_OCX_OBJ.ActiveDocument.PrintRevisions = boolvalue;
}

//�����û���
function TANGER_OCX_SetDocUser(cuser)
{
	if(!TANGER_OCX_bDocOpen)
		return;
	with(TANGER_OCX_OBJ.ActiveDocument.Application)
	{
		UserName = cuser;
	}
}

//����ҳ�沼��
function TANGER_OCX_ChgLayout()
{
 	try
	{
		TANGER_OCX_OBJ.showdialog(5); //����ҳ�沼��
	}
	catch(err){
		alert("����" + err.number + ":" + err.description);
	}
	finally{
	}
}

//��ӡ�ĵ�
function TANGER_OCX_PrintDoc()
{
	try
	{
		TANGER_OCX_OBJ.printout(true);
	}
	catch(err){
		alert("����" + err.number + ":" + err.description);
	}
	finally{
	}
}
//�˺�������ҳװ��ʱ�����á�������ȡ�ؼ����󲢱��浽TANGER_OCX_OBJ
//ͬʱ���������ó�ʼ�Ĳ˵�״�����򿪳�ʼ�ĵ��ȵȡ�
function TANGER_OCX_SetInfo()
{
	var info;
	TANGER_OCX_OBJ = document.all.item("TANGER_OCX");
	TANGER_OCX_EnableFileNewMenu(false);
	TANGER_OCX_EnableFileOpenMenu(false);
	TANGER_OCX_EnableFileSaveMenu(false);
	TANGER_OCX_EnableFileSaveAsMenu(false);
	try
	{
		TANGER_OCX_actionURL = document.forms[0].action;
		TANGER_OCX_strOp = document.all.item("TANGER_OCX_op").innerHTML;
		TANGER_OCX_filename = document.all.item("TANGER_OCX_filename").innerHTML;
		TANGER_OCX_attachName = document.all.item("TANGER_OCX_attachName").innerHTML;
		TANGER_OCX_attachURL = document.all.item("TANGER_OCX_attachURL").innerHTML;
		TANGER_OCX_user = document.all.item("TANGER_OCX_user").innerHTML;

		re=/&amp;/g;
    TANGER_OCX_attachURL=TANGER_OCX_attachURL.replace(re,"&");

		if (TANGER_OCX_OBJ.IsHiddenOpenURL)
		{
			TANGER_OCX_attachURL = TANGER_OCX_HiddenURL(TANGER_OCX_attachURL);
		}
		//alert(TANGER_OCX_attachURL);

		switch(TANGER_OCX_strOp)
		{
			case "1":
				info = "��Word�ĵ�";
				TANGER_OCX_OBJ.CreateNew("Word.Document");
				break;
			case "2":
				info = "��Excel������";
				TANGER_OCX_OBJ.CreateNew("Excel.Sheet");
				break;
			case "3":
				info = "��PowserPoint�õ�Ƭ";
				TANGER_OCX_OBJ.CreateNew("PowerPoint.Show");
				break;
			case "4":
				info = "�༭�ĵ�";
				if(TANGER_OCX_attachURL)
				{
					TANGER_OCX_OBJ.BeginOpenFromURL(TANGER_OCX_attachURL,true,false);
				}
				else
				{
					TANGER_OCX_OBJ.CreateNew("Word.Document");
				}
				break;
			case "5":
				info = "�Ķ��ĵ�";
				if(TANGER_OCX_attachURL)
				{
					//TANGER_OCX_OBJ.BeginOpenFromURL("readdoc.php?filename=" + fileName);
					TANGER_OCX_OBJ.BeginOpenFromURL(TANGER_OCX_attachURL,true,true);
				}
				break;
			default:
				info = "δ֪����";
		}
		//document.all.item("TANGER_OCX_info").innerHTML = info + "&nbsp;&nbsp;" + TANGER_OCX_filename;
		
	}
	catch(err){
		//alert("����" + err.number + ":" + err.description);
		msg='����ʹ��΢��Office�������ĵ���\n\n�Ƿ���ʹ�ý�ɽWPS���ִ����������ĵ���';
    if(window.confirm(msg))
    {
		   if(TANGER_OCX_strOp==4)
		     TANGER_OCX_OBJ.BeginOpenFromURL(TANGER_OCX_attachURL,true,false,"WPS.Document");
		   else
		   	 TANGER_OCX_OBJ.BeginOpenFromURL(TANGER_OCX_attachURL,true,true,"WPS.Document");
		}
	}
	finally{
	}
}
//�˺������ĵ��ر�ʱ�����á�
function TANGER_OCX_OnDocumentClosed()
{
	TANGER_OCX_bDocOpen = false;
}
//�˺����������浱ǰ�ĵ�����Ҫʹ���˿ؼ���SaveToURL������
//�йش˺�������ϸ�÷�������ı���ֲᡣ


function TANGER_OCX_SaveDoc(op_flag)
{
	var retStr=new String;
	var newwin,newdoc;
	var paraObj = new Object();
	paraObj.PARA="";
	paraObj.FFN ="";
	try
	{
	 	if(!TANGER_OCX_doFormOnSubmit())return;
		TANGER_OCX_genDominoPara(paraObj);
		//alert(paraObj.PARA+"\n"+paraObj.FFN);
		if(!paraObj.FFN)
		{
			alert("�������󣺿ؼ��ĵڶ�������û��ָ����");
			return;
		}
		if(!TANGER_OCX_bDocOpen)
		{
			alert("û�д򿪵��ĵ���");
			return;
		}
		switch(TANGER_OCX_strOp)
		{
			case "1":
			case "2":
			case "3":
			case "4":
				lock_ref();
				//alert(paraObj.FFN);
				retStr = TANGER_OCX_OBJ.SaveToURL(TANGER_OCX_actionURL,paraObj.FFN,"",TANGER_OCX_filename,0);
				//newwin = window.open("OFFICE_SAVE","_blank","left=200,top=200,width=400,height=200,status=0,toolbar=0,menubar=0,location=0,scrollbars=0,resizable=0",false);
				//newdoc = newwin.document;
				//newdoc.open();
				//newdoc.close();
				window.alert(retStr);
				if(op_flag==1)
				{
			           TANGER_OCX_bDocOpen = false;
			           window.close();
			  }
				break;
			case "5":
				alert("�ĵ������Ķ�״̬�������ܱ��浽��������");
			default:
				break;
		}
	}
	catch(err){
		alert("���ܱ��浽URL��" + err.number + ":" + err.description);
	}
	finally{
	}
}

//�˺������ĵ���ʱ�����á�
function TANGER_OCX_OnDocumentOpened(str, obj)
{
	var s, s2;
	try
	{
		TANGER_OCX_bDocOpen = true;
		if( 0==str.length)
		{
			str = TANGER_OCX_filename;
		}
		//TANGER_OCX_OBJ.Caption = TANGER_OCX_filename + " - �����ĵ��༭��";
		TANGER_OCX_OBJ.Caption = TANGER_OCX_filename;
		if(TANGER_OCX_filename.indexOf(".ppt")<0 && TANGER_OCX_filename.indexOf(".PPT")<0 )
		   TANGER_OCX_SetDocUser(TANGER_OCX_user);
		s = "δ֪Ӧ�ó���";
		if(obj)
		{
			switch(TANGER_OCX_strOp)
			{
				case "1":
				case "2":
				case "3":
				case "4":
					TANGER_OCX_SetReadOnly(false);
					break;
				case "5":
					TANGER_OCX_SetReadOnly(true);
					break;
				default:
					break;
			}
			s = obj.Application.Name;
		}
		//document.all.item("TANGER_OCX_mes").innerHTML =  str + "&nbsp;&nbsp;Ӧ�ó���: " + s;
	}
	catch(err){
		window.status = "OnDocumentOpened�¼���Script��������" + err.number + ":" + err.description;
	}
	finally{
	}
}

function TANGER_OCX_OnSignSelect(issign,signinfo)
{
   if(!issign)
      return;
	
   if(signinfo.indexOf("�û�:"+TANGER_OCX_user) == -1)
   {
   	  TANGER_OCX_SetReadOnly(true);
      TANGER_OCX_SetReadOnly(false);
   }
}