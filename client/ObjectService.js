(function () {
    angular.module('bkClient')
        .factory('ObjectService', ObjectService);

    ObjectService.$inject = ['ObjectResource'];
    function ObjectService(ObjectR) {
        var service = {
            objects: [],
            getForCategory: getObjectsForCategory,
            getName: getObjectName,
            getById: getObjectById,
            load: loadObject
        };
        return service;

        //////////

        function loadObject(id) {
            var fn = ObjectR.get;
            if (!id) {
                fn = ObjectR.getAll;
            }
            fn({id: id}, function (objects) {
                if (_.isArray(objects)) {
                    objects = [objects];
                }
                _.forEach(objects, insertObject);
            });
        }

        function insertObject(object) {
            var index = _.findIndex(service.objects, {id: object.id});
            if (index === -1) {
                service.objects.push(object);
            } else {
                service.objects[index] = object;
            }
        }

        function getObjectsForCategory(category) {
            var id;
            if (category == null) id = null;
            else if (typeof category === 'number') id = category;
            else id = category.id;

            if (id == null) return [];
            return _.filter(service.objects, {categoryId: id});
        }

        function getObjectName(object) {
            var id;
            if (object == null) id = null;
            else if (typeof object == 'number') id = object;
            else id = object.id;

            var o = getObjectById(id);

            if (o == null) return '';
            return o.title;
        }

        function getObjectById(id) {
            return _.find(service.objects, {id: id});
        }
    }
})();