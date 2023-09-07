<?php

$updaterBase = 'https://projects.bluewindlab.net/wpplugin/zipped/plugins/';
$pluginRemoteUpdater = $updaterBase . 'bpvm/notifier_wpva.php';
new WpAutoUpdater(BPVMWPVA_ADDON_CURRENT_VERSION, $pluginRemoteUpdater, BPVMWPVA_UPDATER_SLUG);
