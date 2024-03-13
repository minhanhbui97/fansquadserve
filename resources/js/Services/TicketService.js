export async function getAllTickets() {
    const response = await axios.get('/api/tickets')
    return response.data;
}