<?php

namespace Project\Project\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;


class enterpriseGetContentViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     *
     * @return void
     */


    public function initializeArguments()
    {
        $this->registerArgument('enterpriseId', 'integer', 'enterprise id');
    }

    public function render()
    {
        $values = $this->arguments['values'];
        $keys = $this->arguments['keys'];
        if ($values === NULL) {
            $values = $this->renderChildren();
        }
        return array_combine($keys, $values);
    }
}
