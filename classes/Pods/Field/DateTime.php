<?php

/**
 * @package Pods\Fields
 */
class Pods_Field_DateTime extends
	Pods_Field {

	/**
	 * Field Type Group
	 *
	 * @var string
	 * @since 2.0
	 */
	public static $group = 'Date / Time';

	/**
	 * Field Type Identifier
	 *
	 * @var string
	 * @since 2.0
	 */
	public static $type = 'datetime';

	/**
	 * Field Type Label
	 *
	 * @var string
	 * @since 2.0
	 */
	public static $label = 'Date / Time';

	/**
	 * Field Type Preparation
	 *
	 * @var string
	 * @since 2.0
	 */
	public static $prepare = '%s';

	/**
	 * {@inheritDocs}
	 */
	public function __construct() {

	}

	/**
	 * {@inheritDocs}
	 */
	public function options() {
		$options = array(
			self::$type . '_repeatable'     => array(
				'label'             => __( 'Repeatable Field', 'pods' ),
				'default'           => 0,
				'type'              => 'boolean',
				'help'              => __( 'Making a field repeatable will add controls next to the field which allows users to Add/Remove/Reorder additional values. These values are saved in the database as an array, so searching and filtering by them may require further adjustments".', 'pods' ),
				'boolean_yes_label' => '',
				'dependency'        => true,
				'developer_mode'    => true
			),
			self::$type . '_format'         => array(
				'label'      => __( 'Date Format', 'pods' ),
				'default'    => 'mdy',
				'type'       => 'pick',
				'data'       => array(
					'mdy'       => date_i18n( 'm/d/Y' ),
					'mdy_dash'  => date_i18n( 'm-d-Y' ),
					'mdy_dot'   => date_i18n( 'm.d.Y' ),
					'ymd_slash' => date_i18n( 'Y/m/d' ),
					'ymd_dash'  => date_i18n( 'Y-m-d' ),
					'ymd_dot'   => date_i18n( 'Y.m.d' ),
					'fjy'       => date_i18n( 'F j, Y' ),
					'fjsy'      => date_i18n( 'F jS, Y' ),
					'Dfjy'      => date_i18n( 'D F j, Y' ),
					'lfjsy'     => date_i18n( 'l F jS, Y' ),
					'c'         => date_i18n( 'c' )
				),
				'dependency' => true
			),
			self::$type . '_time_type'      => array(
				'label'       => __( 'Time Format Type', 'pods' ),
				'excludes-on' => array( self::$type . '_format' => 'c' ),
				'default'     => '12',
				'type'        => 'pick',
				'data'        => array(
					'12' => __( '12 hour', 'pods' ),
					'24' => __( '24 hour', 'pods' )
				),
				'dependency'  => true
			),
			self::$type . '_time_format'    => array(
				'label'       => __( 'Time Format', 'pods' ),
				'depends-on'  => array( self::$type . '_time_type' => '12' ),
				'excludes-on' => array( self::$type . '_format' => 'c' ),
				'default'     => 'h_mma',
				'type'        => 'pick',
				'data'        => array(
					'h_mm_A'     => date_i18n( 'g:i A' ),
					'h_mm_ss_A'  => date_i18n( 'g:i:s A' ),
					'hh_mm_A'    => date_i18n( 'h:i A' ),
					'hh_mm_ss_A' => date_i18n( 'h:i:s A' ),
					'h_mma'      => date_i18n( 'g:ia' ),
					'hh_mma'     => date_i18n( 'h:ia' ),
					'h_mm'       => date_i18n( 'g:i' ),
					'h_mm_ss'    => date_i18n( 'g:i:s' ),
					'hh_mm'      => date_i18n( 'h:i' ),
					'hh_mm_ss'   => date_i18n( 'h:i:s' )
				)
			),
			self::$type . '_time_format_24' => array(
				'label'      => __( 'Time Format', 'pods' ),
				'depends-on' => array( self::$type . '_time_type' => '24' ),
				'default'    => 'hh_mm',
				'type'       => 'pick',
				'data'       => array(
					'hh_mm'    => date_i18n( 'H:i' ),
					'hh_mm_ss' => date_i18n( 'H:i:s' )
				)
			),
			self::$type . '_year_range' => array(
				'label' => __( 'Year Range', 'pods' ),
				'default' => 'c-10:c+10',
				'type' => 'text',
				'help' => __( 'The range of years displayed in the year drop-down: either relative to today\'s year ("-nn:+nn"), relative to the currently selected year ("c-nn:c+nn"), absolute ("nnnn:nnnn"), or combinations of these formats ("nnnn:-nn").', 'pods' )
			),
			self::$type . '_min_date' => array(
				'label' => __( 'Min Date', 'pods' ),
				'default' => '',
				'type' => 'text',
				'help' => __( 'The minimum selectable date. When empty, there is no minimum.', 'pods' )
			),
			self::$type . '_max_date' => array(
				'label' => __( 'Max Date', 'pods' ),
				'default' => '',
				'type' => 'text',
				'help' => __( 'The maximum selectable date. When empty, there is no maximum.', 'pods' )
			), 
			self::$type . '_allow_empty'    => array(
				'label'   => __( 'Allow empty value?', 'pods' ),
				'default' => 1,
				'type'    => 'boolean'
			),
			self::$type . '_html5'          => array(
				'label'   => __( 'Enable HTML5 Input Field?', 'pods' ),
				'default' => apply_filters( 'pods_form_ui_field_html5', 0, self::$type ),
				'type'    => 'boolean'
			)
		);

		// Check if PHP DateTime::createFromFormat exists for additional supported formats
		if ( method_exists( 'DateTime', 'createFromFormat' ) || apply_filters( 'pods_form_ui_field_datetime_custom_formatter', false ) ) {
			$options[ self::$type . '_format' ]['data'] = array_merge( $options[ self::$type . '_format' ]['data'],
				array(
					'dmy'      => date_i18n( 'd/m/Y' ),
					'dmy_dash' => date_i18n( 'd-m-Y' ),
					'dmy_dot'  => date_i18n( 'd.m.Y' ),
					'dMy'      => date_i18n( 'd/M/Y' ),
					'dMy_dash' => date_i18n( 'd-M-Y' )
				) );
		}

		$options[ self::$type . '_format' ]['data']    = apply_filters( 'pods_form_ui_field_date_format_options', $options[ self::$type . '_format' ]['data'] );
		$options[ self::$type . '_format' ]['default'] = apply_filters( 'pods_form_ui_field_date_format_default', $options[ self::$type . '_format' ]['default'] );

		$options[ self::$type . '_time_type' ]['default']      = apply_filters( 'pods_form_ui_field_time_format_type_default', $options[ self::$type . '_time_type' ]['default'] );
		$options[ self::$type . '_time_format' ]['data']       = apply_filters( 'pods_form_ui_field_time_format_options', $options[ self::$type . '_time_format' ]['data'] );
		$options[ self::$type . '_time_format' ]['default']    = apply_filters( 'pods_form_ui_field_time_format_default', $options[ self::$type . '_time_format' ]['default'] );
		$options[ self::$type . '_time_format_24' ]['data']    = apply_filters( 'pods_form_ui_field_time_format_24_options', $options[ self::$type . '_time_format_24' ]['data'] );
		$options[ self::$type . '_time_format_24' ]['default'] = apply_filters( 'pods_form_ui_field_time_format_24_default', $options[ self::$type . '_time_format_24' ]['default'] );

		return $options;
	}

	/**
	 * {@inheritDocs}
	 */
	public function schema( $options = null ) {
		$schema = 'DATETIME NOT NULL default "0000-00-00 00:00:00"';

		return $schema;
	}

	/**
	 * {@inheritDocs}
	 */
	public function display( $value = null, $name = null, $options = null, $pod = null, $id = null ) {
		$format = $this->format( $options );

		if ( ! empty( $value ) && ! in_array( $value, array( '0000-00-00', '0000-00-00 00:00:00', '00:00:00' ) ) ) {
			$date       = $this->createFromFormat( 'Y-m-d H:i:s', (string) $value );
			$date_local = $this->createFromFormat( $format, (string) $value );

			if ( false !== $date ) {
				$value = $date->format( $format );
			} elseif ( false !== $date_local ) {
				$value = $date_local->format( $format );
			} else {
				$value = date_i18n( $format, strtotime( (string) $value ) );
			}
		} elseif ( 0 == pods_v( self::$type . '_allow_empty', $options, 1 ) ) {
			$value = date_i18n( $format );
		} else {
			$value = '';
		}

		return $value;
	}

	/**
	 * {@inheritDocs}
	 */
	public function input( $name, $value = null, $options = null, $pod = null, $id = null ) {
		$form_field_type = Pods_Form::$field_type;

		if ( is_array( $value ) ) {
			$value = implode( ' ', $value );
		}

		// Format Value
		$value = $this->display( $value, $name, $options, null, $pod, $id );

		$field_type = 'datetime';

		if ( isset( $options['name'] ) && false === Pods_Form::permission( self::$type, $options['name'], $options, null, $pod, $id ) ) {
			if ( pods_v( 'read_only', $options, false ) ) {
				$options['readonly'] = true;

				$field_type = 'text';
			} else {
				return;
			}
		} elseif ( ! pods_has_permissions( $options ) && pods_v( 'read_only', $options, false ) ) {
			$options['readonly'] = true;

			$field_type = 'text';
		}

		pods_view( PODS_DIR . 'ui/fields/' . $field_type . '.php', compact( array_keys( get_defined_vars() ) ) );
	}

	/**
	 * {@inheritDocs}
	 */
	public function pre_save( $value, $id = null, $name = null, $options = null, $fields = null, $pod = null, $params = null ) {
		$format = $this->format( $options );

		if ( ! empty( $value ) && ( 0 == pods_v( self::$type . '_allow_empty', $options, 1 ) || ! in_array( $value, array( '0000-00-00', '0000-00-00 00:00:00', '00:00:00' ) ) ) ) {
			$value = $this->convert_date( $value, 'Y-m-d H:i:s', $format );
		} elseif ( 1 == pods_v( self::$type . '_allow_empty', $options, 1 ) ) {
			$value = '0000-00-00 00:00:00';
		} else {
			$value = current_time( 'mysql' );
		}

		return $value;
	}

	/**
	 * {@inheritDocs}
	 */
	public function ui( $id, $value, $name = null, $options = null, $fields = null, $pod = null ) {
		$value = $this->display( $value, $name, $options, $pod, $id );

		if ( 1 == pods_v( self::$type . '_allow_empty', $options, 1 ) && ( empty( $value ) || in_array( $value, array( '0000-00-00', '0000-00-00 00:00:00', '00:00:00' ) ) ) ) {
			$value = false;
		}

		return $value;
	}

	/**
	 * Build date/time format string based on options
	 *
	 * @param $options
	 *
	 * @return string
	 * @since 2.0
	 */
	public function format( $options ) {
		$date_format = array(
			'mdy'       => 'm/d/Y',
			'mdy_dash'  => 'm-d-Y',
			'mdy_dot'   => 'm.d.Y',
			'dmy'       => 'd/m/Y',
			'dmy_dash'  => 'd-m-Y',
			'dmy_dot'   => 'd.m.Y',
			'ymd_slash' => 'Y/m/d',
			'ymd_dash'  => 'Y-m-d',
			'ymd_dot'   => 'Y.m.d',
			'dMy'       => 'd/M/Y',
			'dMy_dash'  => 'd-M-Y',
			'fjy'       => 'F j, Y',
			'fjsy'      => 'F jS, Y',
			'Dfjy'      => 'D F j, Y',
			'lfjsy'     => 'l F jS, Y',
			'c'         => 'c'
		);

		$time_format = array(
			'h_mm_A'     => 'g:i A',
			'h_mm_ss_A'  => 'g:i:s A',
			'hh_mm_A'    => 'h:i A',
			'hh_mm_ss_A' => 'h:i:s A',
			'h_mma'      => 'g:ia',
			'hh_mma'     => 'h:ia',
			'h_mm'       => 'g:i',
			'h_mm_ss'    => 'g:i:s',
			'hh_mm'      => 'h:i',
			'hh_mm_ss'   => 'h:i:s'
		);

		$time_format_24 = array(
			'hh_mm'    => 'H:i',
			'hh_mm_ss' => 'H:i:s'
		);

		$date_format = apply_filters( 'pods_form_ui_field_date_formats', $date_format );

		$time_format    = apply_filters( 'pods_form_ui_field_time_formats', $time_format );
		$time_format_24 = apply_filters( 'pods_form_ui_field_time_formats_24', $time_format_24 );

		$format_value = pods_v( self::$type . '_format', $options, 'ymd_dash', true );

		$format = $date_format[ $format_value ];

		if ( 'c' != $format_value ) {
			if ( 12 == pods_v( self::$type . '_time_type', $options ) ) {
				$format .= ' ' . $time_format[ pods_v( self::$type . '_time_format', $options, 'hh_mm', true ) ];
			} else {
				$format .= ' ' . $time_format_24[ pods_v( self::$type . '_time_format_24', $options, 'hh_mm', true ) ];
			}
		}

		return $format;
	}

	/**
	 * @param $format
	 * @param $date
	 *
	 * @return DateTime
	 */
	public function createFromFormat( $format, $date ) {
		$datetime = false;

		if ( method_exists( 'DateTime', 'createFromFormat' ) ) {
			$timezone = get_option( 'timezone_string' );

			if ( empty( $timezone ) ) {
				$timezone = timezone_name_from_abbr( '', get_option( 'gmt_offset' ) * HOUR_IN_SECONDS, 0 );
			}

			if ( ! empty( $timezone ) ) {
				$datetimezone = new DateTimeZone( $timezone );

				$datetime = DateTime::createFromFormat( $format, (string) $date, $datetimezone );
			}
		}

		if ( false === $datetime ) {
			$datetime = new DateTime( date_i18n( 'Y-m-d H:i:s', strtotime( (string) $date ) ) );
		}

		return apply_filters( 'pods_form_ui_field_datetime_formatter', $datetime, $format, $date );
	}

	/**
	 * Convert a date from one format to another
	 *
	 * @param        $value
	 * @param        $new_format
	 * @param string $original_format
	 *
	 * @return string
	 */
	public function convert_date( $value, $new_format, $original_format = 'Y-m-d H:i:s' ) {
		if ( ! empty( $value ) && ! in_array( $value, array( '0000-00-00', '0000-00-00 00:00:00', '00:00:00' ) ) ) {
			$date = $this->createFromFormat( $original_format, (string) $value );

			if ( false !== $date ) {
				$value = $date->format( $new_format );
			} else {
				$value = date_i18n( $new_format, strtotime( (string) $value ) );
			}
		} else {
			$value = date_i18n( $new_format );
		}

		return $value;
	}
}
