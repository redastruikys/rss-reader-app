"use strict";
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
Object.defineProperty(exports, "__esModule", { value: true });
// any CSS you import will output into a single css file (app.css in this case)
require("../css/app.css");
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
var $ = require('jquery');
require('bootstrap');
$('.alert').alert();
var RegistrationFormModule_js_1 = require("./module/Forms/RegistrationFormModule.js");
RegistrationFormModule_js_1.RegistrationFormModule.init();
