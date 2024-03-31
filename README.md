# FanSquadServe
## Installation
- clone repo
- checkout branch dev
- clone ".env.example" file content to a new file, name it ".env" (same directory)
- install Laravel: https://laravel.com/docs/7.x/installation (php, mysql, composer)
- cmd to run BE server:
    - php artisan migrate:fresh --seeder DatabaseSeeder
    - php artisan serve

=======
    - php artisan migrate:fresh
    - php artisan db:seed (not needed at this point)

- cmd to run FE server:
    - npm install {package_name} (when first installing new dependencies)
    - npm run dev
- ready to use components
    - PrimeVue: https://primevue.org/vite
- Vue documentation: https://vuejs.org/guide/introduction.html

# Frontend



## Pages

### Landing Page
- Route: /
- Link to Service Request Page (Student)
- Link to Login Page (LabSquad)


### Service Request Page
- Route: /service-request
- Include form to submit ticket


### Confirmation Page
- Route: /confirmation
- Include confirmation info of the submitted ticket

### Login Page
- Route: /login


### Ticket Queue Page (Homepage)
- Route: /tickets


### Ticket Detail Page
- Route: /ticket-detail


### Data Dashboard Page
- Route: /data-dashboard


### User Management Page 
- Route: /users
