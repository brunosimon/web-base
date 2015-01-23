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
        );
    }
 
    public function getName()
    {
        return 'twig_extension';
    }
}
 
?>