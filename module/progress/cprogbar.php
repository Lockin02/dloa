<?php
class cprogbar
{
    var $Id;
    var $CurPos;
    var $CurPosInt;
    var $Max;
    var $Step;

    function cprogbar( $id, $pos = 0 )
    {
        $this->Id = $id;
        $this->CurPos = $pos;
        $this->CurPosInt = ( integer )$pos;
    }

    function show( $text = "", $width = "100%", $border = "#909090", $bgcolor = "BLUE" )
    {
        $pos = $this->CurPosInt;
        CTable( $width ? "width='".$width."'" : "", "class=progbar" );
        $tbl = new CTable;
        $tbl->tr( );
        $tbl->td( $pos ? $pos."%" : "&nbsp;", "align=center", "style='border-left:1px solid ".$border.";border-top:1px solid $border;border-bottom:1px solid $border'", "id='Prog_".$this->Id."_Txt'", "width=40" );
        if ( $text )
        {
            $pos = 100;
        }
        $tbl->td( "<div style='border:1px solid ".$border.";width:100%;'><span id='Prog_".$this->Id.( "' style='width:".$pos."%;background:$bgcolor'>" ).( $text."</span></div>" ) );
        return $tbl->html( );
    }

    function init( $max = 100, $frame = "" )
    {
        $this->Max = $max < 100 ? 100 : $max;
        $this->Step = $max ? 100 / $max : 100;
        do
        {
            if ( $frame )
                break;
            $f = "var docP=document;";
        } while( 0 );
        if ( $frame = "_parent" )
        {
            $f = "var docP=parent.document;";
        }
        else
        {
            $f = "var docP=parent.document.frames['".$frame."'].document;".( "if(!docP) alert('Frame \\'".$frame."\\' does not exists!');" );
        }
        echo str_pad( "", 4096 )."\n";
        flush( );
    }

    function step( )
    {
        $this->CurPos += $this->Step;
        if ( $this->CurPosInt < ( integer )$this->CurPos )
        {
            $this->CurPosInt = ( integer )$this->CurPos;
            if ( $this->Max < $this->CurPosInt )
            {
                $this->CurPos = $this->CurPosInt = $this->Max;
            }
            echo str_pad( "", 4096 )."\n";
            flush( );
        }
    }

    function full( )
    {
        $this->CurPos = $this->CurPosInt = 100;
        echo str_pad( "", 4096 )."\n";
        flush( );
    }

    function text( $text, $color = "", $bgcolor = "", $fontWeight = "normal" )
    {
        if ( $text )
        {
            echo str_pad( "", 4096 )."\n";
            flush( );
        }
    }

    function error( $text, $color = "#ffffff", $bgcolor = "#ff0000", $fontWeight = "bold" )
    {
        $this->text( $text, $color, $bgcolor, $fontWeight );
    }
}

include_once( "ctable.php" );
?>
