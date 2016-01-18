<?php

namespace Bookings\Controller;

abstract class BaseController
{
    protected $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    protected function fetchAll($query, $args = null)
    {
        $query = $this->db->prepare($this->parsePrepare($query), $args);
        return $this->db->get_results($query);
    }

    /**
     * Converts the given statement to the WordPress-compliant format
     * `SELECT ... WHERE id = :d` will get `SELECT ... WHERE id = %d`
     *
     * @param $sql
     *
     * @return mixed
     */
    private function parsePrepare($sql)
    {
        $sql = str_replace(":d", "%d", $sql);
        $sql = str_replace(":s", "%s", $sql);

        return $sql;
    }

    protected function fetchOne($query, $args = null)
    {
        $query = $this->db->prepare($this->parsePrepare($query), $args);
        $results = $this->db->get_results($query);
        if (sizeof($results) === 0) {
            return null;
        } else {
            return $results[0];
        }
    }

    protected function updateAll($query, $args = null)
    {
        $parsedQuery = $this->parsePrepare($query);
        $query = $this->db->prepare($parsedQuery, $args);
        $affectedRows = $this->db->query($query);

        return $affectedRows > 0;
    }

    protected function insert($query, $args = null)
    {
        $parsedQuery = $this->parsePrepare($query);
        $query = $this->db->prepare($parsedQuery, $args);
        $query = str_replace("''", "NULL", $query); // wp converts nullable strins to ''
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
}