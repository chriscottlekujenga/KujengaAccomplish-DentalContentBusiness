<?php
/**
 * Plugin Name: Brush With Me Essentials
 * Description: Staging-only presentation and clinical-review workflow.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	register_post_type( 'bwm_clinical_review', array(
		'labels' => array(
			'name' => 'Clinical Reviews',
			'singular_name' => 'Clinical Review',
			'add_new_item' => 'Add Clinical Review',
			'edit_item' => 'Review submission',
		),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => 'dashicons-clipboard',
		'supports' => array( 'title', 'editor', 'custom-fields' ),
		'capability_type' => 'post',
		'map_meta_cap' => true,
	) );
	add_rewrite_rule( '^review/?$', 'index.php?bwm_review=1', 'top' );
	if ( '1' !== get_option( 'bwm_essentials_rewrite_version' ) ) {
		flush_rewrite_rules();
		update_option( 'bwm_essentials_rewrite_version', '1', false );
	}
} );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'bwm_review';
	return $vars;
} );

add_action( 'after_switch_theme', function () { flush_rewrite_rules(); } );

function bwm_review_access() {
	if ( session_status() === PHP_SESSION_NONE ) {
		session_start();
	}
	$configured = defined( 'BWM_REVIEW_PASSWORD_HASH' ) && BWM_REVIEW_PASSWORD_HASH;
	$error = '';
	if ( isset( $_GET['review_lock'] ) ) {
		unset( $_SESSION['bwm_review_unlocked'] );
	}
	if ( $configured && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['bwm_gate_nonce'] ) ) {
		$nonce = sanitize_text_field( wp_unslash( $_POST['bwm_gate_nonce'] ) );
		$password = (string) wp_unslash( $_POST['review_password'] ?? '' );
		if ( ! wp_verify_nonce( $nonce, 'bwm_review_gate' ) || ! password_verify( $password, BWM_REVIEW_PASSWORD_HASH ) ) {
			$error = 'That password does not look right. Please try again.';
		} else {
			$_SESSION['bwm_review_unlocked'] = true;
		}
	}
	return array(
		'configured' => (bool) $configured,
		'unlocked' => ! empty( $_SESSION['bwm_review_unlocked'] ),
		'error' => $error,
	);
}

function bwm_render_review_page() {
	$access = bwm_review_access();
	$submitted = false;
	$error = '';
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['bwm_review_nonce'] ) ) {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bwm_review_nonce'] ) ), 'bwm_submit_review' ) ) {
			$error = 'Your session expired. Please refresh and try again.';
		} else {
			$name = sanitize_text_field( wp_unslash( $_POST['reviewer_name'] ?? '' ) );
			$email = sanitize_email( wp_unslash( $_POST['reviewer_email'] ?? '' ) );
			$url = esc_url_raw( wp_unslash( $_POST['content_url'] ?? '' ) );
			$accuracy = sanitize_text_field( wp_unslash( $_POST['clinical_accuracy'] ?? '' ) );
			$clarity = sanitize_text_field( wp_unslash( $_POST['parent_clarity'] ?? '' ) );
			$safety = sanitize_textarea_field( wp_unslash( $_POST['safety_notes'] ?? '' ) );
			$notes = sanitize_textarea_field( wp_unslash( $_POST['review_notes'] ?? '' ) );
			if ( ! $name || ! $email || ! $url || ! $accuracy || ! $clarity ) {
				$error = 'Please complete the required fields.';
			} else {
				$content = "Reviewer: {$name}\nEmail: {$email}\nContent URL: {$url}\n\nClinical accuracy: {$accuracy}\nParent clarity: {$clarity}\n\nSafety notes:\n{$safety}\n\nReviewer notes:\n{$notes}";
				$post_id = wp_insert_post( array(
					'post_type' => 'bwm_clinical_review',
					'post_status' => 'private',
					'post_title' => 'Review — ' . $name . ' — ' . wp_date( 'M j, Y g:i a' ),
					'post_content' => $content,
				), true );
				if ( is_wp_error( $post_id ) ) {
					$error = 'We could not save this review. Please try again.';
				} else {
					update_post_meta( $post_id, '_bwm_reviewer_email', $email );
					update_post_meta( $post_id, '_bwm_content_url', $url );
					$submitted = true;
				}
			}
		}
	}
	status_header( 200 );
	nocache_headers();
	?>
	<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Clinical review | <?php bloginfo( 'name' ); ?></title><?php wp_head(); ?></head>
	<body class="bwm-review-page"><main class="bwm-review-shell"><a class="bwm-wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>">Brush <span>With Me</span></a>
	<?php if ( ! $access['configured'] ) : ?><section class="bwm-review-card"><p class="bwm-kicker">Review access unavailable</p><h1>This review workspace is being secured.</h1><p class="bwm-intro">Please contact the site owner for access.</p></section>
	<?php elseif ( ! $access['unlocked'] ) : ?><section class="bwm-review-card"><p class="bwm-kicker">Clinical review workspace</p><h1>Enter the reviewer password.</h1><p class="bwm-intro">This keeps clinical submissions available only to invited reviewers.</p><?php if ( $access['error'] ) : ?><p class="bwm-form-error"><?php echo esc_html( $access['error'] ); ?></p><?php endif; ?><form method="post" class="bwm-review-form"><?php wp_nonce_field( 'bwm_review_gate', 'bwm_gate_nonce' ); ?><label>Reviewer password <input required type="password" name="review_password" autocomplete="current-password"></label><button class="bwm-button" type="submit">Open review workspace</button></form></section>
	<?php elseif ( $submitted ) : ?><section class="bwm-success"><p class="bwm-kicker">Saved privately</p><h1>Thank you — your review is in.</h1><p>Your submission is now stored in the WordPress dashboard under <strong>Clinical Reviews</strong>.</p><a class="bwm-button" href="<?php echo esc_url( home_url( '/review/' ) ); ?>">Submit another review</a></section>
	<?php else : ?><section class="bwm-review-card"><p class="bwm-kicker">Clinical review workspace</p><h1>Help us keep every routine trustworthy.</h1><p class="bwm-intro">This private workflow saves directly to Brush With Me’s WordPress dashboard. It is not published on the site.</p><?php if ( $error ) : ?><p class="bwm-form-error"><?php echo esc_html( $error ); ?></p><?php endif; ?>
	<form method="post" class="bwm-review-form"><?php wp_nonce_field( 'bwm_submit_review', 'bwm_review_nonce' ); ?>
	<div class="bwm-grid"><label>Your name <input required name="reviewer_name" value="<?php echo esc_attr( wp_unslash( $_POST['reviewer_name'] ?? '' ) ); ?>"></label><label>Email <input required type="email" name="reviewer_email" value="<?php echo esc_attr( wp_unslash( $_POST['reviewer_email'] ?? '' ) ); ?>"></label></div>
	<label>Content or draft URL <input required type="url" name="content_url" placeholder="https://"></label>
	<div class="bwm-grid"><label>Clinical accuracy <select required name="clinical_accuracy"><option value="">Select one</option><option>Accurate as written</option><option>Minor changes needed</option><option>Needs revision</option></select></label><label>Clarity for parents <select required name="parent_clarity"><option value="">Select one</option><option>Clear and actionable</option><option>Mostly clear</option><option>Needs simplification</option></select></label></div>
	<label>Safety or scope notes <textarea name="safety_notes" rows="4" placeholder="Flag anything that needs clinical context, a disclaimer, or a referral to a dentist."></textarea></label><label>Reviewer notes <textarea name="review_notes" rows="5" placeholder="Suggested edits, sources to check, or approval notes."></textarea></label><button class="bwm-button" type="submit">Save clinical review</button></form></section><?php endif; ?></main><?php wp_footer(); ?></body></html><?php exit;
}

add_action( 'template_redirect', function () {
	if ( get_query_var( 'bwm_review' ) ) {
		bwm_render_review_page();
	}
} );

add_filter( 'the_content', function ( $content ) {
	if ( ! is_admin() && is_front_page() && in_the_loop() && is_main_query() ) {
		return '<section class="bwm-hero"><div><p class="bwm-kicker">Made for real-life bathroom counters</p><h1>Little routines.<br><em>Big confidence.</em></h1><p class="bwm-lede">Simple, reassuring oral-care guides for families—reviewed by a Registered Dental Hygienist and built for the moments that actually happen.</p><div class="bwm-actions"><a class="bwm-button" href="' . esc_url( home_url( '/brush-or-floss-first/' ) ) . '">Start with a routine</a><a class="bwm-text-link" href="#bwm-start">See where to begin <span>→</span></a></div><p class="bwm-proof">✓ Reviewed by Jessie, RDH &nbsp; · &nbsp; 25 years in clinical care</p></div><div class="bwm-hero-art" aria-hidden="true"><span class="bwm-sun"></span><span class="bwm-smile">☺</span><span class="bwm-spark one">✦</span><span class="bwm-spark two">✦</span></div></section><section class="bwm-value-grid"><article><span>01</span><h2>Clear, not clinical.</h2><p>Exact steps, gentle language, and no confusing jargon for tired parents.</p></article><article><span>02</span><h2>Guidance with guardrails.</h2><p>Helpful hygiene education that respects your family dentist’s expertise.</p></article><article><span>03</span><h2>For every growing stage.</h2><p>From first-tooth questions to independent big-kid habits.</p></article></section><section id="bwm-start" class="bwm-start"><div><p class="bwm-kicker">Start here</p><h2>One small habit can change the whole evening.</h2><p>Choose the guide that fits your family tonight. Each one is designed to feel doable, not perfect.</p></div><div class="bwm-guide-card"><p class="bwm-card-label">ROUTINE 01</p><h3>Brush or floss first?</h3><p>The order debate, settled with a hygienist’s logic.</p><a href="' . esc_url( home_url( '/brush-or-floss-first/' ) ) . '">Read the guide <span>→</span></a></div></section><section class="bwm-trust"><p class="bwm-kicker">The Brush With Me standard</p><h2>Would a hygienist hand this to her own family?</h2><p>That is the filter behind every guide, recommendation, and product note we share.</p><p class="bwm-signoff">— Jessie Tang, RDH</p></section><section class="bwm-newsletter"><div><p class="bwm-kicker">A calmer sink-side routine</p><h2>A little encouragement, once a week.</h2><p>One habit to try, one helpful note, zero scare tactics.</p></div><a class="bwm-button bwm-button-light" href="' . esc_url( home_url( '/#newsletter' ) ) . '">Join the list</a></section>';
	}
	return $content;
}, 99 );

add_action( 'wp_head', function () {
	?>
	<style id="bwm-essentials-css">
	:root{--bwm-ink:#17364c;--bwm-sky:#eaf6fb;--bwm-mint:#dff4eb;--bwm-coral:#f4836d;--bwm-paper:#fffdf9;--bwm-line:#d8e4e7}body{background:var(--bwm-paper);color:var(--bwm-ink)}.site-header,.site-footer-wrap{background:#fff!important}.site-header{border-bottom:1px solid var(--bwm-line)}.site-branding .site-title,.site-title a{color:var(--bwm-ink)!important;font-weight:800}.main-navigation a{font-weight:700!important}.entry-content{margin-top:0!important}.entry-content>section{max-width:none!important}.bwm-hero{max-width:1240px!important;margin:0 auto!important;padding:96px 48px 82px;display:grid;grid-template-columns:1.15fr .85fr;gap:52px;align-items:center;background:linear-gradient(135deg,#eef9fc 0%,#fffdf9 62%);overflow:hidden}.bwm-kicker,.bwm-card-label{font-size:.74rem!important;line-height:1.2!important;text-transform:uppercase;letter-spacing:.13em;font-weight:800;color:#4a8294;margin:0 0 18px!important}.bwm-hero h1{font-size:clamp(3rem,6.2vw,5.8rem)!important;line-height:.98!important;letter-spacing:-.055em;color:var(--bwm-ink);margin:0 0 24px!important}.bwm-hero h1 em{font-family:Georgia,serif;font-weight:400;color:var(--bwm-coral)}.bwm-lede{font-size:1.18rem;line-height:1.65;max-width:590px}.bwm-actions{display:flex;gap:25px;align-items:center;flex-wrap:wrap;margin-top:32px}.bwm-button{display:inline-block!important;border:0!important;border-radius:999px!important;background:var(--bwm-ink)!important;color:#fff!important;padding:15px 25px!important;font-weight:800!important;text-decoration:none!important;box-shadow:none!important}.bwm-button:hover{background:#275675!important;transform:translateY(-1px)}.bwm-text-link,.bwm-guide-card a{font-weight:800;text-decoration:none;color:var(--bwm-ink)}.bwm-text-link span,.bwm-guide-card span{color:var(--bwm-coral);font-size:1.25em}.bwm-proof{margin-top:36px!important;font-size:.9rem;color:#527085}.bwm-hero-art{min-height:390px;border-radius:40% 60% 55% 45% / 44% 42% 58% 56%;background:var(--bwm-mint);position:relative;overflow:hidden}.bwm-sun{position:absolute;width:250px;height:250px;border-radius:50%;background:#fbd98d;top:48px;left:52px}.bwm-smile{position:absolute;z-index:2;left:calc(50% - 66px);top:100px;width:132px;height:132px;border-radius:45%;display:grid;place-items:center;background:#fff;font-size:4.9rem;line-height:1;color:var(--bwm-ink);transform:rotate(-7deg)}.bwm-spark{position:absolute;z-index:3;color:var(--bwm-coral);font-size:2.5rem}.bwm-spark.one{right:42px;top:55px}.bwm-spark.two{left:44px;bottom:46px;font-size:1.6rem}.bwm-value-grid{max-width:1240px!important;margin:0 auto!important;padding:0 48px 100px;display:grid;grid-template-columns:repeat(3,1fr);gap:18px;background:#fffdf9}.bwm-value-grid article{padding:30px;border:1px solid var(--bwm-line);border-radius:18px;background:#fff}.bwm-value-grid span{font-weight:800;color:var(--bwm-coral)}.bwm-value-grid h2{font-size:1.35rem;margin:28px 0 10px}.bwm-value-grid p{margin:0;line-height:1.55}.bwm-start{max-width:1240px!important;margin:0 auto!important;padding:90px 48px;display:grid;grid-template-columns:1fr 1fr;gap:75px;align-items:center;background:var(--bwm-sky)}.bwm-start h2,.bwm-trust h2,.bwm-newsletter h2{font-size:clamp(2rem,3.8vw,3.5rem)!important;line-height:1.06!important;letter-spacing:-.04em;margin:0 0 18px!important;color:var(--bwm-ink)}.bwm-start>div>p:not(.bwm-kicker){font-size:1.1rem;line-height:1.65;max-width:500px}.bwm-guide-card{background:var(--bwm-ink);color:#fff;border-radius:22px;padding:43px;transform:rotate(2deg);box-shadow:16px 16px 0 #b4dce5}.bwm-guide-card h3{color:#fff;font-size:2rem;line-height:1.1;margin:8px 0 14px}.bwm-guide-card a{color:#fff;display:inline-block;margin-top:22px}.bwm-trust{max-width:1240px!important;margin:0 auto!important;padding:110px 22% 106px;text-align:center;background:var(--bwm-paper)}.bwm-trust>p:not(.bwm-kicker):not(.bwm-signoff){font-size:1.15rem;line-height:1.7}.bwm-signoff{font-family:Georgia,serif;font-style:italic;color:#4a8294;margin-top:28px!important}.bwm-newsletter{max-width:1240px!important;margin:0 auto!important;padding:62px 48px;display:flex;align-items:center;justify-content:space-between;gap:35px;background:var(--bwm-coral);color:#fff}.bwm-newsletter .bwm-kicker{color:#fff1e9}.bwm-newsletter h2{color:#fff!important}.bwm-newsletter p{margin:0}.bwm-button-light{background:#fff!important;color:var(--bwm-ink)!important;white-space:nowrap}.bwm-review-page{background:linear-gradient(135deg,#eaf6fb,#fffdf9);min-height:100vh}.bwm-review-shell{max-width:820px;margin:0 auto;padding:44px 22px 80px}.bwm-wordmark{display:inline-block;font-size:1.3rem;font-weight:800;color:var(--bwm-ink);text-decoration:none;margin-bottom:48px}.bwm-wordmark span{color:var(--bwm-coral)}.bwm-review-card,.bwm-success{background:#fff;border:1px solid var(--bwm-line);border-radius:24px;padding:48px;box-shadow:0 20px 50px rgba(26,70,88,.08)}.bwm-review-card h1,.bwm-success h1{font-size:clamp(2.2rem,4.5vw,3.7rem);line-height:1.05;margin:0 0 14px;color:var(--bwm-ink)}.bwm-intro{line-height:1.6;color:#517084}.bwm-review-form{margin-top:30px}.bwm-review-form label{display:block;font-weight:800;font-size:.9rem;margin:19px 0 0;color:var(--bwm-ink)}.bwm-review-form input,.bwm-review-form select,.bwm-review-form textarea{width:100%;box-sizing:border-box;margin-top:7px;border:1px solid #c8d9de;border-radius:10px;padding:12px;font:inherit;color:var(--bwm-ink);background:#fff}.bwm-review-form textarea{resize:vertical}.bwm-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.bwm-review-form .bwm-button{margin-top:27px;cursor:pointer}.bwm-form-error{color:#9d3730;font-weight:700;background:#fff0ee;padding:12px;border-radius:8px}.bwm-success{text-align:center;margin-top:48px}.bwm-success p{font-size:1.1rem;line-height:1.65}.bwm-success .bwm-button{margin-top:14px}@media(max-width:700px){.bwm-hero,.bwm-start{grid-template-columns:1fr;padding:62px 25px;gap:35px}.bwm-hero-art{min-height:300px}.bwm-value-grid{grid-template-columns:1fr;padding:0 25px 62px}.bwm-trust{padding:75px 25px}.bwm-newsletter{padding:45px 25px;display:block}.bwm-newsletter .bwm-button{margin-top:24px}.bwm-grid{grid-template-columns:1fr}.bwm-review-card,.bwm-success{padding:30px 22px}}
	</style>
	<?php
} );
