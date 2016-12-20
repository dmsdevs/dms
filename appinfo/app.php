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

use OCP\AppFramework\App;

$app = new App('deadmanswitch');
$container = $app->getContainer();

$container->query('OCP\INavigationManager')->add(function () use ($container) {
	$urlGenerator = $container->query('OCP\IURLGenerator');
	$l10n = $container->query('OCP\IL10N');
	return [
		// the string under which your app will be referenced in Nextcloud
		'id' => 'deadmanswitch',

		// sorting weight for the navigation. The higher the number, the higher
		// will it be listed in the navigation
		'order' => 10,

		// the route that will be shown on startup
		'href' => $urlGenerator->linkToRoute('deadmanswitch.page.index'),

		// the icon that will be shown in the navigation
		// this file needs to exist in img/
		'icon' => $urlGenerator->imagePath('deadmanswitch', 'app.svg'),

		// the title of your application. This will be used in the
		// navigation or on the settings page of your app
		'name' => $l10n->t('Dead Man Switch'),
	];
});

// \OCP\Backgroundjob::addRegularTask('\OCA\DeadManSwitch\Cron\Task', 'run');
// \OCP\Util::connectHook('OC_User', 'post_login', 'OCA\DeadManSwitch\Hooks\Reset', 'run');
