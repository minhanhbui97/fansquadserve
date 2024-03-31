import axios from 'axios';

export async function getAllUsers() {
  const response = await axios.get('/_api/users');
  return response.data;
}

export async function getAllCourses() {
  const response = await axios.get('/_api/courses');
  return response.data;
}

export async function getUserById(id) {
  const response = await axios.get(`/_api/users/${id}`);
  return response.data;
}

export async function getAllRoles() {
  const response = await axios.get('/_api/roles');
  return response.data;
}

export async function createUser(data) {
  const response = await axios.post(`/_api/users/`, data);
  return response.data;
}

export async function updateUser(userId, data) {
  const response = await axios.put(`/_api/users/${userId}`, data);
  return response.data;
}
