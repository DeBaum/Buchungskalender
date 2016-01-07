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
                    thumbnail: '//localhost.de/static/auto.jpg'
                },
                {
                    id: 2,
                    title: 'Räume',
                    thumbnail: '//localhost.de/static/raum.jpg'
                },
                {
                    id: 3,
                    title: 'Beamer',
                    thumbnail: '//localhost.de/static/beamer.jpg'
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