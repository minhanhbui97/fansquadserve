export async function getStudent(fanshaweId) {
    const response = await axios.get(`/api/students/${fanshaweId}`);
    return response.data;
}

export async function getReferenceNumber() {
    const response = await axios.get(`/api/reference-number`);
    return response.data;
}

export async function createStudent(payload) {
    const response = await axios.post('/api/students', payload)
    return response.data;
}

export async function updateStudent(id, payload) {
    const response = await axios.put(`/api/students/${id}`, payload)
    return response.data;
}