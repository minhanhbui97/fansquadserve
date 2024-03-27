import axios from 'axios';

export async function getAllUsers() {
  const response = await axios.get('/api/users');
  return response.data;
}

export async function getAllCourses() {
  const response = await axios.get('/api/courses');
  return response.data;
}

export async function getUserById(id) {
  const response = await axios.get(`/api/users/${id}`);
  return response.data;
}

export async function getAllRoles() {
  const response = await axios.get('/api/roles');
  return response.data;
}

export async function createUser(data) {
  const response = await axios.post(`/api/users/`, data);
  return response.data;
}

export async function assignRoleToUser(data) {
  const response = await axios.post(`/api/users/`, data);
}

export async function updateUser(userId, data) {
  const response = await axios.put(`/api/users/${userId}`, data);
  return response.data;
}
