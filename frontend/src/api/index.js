import axios from "axios";

const URL = "http://127.0.0.1:8000/";
// const URL = "http://192.168.1.4:5000/";

const instance = axios.create({
  baseURL: URL,
  withCredentials: false,
});

instance.interceptors.request.use(
  function (request) {
    // Ensure that request.headers exists and initialize it if it doesn't
    request.headers = request.headers || {};

    // Set the headers
    request.headers["Content-Type"] = "application/json,text/html";
    request.headers["Accept"] = "Application/json";
    request.headers["Authorization"] =
      "Bearer " + localStorage.getItem("attendance-token");
    request.headers["Access-Control-Allow-Origin"] = "*";

    return request;
  },
  function (error) {
    return Promise.reject(error);
  }
);

instance.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    return Promise.reject(error);
  }
);
export default instance;
