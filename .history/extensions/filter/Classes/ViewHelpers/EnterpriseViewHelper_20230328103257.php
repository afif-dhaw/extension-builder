<?php

namespace Filter\Filter\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;


class EnterpriseViewHelper extends AbstractViewHelper
{


    public function initializeArguments()
    {
        $this->registerArgument('enterpriseId', 'integer', 'uid of the author', true);
    }

    public function render()
    {
        
        $enterpriseId = $this->arguments['enterpriseId'];
        if (empty($enterpriseId)) {
            return [];
        }

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $EnterpriseRepository = $objectManager->get('Filter\\Filter\\Domain\\Repository\\EnterpriseRepository');
        $enterprise = $EnterpriseRepository->findByUid($enterpriseId);
        return $enterprise ?  $enterprise : [];
    }
}
