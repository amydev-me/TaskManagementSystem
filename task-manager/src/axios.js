import axios from "axios";

// Create Axios instance
const api = axios.create({
    baseURL: "http://localhost:8000/api", // Change this to your API base URL
});

// Add request interceptor
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token"); // Get token from storage
    if (token) {
        config.headers["Authorization"] = `Bearer ${token}`; // Attach token
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Add response interceptor (optional)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
        localStorage.removeItem("token"); // Clear token if unauthorized
        window.location.href = "/login"; // Redirect to login page
    }
    return Promise.reject(error);
  }
);

export default api;
