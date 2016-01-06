(function () {
    angular.module('bkClient')
        .factory('ObjectService', ObjectService);

    ObjectService.$inject = [];
    function ObjectService() {
        return {
            objects: [
                {id: 1, title: 'Ford'},
                {id: 2, title: 'Opel'}
            ]
        };

        //////////


    }
})();