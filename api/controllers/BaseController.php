<?php

namespace Bookings\Controller;

use wpdb;

/**
 * The BaseController provides general information and logic for
 * controllers (like access to the database and validation).
 *
 * @package Bookings\Controller
 */
abstract class BaseController
{
    /** @var wpdb WordPress database object */
    protected $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    /**
     * Fetches multiple rows from the database.
     *
     * @param $query string SELECT statement (optionally with wildcards)
     * @param $args array (optional) Arguments for the wildcards
     * @return array|null Database query results
     */
    protected function fetchAll($query, $args = null)
    {
        $query = $this->parsePrepare($query);
        if (self::hasPlaceholders($query)) {
            $query = $this->db->prepare($query, $args);
        }
        return $this->db->get_results($query);
    }

    /**
     * Converts the given statement to the WordPress-compliant format
     * `SELECT ... WHERE id = :d` will get `SELECT ... WHERE id = %d`.
     *
     * @param string $sql Unparsed statement
     * @return string Parsed statement
     */
    private function parsePrepare($sql)
    {
        $sql = str_replace(":d", "%d", $sql);
        $sql = str_replace(":s", "%s", $sql);
        return $sql;
    }

    /**
     * Checks if the query contains placeholders.
     *
     * @param string $query Query
     * @return bool If the query contains placeholders
     */
    private static function hasPlaceholders($query)
    {
        // this is important because the WordPress database object
        // throws an exception if prepare() is called without placeholders
        return strpos($query, '%') !== false;
    }

    /**
     * Fetches one row from the database.
     *
     * @param $query string SELECT statement (optionally with wildcards)
     * @param $args array (optional) Arguments for the wildcards
     * @return object|null Database query result
     */
    protected function fetchOne($query, $args = null)
    {
        $query = $this->parsePrepare($query);
        if (self::hasPlaceholders($query)) {
            $query = $this->db->prepare($query, $args);
        }
        $results = $this->db->get_results($query);
        if (sizeof($results) === 0) {
            return null;
        } else {
            return $results[0];
        }
    }

    // region Validation Helper

    /**
     * Updates one or more rows.
     *
     * @param $query string UPDATE statement (optionally with wildcards)
     * @param $args array (optional) Arguments for the wildcards
     * @return int|false Number of rows affected/selected or false on error
     */
    protected function updateAll($query, $args = null)
    {
        $parsedQuery = $this->parsePrepare($query);
        $query = $this->db->prepare($parsedQuery, $args);
        $affectedRows = $this->db->query($query);
        return $affectedRows;
    }

    /**
     * Inserts a row.
     *
     * @param $query string INSERT statement (optionally with wildcards)
     * @param $args array (optional) Arguments for the wildcards
     * @return int Last inserted id (auto_increment value)
     */
    protected function insert($query, $args = null)
    {
        $parsedQuery = $this->parsePrepare($query);
        $query = $this->db->prepare($parsedQuery, $args);
        $query = str_replace("''", "NULL", $query); // wp converts nullable strings to ''
        $this->db->query($query);
        return $this->db->insert_id;
    }

    protected function isInt($value, $min = 1)
    {
        if (!is_numeric($value)) {
            return false;
        }

        return $value >= $min;
    }

    // endregion

    //region Internal Helper

    protected function isString($value, $minLength = 1)
    {
        if ($value == null) {
            return false;
        }
        $value = trim($value);

        return strlen($value) >= $minLength;
    }

    protected function isDate($value)
    {
        return true; // TODO
    }

    //endregion
}