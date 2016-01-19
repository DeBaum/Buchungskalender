<?php

namespace Bookings\Controller;

use help\BookingsHelper;
use Slim\Slim;
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
        $this->db->query($query);
        return $this->db->insert_id;
    }

    // region Request Helper
    protected function getSlim()
    {
        return Slim::getInstance();
    }

    /**
     * Resolves a specific parameter
     *
     * @param string $name Parameter name
     * @return array|mixed|null
     */
    protected function getParam($name)
    {
        return $this->getSlim()->request->params($name, null);
    }
    // endregion

    // region Validation Helper

    protected function isInt($value, $min = 1)
    {
        return BookingsHelper::isInt($value, $min);
    }

    protected function isString($value, $minLength = 1)
    {
        return BookingsHelper::isString($value, $minLength);
    }

    protected function isDate($value)
    {
        return BookingsHelper::isDate($value);
    }

    // endregion

    // region Internal Helper

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
        $sql = str_replace("''", "NULL", $sql); // wp converts nullable strings to ''
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

    // endregion
}