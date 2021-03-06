import axios from "axios";
import Cookies from "js-cookie";

if(window.location.origin === 'https://channelable.shibbir.dev'){
  window.apiUrl = 'https://channelable.shibbir.dev/api/'
}else if (window.location.origin === 'http://channelable.shibbir.dev') {
  window.apiUrl = 'http://channelable.shibbir.dev/api/'
}else{
  window.apiUrl = 'http://localhost:8000/api/'
}

export const http = {
    get: (url) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${Cookies.get("jwt-token")}`
            };
            axios.get(window.apiUrl + url, {headers: header}).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    post: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${Cookies.get("jwt-token")}`
            };
            axios.post(window.apiUrl + url, data, {headers: header}).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    put: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${Cookies.get("jwt-token")}`
            };
            axios.put(window.apiUrl + url, data, {headers: header}).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    delete: (url) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${Cookies.get("jwt-token")}`
            };
            axios.delete(window.apiUrl + url, {headers: header}).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    file: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            let header = {
                'Authorization': `Bearer ${Cookies.get("jwt-token")}`,
                'Content-Type': 'multipart/form-data'
            };
            axios.post(apiUrl + url, data, {headers: header}).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    },
    postWithoutToken: (url, data = {}) => {
        return new Promise((resolve, reject) => {
            axios.post(window.apiUrl + url, data).then(response => {
                resolve(response);
            }).catch(e => {
                reject(e)
            });
        })
    }
};
