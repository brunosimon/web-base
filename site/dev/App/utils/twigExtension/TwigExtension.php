<?php
 
use \Twig_Filter_Function;
use \Twig_Filter_Method;
 
class TwigExtension extends \Twig_Extension
{
 
    /**
     * Return the functions registered as twig extensions
     * 
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'file_exists' => new \Twig_Function_Function('file_exists'),
            'getUrl' => new \Twig_Function_Function(function(){
                return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            })
        );
    }
 
    public function getName()
    {
        return 'twig_extension';
    }
}
 
?>