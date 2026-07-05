<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

  $demo    = get_option( 'streamcast_csf_demo_mode', false );
  $text    = ( ! empty( $demo ) ) ? 'Deactivate' : 'Activate';
  $status  = ( ! empty( $demo ) ) ? 'deactivate' : 'activate';
  $class   = ( ! empty( $demo ) ) ? ' streamcast-csf-warning-primary' : '';
  $section = ( ! empty( $_GET[ 'section' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'section' ] ) ) : 'about'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
  $links   = array(
    'about'           => 'About',
    'quickstart'      => 'Quick Start',
    'documentation'   => 'Documentation',
    'free-vs-premium' => 'Free vs Premium',
    'support'         => 'Support',
    'relnotes'        => 'Release Notes',
  );

?>
<div class="streamcast-csf-welcome streamcast-csf-welcome-wrap">

  <h1>Welcome to Codestar Framework v<?php echo esc_attr( STREAMCAST_STREAMCAST_CSF::$version ); ?></h1>

  <p class="streamcast-csf-about-text">A Simple and Lightweight WordPress Option Framework for Themes and Plugins</p>

  <p class="streamcast-csf-demo-button"><a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'streamcast-csf-demo' => $status ) ), 'streamcast-csf-demo-nonce' ) ); ?>" class="button button-primary<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $text ); ?> Demo</a></p>

  <div class="streamcast-csf-logo">
    <div class="streamcast-csf--effects"><i></i><i></i><i></i><i></i></div>
    <div class="streamcast-csf--wp-logos">
      <div class="streamcast-csf--wp-logo"></div>
      <div class="streamcast-csf--wp-plugin-logo"></div>
    </div>
    <div class="streamcast-csf--text">Codestar Framework</div>
    <div class="streamcast-csf--text streamcast-csf--version">v<?php echo esc_attr( STREAMCAST_STREAMCAST_CSF::$version ); ?></div>
  </div>

  <h2 class="nav-tab-wrapper wp-clearfix">
    <?php

      foreach ( $links as $key => $link ) {

        if ( STREAMCAST_STREAMCAST_CSF::$premium && $key === 'free-vs-premium' ) { continue; }

        $activate = ( $section === $key ) ? ' nav-tab-active' : '';

        echo '<a href="'. esc_url( add_query_arg( array( 'page' => 'streamcast-csf-welcome', 'section' => $key ), admin_url( 'tools.php' ) ) ) .'" class="nav-tab'. esc_attr( $activate ) .'">'. esc_attr( $link ) .'</a>';

      }

    ?>
  </h2>
