
<script language="javascript">

function getSrh()
{
    var search="";
    search = location.search.substring(0,location.search.indexOf('&Order_by'));
    if(search.length==0)
    {
        search = location.search;
        if(search.length==0)
            search = "?";
        else
            search = search+"&";
    }else
        search = search + "&";
    return search;
}   
</script>
<?php
$trclass = isset($trclass) ? $trclass : "small";
$Order_by = isset($Order_by)?$Order_by:"";
$PagerTbAlign = isset($PagerTbAlign) ? $PagerTbAlign :"center";
//if ( $offset < $pages )
{
    echo <<<EOT
<table width="60%"  border="0" align="$PagerTbAlign" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table cellspacing="0" cellpadding="0" width="100%" border="0"> 
                <tbody>
                    <tr class="$trclass">
                        <td>�ϼ�<font color="red"><b>
EOT;
    echo $pages;
    echo "</B></FONT>�� |��";
    if ( $pp1 != "off" )
    {
        echo "<a onclick=\"javascript:this.href=getSrh()+'Order_by=$Order_by&page=1';\" href='#'>";
    }
    echo "��ҳ</a> ";
    if ( $pp1 != "off" )
    {
        echo "<a onclick=\"javascript:this.href=getSrh()+'Order_by=".$Order_by."'+'&page=".$page1."';\" href='#'>";
    }
    echo "��һҳ</a> ";
    if ( $pp2 != "off" )
    {
        echo "<a onclick=\"javascript:this.href=getSrh()+'Order_by=".$Order_by."'+'&page=".$page2."';\" href='#'>";
    }
    echo "��һҳ</a> ";
    if ( $pp2 != "off" )
    {
        echo "<a onclick=\"javascript:this.href=getSrh()+'Order_by=".$Order_by."'+'&page=".$totalpage."';\" href='#'>";
    }
    echo <<<EOT
    βҳ</a> ҳ�Σ�<strong><font color="red">$page</font>/$totalpage</strong>ҳ <B>$offset</b>��/ҳ
    </td>
    <form name='jumpFrm' action="" method='get'>
    <td>
        <input type="hidden" name="page" />ת��:<select onchange="javascript:jumpFrm.page.value=jumpFrm.select.value;jumpFrm.action=getSrh()+'Order_by=$Order_by'+'&page=$page';jumpFrm.submit();" name="select">
EOT;
    $pp = 1;
    for ( ; $pp <= $totalpage; ++$pp )
    {
        echo "<option value=";
        echo $pp;
        if ( $pp == $page )
        {
            echo " selected";
        }
        echo " >��";
        echo $pp;
        echo "ҳ</option>\r\n";
    }
    echo <<<EOT
                    </select>
                    </td>
                </form>
                </tr>
            </tbody>
        </table>
    </td>
  </tr>
</table>
EOT;
}
?>