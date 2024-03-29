export async function getAllTickets() {
  const response = await axios.get('/_api/tickets');
  return response.data;
}

export async function getTicketById(id) {
  const response = await axios.get(`/_api/tickets/${id}`);
  return response.data;
}

export async function getAllPriorities() {
  const response = await axios.get('/_api/priorities');
  return response.data;
}

export async function getAllTicketStatuses() {
  const response = await axios.get('/_api/ticket_statuses');
  return response.data;
}
export async function getAllOperatingSystems() {
  const response = await axios.get('/_api/operating_systems');
  return response.data;
}

export async function getAllPrograms() {
  const response = await axios.get('/_api/programs');
  return response.data;
}

export async function getAllTypeofMachines() {
  const response = await axios.get('/_api/type_of_machines');
  return response.data;
}

export async function getAllCourses() {
  const response = await axios.get('/_api/courses');
  return response.data;
}

export async function getUsers(course_id) {
  const response = await axios.get('/_api/users', {
    params: {
      course_id,
    },
  });
  return response.data;
}

export async function updateTicket(ticketId, data) {
  const response = await axios.put(`/_api/tickets/${ticketId}`, data);
  return response.data;
}

export async function 

createTicket(data) {
  const response = await axios.post(`/_api/tickets/`, data);
  return response.data;
}
