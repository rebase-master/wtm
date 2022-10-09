/* global angular, $, baseUrl, console, Prism, defLoc */

var iscyq = angular
    .module('iscyq', ['ngRoute','appControllers','ngAnimate'])
    .constant('BASE_URL', baseUrl)
    .config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';

    }]);

iscyq.config(['$routeProvider', function($routeProvider) {
    "use strict";

    $routeProvider.
        when('/:year/:question',{
            controller: 'ISCYQController',
            templateUrl: baseUrl+'isc/yearly'
        }).
        otherwise({
            redirectTo: defLoc
        });
}]);

iscyq.filter('dateToISO', function() {
    return function(input) {
        var t = input.split(/[- :]/);
        input = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
        return new Date(input).toISOString();
    };
});

iscyq.filter('cut', function () {
    return function (value, wordwise, max, tail) {
        if (!value) return '';

        max = parseInt(max, 10);
        if (!max) return value;
        if (value.length <= max) return value;

        value = value.substr(0, max);
        if (wordwise) {
            var lastspace = value.lastIndexOf(' ');
            if (lastspace != -1) {
                //Also remove . and , so its gives a cleaner result.
                if (value.charAt(lastspace-1) == '.' || value.charAt(lastspace-1) == ',') {
                    lastspace = lastspace - 1;
                }
                value = value.substr(0, lastspace);
            }
        }

        return value + (tail || ' …');
    };
});

iscyq.filter('htmlToPlaintext', function() {
        return function(text) {
            return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
        };
    }
);

var appControllers = angular.module('appControllers', []);

appControllers.controller('ISCYQController', ['$scope', '$rootScope', '$http', '$routeParams', '$location', '$sce', function($scope, $rootScope, $http, $routeParams, $location, $sce) {

    "use strict";

    var path = $location.path().substr(1),
        cat = path.split('/'),
        year, qno, type, subject;

    if(typeof cat[1] != "undefined"){
        year = parseInt(cat[0]);
        qno = parseInt(cat[1].substr(1));
        type = $('#type').val().trim();
        subject = $('#subject').val().trim();

    }else{
        return false;
    }

    var dataUrl = 'ajax/agpqbyyear';

    $rootScope.isActive = function(location){

        return location === path;

    };

    //$sce.trustAsResourceUrl(baseUrl+dataUrl);
        $rootScope.loaded = false;
        $http({
            cache: true,
            url: baseUrl+dataUrl,
            method: "GET",
            params: {subject: subject, type: type, year: year, qno: qno }
        }).success(function(response) {
            if(response.status == -1){
                $scope.noresult = true;
                document.title = "Question not found!";
            }else{
                $scope.noresult  = false;
                $scope.data      = response.data;
                $scope.comments  = response.comments;
                $scope.tags      = response.tags;
                $scope.rques     = response.rques;
                $scope.question  = $sce.trustAsHtml(response.data.question);
                $scope.solution  = $sce.trustAsHtml(response.data.solution);
                //Prism.highlightAll();
                document.title = "ISC Computer Science Practical "+response.data.year+" - Questions "+response.data.position;
            }
        });

    $scope.$watch('solution', function (newVal, oldVal) {
        setTimeout(function () {
            var code = document.getElementsByTagName('code');
            angular.forEach(code, function(c) {
                Prism.highlightElement(c);
            });
        }, 1);
    });

    $scope.$watch('question', function (newVal, oldVal) {
        setTimeout(function () {
            var code = document.getElementsByTagName('code');
            angular.forEach(code, function(c) {
                Prism.highlightElement(c);
            });
        }, 1);
    });

}]);//ISCYQController

