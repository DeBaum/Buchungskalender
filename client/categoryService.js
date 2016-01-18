(function () {
    angular.module('bkClient')
        .factory('CategoryService', CategoryService);

    CategoryService.$inject = [];
    function CategoryService() {
        var service = {
            getById: getById,
            categories: [
                {
                    id: 1,
                    title: 'Autos',
                    thumbnail: bkRootPath + 'tmp/auto.jpg'
                },
                {
                    id: 2,
                    title: 'Räume',
                    thumbnail: bkRootPath + 'tmp/raum.jpg'
                },
                {
                    id: 3,
                    title: 'Beamer',
                    thumbnail: bkRootPath + 'tmp/beamer.jpg'
                }
            ]
        };
        return service;

        //////////
        function getById(id) {
            return _.find(service.categories, {id: id});
        }

    }
})();