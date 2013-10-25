<?php

class SDIS62_View_Helper_Navigation_BootstrapBreadcrumbs extends Zend_View_Helper_Navigation_Breadcrumbs
{
    /**
     * Breadcrumbs separator string
     *
     * @var string
     */
    protected $_separator = '/';
    
    /**
     * Call helper
     *
     * @return SDIS62_View_Helper_Navigation_BootstrapBreadcrumbs
     */
    public function bootstrapBreadcrumbs()
    {
        return $this;
    }
    
    /**
     * Renders breadcrumbs based on bootstrap breadcrumbs
     *
     * @param  Zend_Navigation_Container $container  [optional] container to
     *                                               render. Default is to
     *                                               render the container
     *                                               registered in the helper.
     * @return string                                helper output
     */
    public function renderStraight(Zend_Navigation_Container $container = null)
    {
        if ($container === null)
        {
            $container = $this->getContainer();
        }

        // find deepest active
        if (!$active = $this->findActive($container))
        {
            return '';
        }

        $active = $active['page'];

        // put the deepest active page last in breadcrumbs
        if ($this->getLinkLast())
        {
            $html = '<li>' . $this->htmlify($active) . '</li>';
        }
        else
        {
            $html = $active->getLabel();
            
            if ($this->getUseTranslator() && $t = $this->getTranslator())
            {
                $html = $t->translate($html);
            }
            
            $html = '<li class="active">' . $this->view->escape($html) . '</li>';
        }

        // walk back to root
        while (($parent = $active->getParent()) != null)
        {
            if ($parent instanceof Zend_Navigation_Page)
            {
                // prepend crumb to html
                $html = '<li>' . $this->htmlify($parent) . ' <span class="divider">' . 
                    $this->getSeparator() . '</span></li>' . PHP_EOL . $html;
            }

            if ($parent === $container)
            {
                // at the root of the given container
                break;
            }

            $active = $parent;
        }

        return strlen($html) ? $this->getIndent() . '<ul class="breadcrumb">' . $html .  '</ul>' : '';
    }
}