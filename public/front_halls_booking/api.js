// API helper functions for communicating with Laravel backend

const API_BASE = "http://127.0.0.1:8000/api"; // adjust to your API server address

// Helper to extract error message from axios error
const getErrorMessage = (error) => {
  if (error.response?.data?.message) {
    return error.response.data.message;
  }
  if (error.response?.data?.error) {
    return error.response.data.error;
  }
  if (error.response?.statusText) {
    return error.response.statusText;
  }
  if (error.message) {
    return error.message;
  }
  return "An error occurred";
};

const apiFetch = (path, opts = {}) => {
  const config = {
    ...opts,
    baseURL: API_BASE,
  };
  return window
    .axios(path, config)
    .then((res) => res.data)
    .catch((error) => {
      throw {
        message: getErrorMessage(error),
        status: error.response?.status,
        data: error.response?.data,
      };
    });
};

const apiGet = (path) =>
  window.axios
    .get(`${API_BASE}${path}`)
    .then((res) => res.data)
    .catch((error) => {
      throw {
        message: getErrorMessage(error),
        status: error.response?.status,
        data: error.response?.data,
      };
    });

const apiPost = (path, data) => {
  const config = {};
  if (data instanceof FormData) {
    config.headers = { "Content-Type": "multipart/form-data" };
  }
  return window.axios
    .post(`${API_BASE}${path}`, data, config)
    .then((res) => res.data)
    .catch((error) => {
      throw {
        message: getErrorMessage(error),
        status: error.response?.status,
        data: error.response?.data,
      };
    });
};

const apiPut = (path, data) =>
  window.axios
    .put(`${API_BASE}${path}`, data)
    .then((res) => res.data)
    .catch((error) => {
      throw {
        message: getErrorMessage(error),
        status: error.response?.status,
        data: error.response?.data,
      };
    });
