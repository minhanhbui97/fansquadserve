export async function getAllTickets() {
    const response = await axios.get('/api/tickets')
    return response.data;
}


export async function getAllPriorities() {
    const response = await axios.get('/api/priorities')
    return response.data;
}


