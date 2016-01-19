(function () {
    angular.module('bkClient')
        .controller('ExtrasController', ExtrasController);

    ExtrasController.$inject = ['ObjectResource', '$scope'];
    function ExtrasController(ObjectResource, $scope) {
        var vm = this;
        var extras = [];

        vm.selectedExtras = {};
        vm.getFields = getFields;
        vm.getForms = getForms;
        vm.getSelectDefault = getSelectDefault;
        vm.getAll = getAll;

        setDefaults();
        $scope.$watch('booking.bookingData.objectId', loadExtras);

        ////////////

        function setDefaults() {
            _(extras)
                .filter(function (e) {return e.default != null})
                .map('id')
                .forEach(function (e) {vm.selectedExtras[e] = true});
        }

        function loadExtras(objectId) {
            ObjectResource.getExtras({id: objectId}, function (objectExtras) {
                extras = objectExtras;
            });
        }

        function getFields() {
            return _.filter(extras, function (e) {
                return e.type != 'form';
            });
        }

        function getForms() {
            return _.filter(extras, function (e) {
                return e.type == 'form';
            });
        }

        function getSelectDefault(extra) {
            if (extra.type == 'select') {
                return _.first(_.find(extra.values, {"default": true}));
            }
            return null;
        }

        function getAll() {
            return extras;
        }
    }
})();