(function () {
    angular.module('bkClient')
        .factory('bookingDataFactory', bookingDataFactory);

    bookingDataFactory.$inject = ['$stateParams'];
    function bookingDataFactory($stateParams) {
        var r = {
            categoryId: null,
            objectId: null,
            start: null,
            end: null,
            timeAround: {before: 0, after: 0},

            hasTime: hasTime,
            update: readParams
        };
        readParams();
        return r;

        //////////

        function hasTime() {
            return r.start && r.end;
        }

        function readParams() {
            r.categoryId = $stateParams.categoryId || r.categoryId;
            r.objectId = $stateParams.objectId || r.objectId;
            r.start = $stateParams.start ? moment($stateParams.start) : r.start;
            r.end = $stateParams.end ? moment($stateParams.end) : r.end;
        }
    }
})();