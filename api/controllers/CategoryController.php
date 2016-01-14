<?php

namespace Bookings\Controller;


use Bookings\GlobalErrors;
use Bookings\Models\Category;
use function Bookings\returnResult;
use function Bookings\returnSlimError;

class CategoryController extends BaseController
{

    public function create($categoryJson)
    {
        if ($categoryJson == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", null);
        }

        $category = Category::fromJson($categoryJson);
        if ($category->id !== 0) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "id must be null", null);
        }

        $inserted_id = $this->insert(
            "INSERT INTO bookings_category(title) VALUES (:s)",
            array($category->title));

        if ($inserted_id > 0) {
            returnResult($this->get($inserted_id));
        } else {
            returnSlimError(GlobalErrors::$NOTHING_CHANGED);
        }
    }

    public function get($id)
    {
        return Category::fromDb($this->fetchOne("SELECT * FROM bookings_category WHERE id = :d", $id));
    }

    public function getAll()
    {
        $categories = array();
        foreach ($this->fetchAll("SELECT * FROM bookings_category") as $row) {
            array_push($categories, Category::fromDb($row));
        }

        return $categories;
    }

    public function update($id, $categoryJson)
    {
        if ($categoryJson == null || $id == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", null);
        }

        $category = Category::fromJson($categoryJson);
        if ($category->id !== $id) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "id not matching", null);
        }

        $existingCategory = $this->get($id);
        if ($existingCategory == null) {
            return returnSlimError(GlobalErrors::$RESOURCE_NOT_FOUND);
        }

        $rows = $this->updateAll(
            "UPDATE bookings_category SET title = :s WHERE id = :d",
            array($category->title, $category->id));

        if ($rows > 0) {
            returnResult($category);
        } else {
            returnSlimError(GlobalErrors::$NOTHING_CHANGED);
        }
    }
}