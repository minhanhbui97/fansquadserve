export async function getAllTickets() {
  const response = await axios.get('/api/tickets');
  return response.data;
}

export async function getTicketById(id) {
  const response = await axios.get(`/api/tickets/${id}`);
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
export async function getAllOperatingSystems() {
  const response = await axios.get('/api/operating_systems');
  return response.data;
}

export async function getAllPrograms() {
  const response = await axios.get('/api/programs');
  return response.data;
}

export async function getAllTypeofMachines() {
  const response = await axios.get('/api/type_of_machines');
  return response.data;
}

export async function getAllCourses() {
  const response = await axios.get('/api/courses');
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
  return response.data;
}

export async function 

createTicket(data) {
  const response = await axios.post(`/api/tickets/`, data);
  return response.data;
}
