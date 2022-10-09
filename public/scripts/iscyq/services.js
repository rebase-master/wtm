'use strict';

/* Services */

var isccpServices = angular.module('isccpServicesServices', ['ngResource']);

isccpServices.factory('Phone', ['$resource',
  function($resource){
    return $resource('phones/:phoneId.json', {}, {
      query: {method:'GET', params:{year:'year', questionId:'qid'}, isArray:true}
    });
  }]);
