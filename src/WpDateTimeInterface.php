<?php

namespace Rarst\WordPress\DateTime;

/**
 * @see WpDateTimeTrait
 */
interface WpDateTimeInterface extends \DateTimeInterface {

	public static function createFromPost( $post, $field = 'date' );

	public function formatI18n( $format );

	public function formatDate();

	public function formatTime();
}
