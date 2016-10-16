<?php
/**
 * @class	owncloudmember Admin Controller Module
 * @date	2016/10/13
 * @author	Micalgenus(micalgenus@gmail.com)
 * @package /modules/owncloudmember
 * @version 1.0
 * @brief	ownCloud 회원 동기화 관리자 controller
 */
class owncloudmemberAdminController extends owncloudmember
{
	/**
	 * @brief	Initilization
	 */
	function init() {
	}

	function procOwncloudmemberAdminSiteChange() {
		// Init module value
		$oModuleModel = &getModel('module');
		$oModuleController = &getController('module');

		// Get enable/disable
		$able_module = Context::get('able_module');
		$able_admin = Context::get('able_admin');

		// Get site information
		$site_protocol = Context::get('site_protocol');
		$site_url = Context::get('site_url');
		$admin_id = Context::get('admin_id');
		$admin_pw = convert_uuencode(Context::get('admin_pw'));

        // Get quota size
		$quota_size = Context::get('quota_size');
        $quota_option = Context::get('quota_option');

		// Get module config
		$config = $oModuleModel->getModuleConfig('owncloudmember');
		
		// Set module config
		$config->able_module = $able_module;
		$config->able_admin = $able_admin;
		$config->site_protocol = $site_protocol;
		$config->site_url = $site_url;
		$config->admin_id = $admin_id;
		$config->admin_pw = $admin_pw ?: $config->admin_pw;
		$config->quota_size = $quota_size;
        $config->quota_option = $quota_option;
		
		// Update module config
		$oModuleController->insertModuleConfig('owncloudmember', $config);

		if ($config->able_module == 'Y') {
			// add trigger
			$oModuleController->insertTrigger('member.doLogin', 'owncloudmember', 'controller', 'triggerBeforeLogin', 'before');

			// all user sync
			//$oOwncloudememberAdminModel = &getAdminModel('owncloudmember');
			//$oOwncloudememberAdminModel->allUserSync();
		} else if ($config->able_module != 'Y') {
			if($oModuleModel->getTrigger('member.doLogin', 'owncloudmember', 'controller', 'triggerBeforeLogin', 'before')) {
				$oModuleController->deleteTrigger('member.doLogin', 'owncloudmember', 'controller', 'triggerBeforeLogin', 'before');
			}
        }

		// redirect url
		$returnUrl = getNotEncodedUrl('', 'module', 'admin', 'act', 'dispOwncloudmemberAdminIndex');
		$this->setRedirectUrl($returnUrl);
	}
}
