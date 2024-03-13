export async function login(payload) {
  await axios.get('/sanctum/csrf-cookie');
  return axios.post('/login', payload);
}

export async function fetchAuthUser() {
  const data = await axios.get('/api/user');
  return data.data;
}

export async function logout() {
  return await axios.post('/logout');
}
