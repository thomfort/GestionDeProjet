<?php 
/**
 * Creates a masked input box
 * @author RosSoft
 * @license MIT
 * @version 0.1
 */
class MaskeditHelper extends Helper
{
    var $helpers=array(‘Html’,'Javascript’,'Head’);
   
    /*
     * Creates a masked input box
     * @param string $fieldname input box fieldname (model/field)
     * @param string $mask The input Mask
     * @param array $htmlAttributes Extra html attributes for input tag
     * @return string
     */
    function maskedit($fieldname,$mask,$htmlAttributes)
    {
        $this->Head->register_js(‘maskedit/maskedit’);
       
        if (!isset($htmlAttributes['id']))
        {
            $htmlAttributes['id']=”maskedit_” . str_replace(‘/’,'_’,$fieldname);
        }
        $htmlAttributes['onclick']=”javascript:RS_InputMask_OnClick(event, this);”;
        $htmlAttributes['onkeypress']=”javascript:RS_InputMask_KeyPress(event, this);”;
        $htmlAttributes['onkeydown']=”javascript:RS_InputMask_KeyDown(event, this);”;
        $htmlAttributes['oninput']=”javascript:RS_InputMask_OnInput(event, this);”;
        $htmlAttributes['onfocus']=”javascript:RS_InputMask_GotFocus(this);”;
        $htmlAttributes['onpaste']=”javascript:RS_InputMask_OnPaste(this);”;       
               
        ob_start();                       
        echo $this->Html->input($fieldname, $htmlAttributes,true);
        echo $this->Javascript->codeBlock(“maskeditInit(‘{$htmlAttributes['id']}’,'$mask’)”);
        return ob_get_clean();
    }
}
/**
 * Mask examples:
 * nnnn-nn-nn   Date
 * aaaa-nn      4 letters, then two numbers
 * xxxx-nn      4 alphanumeric, then two numbers
 * Other simbols than n and a are literals (maybe exists more special chars)
 */
?>