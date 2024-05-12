import axios from "axios";

const URL = "http://127.0.0.1:8000/";
// const URL = "http://192.168.1.4:5000/";

const instance = axios.create({
  baseURL: URL,
  withCredentials: false,
});

instance.interceptors.request.use(
  function (request) {
    request.headers = request.headers || {};

    // Check if the request is sending form data (e.g., uploading a file)
    if (request.data instanceof FormData) {
      // Set Content-Type to "multipart/form-data" for form data requests
      request.headers["Content-Type"] = "multipart/form-data";
    } else {
      // Set Content-Type to "application/json" for other requests
      request.headers["Content-Type"] = "application/json";
    }

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
