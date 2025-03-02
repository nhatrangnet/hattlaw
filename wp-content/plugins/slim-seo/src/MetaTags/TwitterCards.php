<?php
namespace SlimSEO\MetaTags;

use SlimSEO\Helpers\Option;

class TwitterCards {
	private $image_obj;

	public function __construct() {
		$this->image_obj = new Image( 'twitter_image' );
	}

	public function setup(): void {
		add_action( 'slim_seo_head', [ $this, 'output' ] );
	}

	/**
	 * Twitter uses OpenGraph, so no need to output duplicated tags.
	 *
	 * @link https://developer.twitter.com/en/docs/tweets/optimize-with-cards/guides/getting-started
	 */
	public function output(): void {
		$type = apply_filters( 'slim_seo_twitter_card_type', 'summary_large_image' );
		echo '<meta name="twitter:card" content="', esc_attr( $type ), '">', "\n";

		$image = $this->image_obj->get_value() ?: $this->get_default_image();
		$image = $image['src'] ?? '';
		$image = apply_filters( 'slim_seo_twitter_card_image', $image );
		if ( ! empty( $image ) ) {
			echo '<meta name="twitter:image" content="', esc_url( $image ), '">', "\n";
		}

		$site = $this->get_site();
		$site = apply_filters( 'slim_seo_twitter_card_site', $site );
		if ( $site ) {
			echo '<meta name="twitter:site" content="', esc_attr( $site ), '">', "\n";
		}
	}

	private function get_default_image(): array {
		$url = Option::get( 'default_twitter_image', '' );
		return $url ? $this->image_obj->get_data_from_url( $url ) : [];
	}

	private function get_site(): string {
		return Option::get( 'twitter_site', '' );
	}
}
