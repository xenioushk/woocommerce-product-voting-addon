<?php

namespace WPVAADDON\Widgets;

use WPVAADDON\Widgets\WpvaWidget;
/**
 * Register widgets
 *
 * @return void
 */
function register_widgets() {

    register_widget( WpvaWidget::class );
}

add_action( 'widgets_init', __NAMESPACE__ . '\\register_widgets' );
