export async function getAllTickets() {
    const response = await axios.get('/api/tickets')
    return response.data;
}


export async function getAllPriorities() {
    const response = await axios.get('/api/priorities')
    return response.data;
}

export async function getAllOperatingSystem() {
    const response = await axios.get('/api/operating_systems')
    return response.data;
}

export async function getAllProgram() {
    const response = await axios.get('/api/programs')
    return response.data;
}

export async function getAllTypeofMachines() {
    const response = await axios.get('/api/type_of_machines')
    return response.data;
}

export async function getAllCourses() {
    const response = await axios.get('/api/courses')
    return response.data;
}


