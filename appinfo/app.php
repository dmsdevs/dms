<?php
/**
 * ownCloud - deadmanswitch
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author DMS Developers <dms-devs@web.de>
 * @copyright DMS Developers 2016
 */

namespace OCA\DeadManSwitch\AppInfo;


\OC::$server->getNavigationManager()->add(function () {
    $urlGenerator = \OC::$server->getURLGenerator();
    return [
	// the string under which your app will be referenced in owncloud
	'id' => 'deadmanswitch',

	// sorting weight for the navigation. The higher the number, the higher
	// will it be listed in the navigation
	'order' => 10,

	// the route that will be shown on startup
	'href' => \OCP\Util::linkToRoute('deadmanswitch.page.index'),

	// the icon that will be shown in the navigation
	// this file needs to exist in img/
	'icon' => \OCP\Util::imagePath('deadmanswitch', 'app.svg'),

	// the title of your application. This will be used in the
	// navigation or on the settings page of your app
	//'name' => \OC_L10N::get('deadmanswitch')->t('Dead Man Switch')
	'name' => \OC::$server->getL10N('deadmanswitch')->t('DMS'),
    ];
});


\OCP\Backgroundjob::addRegularTask('OCA\DeadManSwitch\Cron\Task', 'run');
\OCP\Util::connectHook('OC_User', 'post_login', 'OCA\DeadManSwitch\Hooks\Reset', 'run');
