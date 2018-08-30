<?php
class fckeditor
{
    var $InstanceName;
    var $BasePath;
    var $Width;
    var $Height;
    var $ToolbarSet;
    var $Value;
    var $Config;

    function __construct( $instanceName )
    {
        $this->InstanceName = $instanceName;
        $this->BasePath = "/fckeditor/";
        $this->Width = "100%";
        $this->Height = "96%";
        $this->ToolbarSet = "Default";
        $this->Value = "";
        $this->Config = array( );
    }

    function fckeditor( $instanceName )
    {
        $this->__construct( $instanceName );
    }

    function create( )
    {
        echo $this->CreateHtml( );
    }

    function createhtml( )
    {
        $HtmlValue = htmlspecialchars( $this->Value );
        $Html = "<div>";
        if ( $this->IsCompatible( ) )
        {
            if ( isset( $_GET['fcksource'] ) && $_GET['fcksource'] == "true" )
            {
                $File = "fckeditor.original.html";
            }
            else
            {
                $File = "fckeditor.html";
            }
            $Link = $this->BasePath."editor/$File?InstanceName=$this->InstanceName";
            if ( $this->ToolbarSet != "" )
            {
                $Link .= "&amp;Toolbar=".$this->ToolbarSet;
            }
            $Html .= "<input type=\"hidden\" id=\"".$this->InstanceName."\" name=\"$this->InstanceName\" value=\"$HtmlValue\" style=\"display:none\" />";
            $Html .= "<input type=\"hidden\" id=\"".$this->InstanceName."___Config\" value=\"".$this->GetConfigFieldString( )."\" style=\"display:none\" />";
            $Html .= "<iframe id=\"".$this->InstanceName."___Frame\" src=\"$Link\" width=\"$this->Width\" height=\"$this->Height\" frameborder=\"0\" scrolling=\"no\"></iframe>";
        }
        if ( strpos( $this->Width, "%" ) === false )
        {
            $WidthCSS = $this->Width."px";
        }
        else
        {
            $WidthCSS = $this->Width;
        }
        if ( strpos( $this->Height, "%" ) === false )
        {
            $HeightCSS = $this->Height."px";
        }
        else
        {
            $HeightCSS = $this->Height;
        }
        $Html .= "<textarea name=\"".$this->InstanceName."\" rows=\"4\" cols=\"40\" style=\"width: $WidthCSS; height: $HeightCSS\">$HtmlValue</textarea>";
        $Html .= "</div>";
        return $Html;
    }

    function iscompatible( )
    {
        global $HTTP_USER_AGENT;
        if ( isset( $HTTP_USER_AGENT ) )
        {
            $sAgent = $HTTP_USER_AGENT;
        }
        else
        {
            $sAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if ( strpos( $sAgent, "MSIE" ) !== false )
        {
            if ( strpos( $sAgent, "mac" ) === false )
            {
                if ( strpos( $sAgent, "Opera" ) === false )
                {
                    $iVersion = ( double )substr( $sAgent, strpos( $sAgent, "MSIE" ) + 5, 3 );
                    return 5.5 <= $iVersion;
                }
            }
        }
        if ( strpos( $sAgent, "Gecko/" ) !== false )
        {
            $iVersion = ( integer )substr( $sAgent, strpos( $sAgent, "Gecko/" ) + 6, 8 );
            return 20030210 <= $iVersion;
        }
        else
        {
            return false;
        }
    }

    function getconfigfieldstring( )
    {
        $sParams = "";
        $bFirst = true;
        foreach ( $this->Config as $sKey=>$sValue )
        {
            if ( $bFirst == false )
            {
                $sParams .= "&amp;";
            }
            else
            {
                $bFirst = false;
            }
            if ( $sValue === true )
            {
                $sParams .= $this->EncodeConfig( $sKey )."=true";
                continue;
            }
            else if ( $sValue === false )
            {
                $sParams .= $this->EncodeConfig( $sKey )."=false";
                continue;
            }
            else
            {
                $sParams .= $this->EncodeConfig( $sKey )."=".$this->EncodeConfig( $sValue );
            }
        }
        return $sParams;
    }

    function encodeconfig( $valueToEncode )
    {
        $chars = array( "&"=>"%26", "="=>"%3D", "\""=>"%22" );
        return strtr( $valueToEncode, $chars );
    }
}

?>
