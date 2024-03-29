export async function getStudent(fanshaweId) {
    const response = await axios.get(`/_api/students/${fanshaweId}`);
    return response.data;
}

export async function getReferenceNumber() {
    const response = await axios.get(`/_api/reference-number`);
    return response.data;
}

export async function createStudent(payload) {
    const response = await axios.post('/_api/students', payload)
    return response.data;
}

export async function updateStudent(id, payload) {
    const response = await axios.put(`/_api/students/${id}`, payload)
    return response.data;
}