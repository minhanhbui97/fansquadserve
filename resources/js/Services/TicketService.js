export async function getAllTickets() {
  const response = await axios.get('/api/tickets');
  return response.data;
}

export async function getTicketById(id) {
    const response = await axios.get(`/api/tickets/${id}`)
    return response.data;
}

export async function getAllPriorities() {
  const response = await axios.get('/api/priorities');
  return response.data;
}

export async function getAllTicketStatuses() {
  const response = await axios.get('/api/ticket_statuses');
  return response.data;
}

export async function getUsers(course_id) {
  const response = await axios.get('/api/users', {
    params: {
      course_id,
    },
  });
  //Need to pass course_id to get list of assignee available for that course only
  return response.data;
}

export async function updateTicket(ticketId, data) {
  const response = await axios.put(`/api/tickets/${ticketId}`, data);
  return response.data
}
