<?php

namespace Rarst\WordPress\DateTime;

/**
 * @see WpDateTimeTrait
 */
interface WpDateTimeInterface {

	public static function createFromPost( $post, $field = 'date' );

	public function formatI18n( $format );
}
