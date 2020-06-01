"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ApiModule = void 0;
var routes_1 = require("./routes");
var $ = require('jquery');
var __xhrStack = {};
exports.ApiModule = {
    doRequest: function (url, data, callback, method) {
        if (!!__xhrStack[url] && __xhrStack[url].readyState != 4) {
            console.log("Aborting: " + url);
            __xhrStack[url].abort();
        }
        __xhrStack[url] = $.ajax({
            url: url, method: method, data: data, success: function (data) {
                var response = data.response;
                callback(response);
            }
        });
    },
    doGetRequest: function (url, data, callback) {
        this.doRequest(url, data, callback, "GET");
    },
    doPostRequest: function (url, data, callback) {
        this.doRequest(url, data, callback, "POST");
    },
    checkEmail: function (email, callback) {
        this.doPostRequest(routes_1.routes.checkEmail, { email: email }, callback);
    },
};
