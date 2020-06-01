import { routes } from './routes';

const $ = require('jquery');

interface ApiResponseInterface {
    data: [],
    valid: boolean,
    error: string|null
}

type RequestType = 'GET' | 'POST';
type ResponseCallbackType = (data: ApiResponseInterface) => void;

interface ApiModuleInterface {
    doRequest(url: string, data: object, success: ResponseCallbackType, method: RequestType);
    doGetRequest(url: string, data: object, success: ResponseCallbackType);
    doPostRequest(url: string, data: object, success: ResponseCallbackType);
    checkEmail(email: string, callback: ResponseCallbackType)
}

const __xhrStack = {};

export const ApiModule: ApiModuleInterface = {
    doRequest(url: string, data: object, callback: ResponseCallbackType, method: RequestType) {
        if (!!__xhrStack[url] && __xhrStack[url].readyState != 4) {
            __xhrStack[url].abort();
        }

        __xhrStack[url] = $.ajax({
            url, method, data, success: (data) => {
                const { response } = data;
                callback(response);
            }
        })
    },
    doGetRequest(url: string, data: object, callback: (data: ApiResponseInterface) => void) {
        this.doRequest(url, data, callback, "GET");
    },
    doPostRequest(url: string, data: object, callback: (data: ApiResponseInterface) => void) {
        this.doRequest(url, data, callback, "POST");
    },
    checkEmail(email: string, callback: (data: ApiResponseInterface) => void) {
        this.doPostRequest(routes.checkEmail, { email }, callback)
    },
}
