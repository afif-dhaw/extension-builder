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
        $this->registerArgument('enterpriseId', 'integer', 'uid of the author', true);
    }

    public function render()
    {
        $enterpriseId = $this->arguments['enterpriseId'];
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $EnterpriseRepository = $objectManager->get('filter\\classes\\Domain\\Repository\\EnterpriseRepository');
        $enterprise = $EnterpriseRepository->findByUid($enterpriseId);
        $this->templateVariableContainer->add('enterpriseElement', $enterprise);
        return $this->renderChildren();
    }
}
