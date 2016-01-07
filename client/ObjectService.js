(function () {
    angular.module('bkClient')
        .factory('ObjectService', ObjectService);

    ObjectService.$inject = [];
    function ObjectService() {
        var service = {
            objects: [
                {id: 1, title: 'Ford', categoryId: 1},
                {id: 2, title: 'Opel', categoryId: 1},
                {id: 3, title: 'Konferenzraum EG', categoryId: 2},
                {id: 4, title: 'Konferenzraum 1.OG', categoryId: 2},
                {id: 5, title: 'Besprechungsraum 1.OG', categoryId: 2},
                {id: 6, title: 'HDMI-Beamer', categoryId: 3},
                {id: 7, title: 'VGA-Beamer', categoryId: 3},
                {id: 8, title: 'OHP', categoryId: 3},
                {id: 9, title: 'Flipchart', categoryId: 3}
            ],
            getForCategory: getObjectsForCategory,
            getName: getObjectName,
            getById: getObjectById
        };
        return service;

        //////////

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
            if (object == null) id=null;
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