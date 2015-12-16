(function () {
    angular.module('bkClient')
        .factory('bookingDataFactory', bookingDataFactory);

    bookingDataFactory.$inject = ['$stateParams'];
    function bookingDataFactory($stateParams) {
        var r = {
            categoryId: $stateParams.categoryId || null,
            objectId: $stateParams.objectId || null,
            start: null,
            end: null,

            hasTime: hasTime
        };
        return r;

        //////////

        function hasTime() {
            return r.start && r.end;
        }
    }
})();