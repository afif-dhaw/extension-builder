<?php

namespace Filter\Filter\Hook;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

class EnterpriseHook
{
	public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldValues, $dataHandler)
	{
		if (($status == 'new') && ($table == 'tx_filter_domain_model_enterprise')) {
			if (GeneralUtility::isFirstPartOfStr($id, 'NEW')) {
				$id = $dataHandler->substNEWwithIDs[$id];
			}
			$data['pages']['NEWbe68s587'] = array(
				'pid' => '7',
				'hidden' => 0,
				'title' => 'Enterprise Of ' . $fieldValues['name'],
				'backend_layout' => 'flux__grid',
				'tx_fed_page_controller_action' => 'SOFTTODO.Formation->verbraucher'
			);
			$data['tt_content']['NEWbe68s587'] = array(
				'pid' => 'NEWbe68s587',
				'CType' => 'formation_enterprise',
				'colPos' => 0,
				'pi_flexform' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
				<T3FlexForms>
					<data>
						<sheet index="options">
							<language index="lDEF">
								<field index="enterpriseId">
									<value index="vDEF">' . $id . '</value>
								</field>
								<field index="enterprise">
									<value index="vDEF">' . $fieldValues['name'] . '</value>
								</field>
								<field index="E-Mail">
									<value index="vDEF">' . $fieldValues['email'] . '</value>
								</field>
							</language>
						</sheet>
					</data>
				</T3FlexForms>
							'
			);
			$dataHandler->start($data, []);
			$dataHandler->process_datamap();
			\TYPO3\CMS\Backend\Utility\BackendUtility::setUpdateSignal('updatePageTree');
		}
	}
}
