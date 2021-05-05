<?php

/**
 * Plugin Name: Gravity Forms Fields Allowlist
 * Description: Limits Gravity Forms fields shown in the WordPress admin to only certain fields.
 * Version:     0.1.0
 * Author:      Kellen Mace
 * Author URI:  https://kellenmace.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

class GravityFormsFieldsAllowlist
{
    const ALLOWED_FIELDS = [
        'address',
        'checkbox',
        'date',
        'email',
        'multiselect',
        'name',
        'phone',
        'radio',
        'select',
        'textarea',
        'text',
        'time',
        'website',
    ];

    public function register_hooks(): void
    {
        add_filter('gform_add_field_buttons', [$this, 'restrict_to_allowed_fields']);
        add_filter('gform_add_field_buttons', [$this, 'remove_empty_field_groups'], 11);
    }

    /**
     * Restrict the list of Gravity Forms fields to only the allowed field types.
     * 
     * @param array $field_groups The field groups, including group name, label and fields.
     *
     * @return array The field groups, with disallowed fields removed.
     */
    public function restrict_to_allowed_fields(array $field_groups): array
    {
        $is_allowed = fn ($field) => in_array($field['data-type'], self::ALLOWED_FIELDS, true);

        foreach ($field_groups as &$field_group) {
            $field_group['fields'] = array_filter($field_group['fields'], $is_allowed);
        }

        return $field_groups;
    }

    /**
     * Remove empty Gravity Forms field groups.
     *
     * @param array $field_groups The field groups, including group name, label and fields.
     *
     * @return array The field groups, with empty field groups removed.
     */
    public function remove_empty_field_groups(array $field_groups): array
    {
        return array_filter($field_groups, fn ($field_group) => (bool) $field_group['fields']);
    }
}

(new GravityFormsFieldsAllowlist())->register_hooks();
