<?php

namespace Bookings\Controller;


use Bookings\GlobalErrors;
use Bookings\Models\Resource;
use function Bookings\returnResult;
use function Bookings\returnSlimError;

class ResourceController extends BaseController
{

    public function create($json)
    {
        if ($json == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", null);
        }

        $resource = Resource::fromJson($json);
        if ($resource->id > 0) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "id must be null", null);
        }

        $inserted_id = $this->insert(
            "INSERT INTO bookings_resource(category_id, title, quantity) VALUES (:d, :s, :d)",
            array($resource->category_id, $resource->title, $resource->quantity));

        if ($inserted_id > 0) {
            returnResult($this->get($inserted_id));
        } else {
            returnSlimError(GlobalErrors::$NOTHING_CHANGED);
        }
    }

    public function get($id)
    {
        return Resource::fromDb($this->fetchOne("SELECT * FROM bookings_resource WHERE id = :d", $id));
    }

    public function getAll()
    {
        $categoryId = $this->getParam("category_id");
        if (!$this->isInt($categoryId, 0)) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing category_id", null);
        }
        $resources = array();
        foreach ($this->fetchAll("SELECT * FROM bookings_resource WHERE category_id = :d", [$categoryId]) as $row) {
            array_push($resources, Resource::fromDb($row));
        }

        return $resources;
    }

    public function update($id, $json)
    {
        if ($json == null || $id == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", null);
        }

        $resource = Resource::fromJson($json);
        if ($resource->id !== $id) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "id not matching", null);
        }

        $existingResource = $this->get($id);
        if ($existingResource == null) {
            return returnSlimError(GlobalErrors::$RESOURCE_NOT_FOUND);
        }

        $rows = $this->updateAll(
            "UPDATE bookings_resource SET category_id = :d, title = :s, quantity = :d WHERE id = :d",
            array($resource->category_id, $resource->title, $resource->quantity, $resource->id));

        if ($rows > 0) {
            returnResult($resource);
        } else {
            returnSlimError(GlobalErrors::$NOTHING_CHANGED);
        }
    }

    public function getExtras($id)
    {
        $resource = $this->get($id);
        if ($resource == null) {
            return returnSlimError(GlobalErrors::$RESOURCE_NOT_FOUND);
        }

        $extraController = new ExtraController();
        $extras = [];
        foreach ($extraController->getExtrasForResource($resource) as $extra) {
            array_push($extras, $extra->createDisplayConfig());
        }
        return $extras;
    }
}